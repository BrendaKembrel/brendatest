<?

session_start();

//HELPER FUNCTIONS NOT ASSOCIATED DIRECTLY WITH ANYTHING IN PARTICULAR

/**
 * Calculating the difference between two dates
 * @author: Elliott White
 * @author: Jonathan D Eisenhamer.
 * @link: http://www.quepublishing.com/articles/article.asp?p=664657&rl=1
 * @since: Dec 1, 2006.
 */

// Will return the number of days between the two dates passed in
function count_days( $a, $b )
{	
	$aArray = explode("-", $a);
	$bArray = explode("-", $b);
	
	$a = strtotime( $aArray[1]."/".$aArray[2]."/".$aArray[0]." 12:00am" );
	$b = strtotime( $bArray[1]."/".$bArray[2]."/".$bArray[0]." 12:00am" );
	
	if( function_exists( 'date_default_timezone_set' ) )
	{
		// Set the default timezone to US/Eastern
		date_default_timezone_set( 'US/Eastern' );
	}

    // First we need to break these dates into their constituent parts:
    $gd_a = getdate( $a );
    $gd_b = getdate( $b );

    // Now recreate these timestamps, based upon noon on each day
    // The specific time doesn't matter but it must be the same each day
    $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
    $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );

    // Subtract these two numbers and divide by the number of seconds in a
    //  day. Round the result since crossing over a daylight savings time
    //  barrier will cause this time to be off by an hour or two.
    return round( ( $a_new - $b_new ) / 86400 );
}

//FUNCTIONS ASSOCIATED WITH IMAGE INFO
//the following function returns the name of the default image
//associated with the color (in the event that a product has different
//images for different colors)
function getDefaultImage($con, $productId, $color){
	
	$sql = "SELECT imageId
			FROM productColor
			WHERE productId = '$productId'
			AND color LIKE '$color'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$imageId = $row["imageId"];
	
	//now if the imageId = -1 or 0,
	//then we know that the image is just the usual default image
	//no matter the product's color
	if($imageId==-1 || $imageId ==0){
		
		$sql = "SELECT image
			FROM images
			WHERE productId = '$productId'
			AND type LIKE 'default'";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$image = $row["image"];
		
		return $row["image"];
	
	}	
	//otherwise, we find the image with that imageId
	else{
		
		$sql = "SELECT image
			FROM images
			WHERE productId = '$productId'
			AND imageId = '$imageId'";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		return $row["image"];
	
	}
	
}

//FUNCTIONS ASSOCIATED WITH COLOR INFO


//FUNCTIONS ASSOCIATED WITH ISNEW INFO

//we use the following function to determine if something isNew or not
//returns true if new, false if not
//NOTE: THE ASSUMPTION IS THAT, IF A PRODUCT HAS
//AT LEAST ONE NEW COLOUR, THEN IT IS CONSIDERED NEW
function getIsNew($con, $productId){
	
	$today = date("Y-m-d");
	
	//isNew depends on the colors of a product
	//so we must look through all active colors of a
	//product to determine whether or not it is new
	$sql = "SELECT isNewUntil, activationDate
			FROM productColor
			WHERE deactivationDate>'$today'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
		
		$activationDate = $row[" ActivationDate"];
		$isNewUntil = $row["isNewUntil"];
		
		$daysSinceActivation = count_days( $today, $activationDate);
		$remDays = $isNewUntil-$daysSinceActivation;
		
		//only one color being new is sufficient,
		//so as soon as we find a new one, we return true
		if($remDays>=0){
		
			return true;
		}
	}
	
	//if we reach here, then this means we have not encountered a new
	//color and therefore we return false
	return false;
}


//FUNCTIONS ASSOCIATED WITH PROMO CALCULATION

//this function computes the new cart price
//after the effect of any promo that has an effect on the cart total
//as opposed to having an effect on individual prices of products
function getCartPromoEffect($con, $rowPromo, $originalCartPrice, $adjustedCartPrice){
	
	
	$originalCartPrice = (real) $originalCartPrice;
	$adjustedCartPrice = (real) $adjustedCartPrice;
	
	//here we're dealing with a single effect
	//for a promoCode.  The id is the id of one of
	//possibly many effects associated with a promoCode
	
	$currency = $_SESSION["currency"];
	
	$rate = (real) $rowPromo["rate"];
	$denomination = $rowPromo["denomination"];
	$id = $rowPromo["id"];
	
	//we get the id 
	$sqlOps = "SELECT *
			   FROM promoSpecifics
			   WHERE id = '$id'";
			   
	$resultOps = mysql_query($sqlOps, $con) or die(mysql_error());
	
	//we loop through all of the conditions
	//if a single condition doesn't apply to the original, then we immediately return
	//the adjusted cart price in its current state, without further adjustment
	//if we get through the while loop, then we know that all conditions applied
	//and therefore we adjust the cartPrice by rate and denomination as appropriate
	while($rowOps = mysql_fetch_array($resultOps, MYSQL_ASSOC) ){
		
		$thing = $rowOps["thing"];
		$operator = $rowOps["operator"];
		$value = (real) $rowOps["value"];
		
		
		if(strcasecmp($thing, "currency")==0){
			
			if(strcasecmp($currency, $value)!=0){
				
				//clearly we are not affected by the effect
				return $adjustedCartPrice;
			}
			//if yes, then we can continue going through the loop
			//because we know that there's still a chance that we meet
			//all other conditions
			
		}		
		elseif(strcasecmp($thing, "cart total")==0){
			
			//now, we need to figure out what the operators are
			if(strcasecmp("=", $operator)==0){
				
				if($originalCartPrice==$value){
					//we're ok
				}
				else{
					return $adjustedCartPrice;
				}
			}
			elseif(strcasecmp(">=", $operator)==0){
				
				if($originalCartPrice>=$value){
					//we're ok
				}
				else{
					return $adjustedCartPrice;
				}
			}
			elseif(strcasecmp(">", $operator)==0){
				
				if($originalCartPrice>$value){
					//we're ok
				}
				else{
					return $adjustedCartPrice;
				}
			}
			elseif(strcasecmp("<=", $operator)==0){
			
				if($originalCartPrice<=$value){
					//we're ok
				}
				else{
					return $adjustedCartPrice;
				}
			
			}
			elseif(strcasecmp("<", $operator)==0){
				
				if($originalCartPrice<$value){
					//we're ok
				}
				else{
					return $adjustedCartPrice;
				}
			}
			else{
				//it's a != case
				if($originalCartPrice!=$value){
					//we're ok
				}
				else{
					return $adjustedCartPrice;
				}
			}
			
		}
		
	}//end while
	
	//now, if we get here, we know we have to adjust the cart price
	
	if(strcasecmp($denomination, "percentage")==0){
		
		$adjustedCartPrice = $adjustedCartPrice* (1.0-($rate/100));
		
	}
	else{
		
		$adjustedCartPrice -= $rate;
		
		
	}
	
	return number_format($adjustedCartPrice, 2);
}

//this function computes the price of an individual product
//when subject to the effect of a certain promo
//where this effect has id $rowPromo[id]
//(this ignores any effect that considers the cartTotal)

function getPriceAfterEffectOfPromo($con, $rowPromo, $row, $finalPrice){
	
	$currency = $_SESSION["currency"];
	
	$productId = $row["productId"];
	
	$rate = $rowPromo["rate"];
	$denomination = $rowPromo["denomination"];
	$id = $rowPromo["id"];
	
	//this is the sqlString we will build up
	//to figure out if our product is affected by the effect with id $id
	$sqlString = "SELECT *
				  FROM product
				  WHERE productId = '".$productId."'";
	
	//now we want to get the specific
	//order of operations relating to this id
	//in other words, we want to see if the current product
	//can have the promo applied to it or not
	//based on the operations
	
	
	$sqlOps = "SELECT *
			   FROM promoSpecifics
			   WHERE id = '$id'";
			   
	$resultOps = mysql_query($sqlOps, $con) or die(mysql_error());
	
	while($rowOps = mysql_fetch_array($resultOps, MYSQL_ASSOC) ){
		
		$thing = $rowOps["thing"];
		$operator = $rowOps["operator"];
		$value = $rowOps["value"];
		

		//$thing can equal one of the following
		//if $thing is cartTotal, we ignore it
		if(strcasecmp($thing, "brand")==0 || strcasecmp($thing, "category")==0 || strcasecmp($thing, "collection")==0 || strcasecmp($thing, "itemType")==0){
			
			$sqlString.=" AND ".$thing." ".$operator." '".$value."'";
		
		}
		elseif(strcasecmp($thing, "price")==0){
			
			if(strcasecmp($currency, "CAD")==0){
				
				$thing = "canFinalPrice";
			}
			else{
			
				$thing = "finalPrice";
			}
			
			$sqlString.=" AND ".$thing." ".$operator." '".$value."'";
		}
		elseif(strcasecmp($thing, "priceType")==0){
			
			if(strcasecmp($currency, "CAD")==0){
				
				$thing = "canPriceType";
			}
			else{
			
				$thing = "priceType";
			}
			
			$sqlString.=" AND ".$thing." ".$operator." '".$value."'";
		}
		elseif(strcasecmp($thing, "isNew")==0){
			
			//if we enter here, then we need to check
			//if the product isNew .  
			
			//If not, then we know right away,
			//no matter what the operators are, that the effect won't apply
			//so we return $finalPrice in its current form
			//thus exiting the loop immediately
			if(!getIsNew($con, $productId)){
				
				return $finalPrice;
			}
			//if yes, then we can continue going through the loop
			//because we know that the finalPrice still has a chance  
			
		}
		elseif(strcasecmp($thing, "currency")==0){
			
			if(strcasecmp($currency, $value)!=0){
				
				//clearly we are not affected by the effect
				return $finalPrice;
			}
			//if yes, then we can continue going through the loop
			//because we know that the finalPrice still has a chance
			//to be affected by this effect
			
		}		
		else{
			//ignore
		}
		
	}
	
	//now that we've built up our sql call,
	//we check if it returns a row
	//if it does, then the promo applies!!
	
	//PLEASE NOTE: if the only condition was isNew
	//or the currency, and these were met, then the sqlString
	//will only be select * from product where productId=productId
	//which we know is true.  Therefore the finalPrice will be affected.
	//if these conditions were not met, then the finalPrice was already 
	//returned unaffected
	
	$result = mysql_query($sqlString, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(!empty($row)){
		
		//the price is affected!
		if(strcasecmp($denomination, "percentage")==0){
			
			$finalPrice = round(  ( 1.00-( (real)($rate) / 100.00 ) )*$finalPrice, 2);
			
		}
		else{
			
			//we're dealing with a fixed amount
			$finalPrice = round( ($finalPrice - $rate) , 2);
		}
	
	}
	
	return $finalPrice;
}

//FUNCTIONS ASSOCIATED WITH PRODUCT PRICE INFO

//this function returns an array
//of 5 values (where the info is based on the value
//of the currency)
//array[0] = price (reg)
//array[1] = finalPrice (reg, our, sale, clearance)
//array[2] = priceType
//array[3] = new price given possible promo
//array[4] = the percentage saved either via promo or just via finalPrice

//$con: database connection
//$row: the array of rows resulting from your sql call
//$currency:  the currency currently being used
function getPriceInfo($con, $row, $currency){
	
	
	$promoPrice = 0;
	$percentageOff = 0;
	
	if(strcasecmp($currency, "CAD")==0){
	
		$price = number_format($row["canPrice"], 2);
		$finalPrice = number_format($row["canFinalPrice"], 2);
		$priceType = $row["canPriceType"];
		
	}
	else{
	
		$price = number_format($row["price"], 2);
		$finalPrice = number_format($row["finalPrice"], 2);
		$priceType = $row["priceType"];
		
	}
	
	//first we deal with potential sitewide promos
	//which apply to everyone, without a promo
	//and are never mutually exclusive
	//(they behave more like specials)
	
	//we need today's date to be able to know if a promo is 
	//expired / released or not
	
	//also note that, since we're only dealing with promos that apply
	//to the price of a single item, we only want those where valueThatGetsReduced LIKE product price
	
	$date = date("Y-m-d");
	
	$sqlPromo = "SELECT *
				FROM promos
				WHERE expiryDate>'$date'
				AND releaseDate<='$date'
				AND type LIKE 'sitewide with no promo'
				AND valueThatGetsReduced LIKE 'product price'";
	
	$resultPromo = mysql_query($sqlPromo, $con) or die(mysql_error());
	
	$counter = 0;
	while( $rowPromo = mysql_fetch_array($resultPromo, MYSQL_ASSOC) ){
		
		if($counter==0){
			
			$promoPrice = $finalPrice;
		}
		
		
		$promoPrice = getPriceAfterEffectOfPromo($con, $rowPromo, $row, $promoPrice);
		
		$counter++;
	}
	
	//the promos stored in the promoArray are combinable
	//with all other promos in the promoArray
	//the check for a valid promo (ie: expiry date, release date, mutex)
	//is done when the promo is added to cart by the user
	if(isset($_SESSION["promoArray"]) ){
		
		foreach($_SESSION["promoArray"] as $promoCode){
			
			//again, we only care here about promos that 
			//affect the individual price of items
			$sqlPromo = "SELECT *
						 FROM promos
						 WHERE promoCode LIKE '$promoCode'
						 AND valueThatGetsReduced LIKE 'product price'";
			
			while( $rowPromo = mysql_fetch_array($resultPromo, MYSQL_ASSOC) ){
				
				//we do not reset the counter to 0, because it is possible
				//that a product is affected by a sitewide promo
				//and then a promo with a promoCode
				if($counter==0){
					
					//we want to initially set the promoPrice 
					//to the current price of the item, without any promos
					$promoPrice = $finalPrice;
				}
				
				$promoPrice = getPriceAfterEffectOfPromo($con, $rowPromo, $row, $promoPrice);
				
				$counter++;
			}
		}
		
	}//end if isset($_SESSION["promoArray"])
	
	//finally, once we've gone through all potential promos,
	//if the product was never affected by a promo, then promoPrice == finalPrice
	
	//note: if we never entered any promo loops, then promoPrice will already = 0
	if($promoPrice==$finalPrice){
	
		$promoPrice = 0;
	}

	//we calculate the percentageOff based on the finalPrice if the promoPrice
	//doesn't exist (ie: there is no promo)
	if($promoPrice==0&&$finalPrice!="0"&&$finalPrice!="0.00"&&$finalPrice!=""){
			
		$percentageOff = round(100*(($price - $finalPrice)/$price));
	}
	elseif($promoPrice!="0"&&$promoPrice!="0.00"&&$promoPrice!=""){
		//otherwise, if the promoPrice does exist, then we calculate the finalPrice off of this
		$percentageOff = round(100*(($price - $promoPrice)/$price));
	}
	return array($price, $finalPrice, $priceType, $promoPrice, $percentageOff);
}

//this function formats the price as follows (it returns a String)
//priceStyleArray[0] = reg price class
//priceStyleArray[1] = class associated with the intermediate price (if applicable)
//priceStyleArray[2] = class associated with the final discounted price (in the event of a discount)

//shortenedVersion is a boolean; true means that we want to condense the formatting as much as possible
//whereas false means that we can be as wordy as we want
function formatPrice($currency, $priceInfoArray, $priceStyleArray, $shortenedVersion){
	
	//note, we only display percentage off for promos, sale and clearance prices
	//we don't display them for "our price"
	
	$price = number_format($priceInfoArray[0], 2);
	$finalPrice = number_format($priceInfoArray[1], 2);
	$promoPrice = number_format($priceInfoArray[3], 2);
	
	$priceType = $priceInfoArray[2];
	$percentageOff = $priceInfoArray[4];
	
	$regPriceClass = $priceStyleArray[0];
	$intermediatePriceClass = $priceStyleArray[1];
	$finalDiscountedPriceClass = $priceStyleArray[2];
	
	$priceString = "";
	
	if($shortenedVersion){
		
		$pOffString = "</p>";
		$regPriceStr = "Regular";
		$ourPriceStr = "Our Price";
		$salePriceStr = "Sale";
		$clearPriceStr = "Clearance";
	}
	else{
		
		$pOffString = " (".$percentageOff."% Off!)</p>";
		$regPriceStr = "Regular Price";
		$ourPriceStr = "Our Price";
		$salePriceStr = "Sale Price";
		$clearPriceStr = "Clearance";
	}
	
	switch($priceType){
									
		case(2): {
			
			if($promoPrice!="0"&&$promoPrice!="0.00"&&$promoPrice!=""){
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$price." ".$currency."</p>
								<p class='".$intermediatePriceClass."'>".$ourPriceStr.": $".$finalPrice." ".$currency."</p>
								<p class='".$finalDiscountedPriceClass."'>Promo: $".$promoPrice." ".$currency.$pOffString;
								
								
								
			}
			else{
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$price." ".$currency."</p>
								<p class='".$finalDiscountedPriceClass."'>".$ourPriceStr.": $".$finalPrice." ".$currency;
								
			}
			
			break;
		}
		case(3): {
			
			if($promoPrice!="0"&&$promoPrice!="0.00"&&$promoPrice!=""){
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$price." ".$currency."</p>
								<p class='".$intermediatePriceClass."'>".$salePriceStr.": $".$finalPrice." ".$currency."</p>
								<p class='".$finalDiscountedPriceClass."'>Promo: $".$promoPrice." ".$currency.$pOffString;
			}
			else{
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$price." ".$currency."</p>
								<p class='".$finalDiscountedPriceClass."'>".$salePriceStr.": $".$finalPrice." ".$currency.$pOffString;
			}
			break;
		}
		case(4): {
			
			if($promoPrice!="0"&&$promoPrice!="0.00"&&$promoPrice!=""){
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$price." ".$currency."</p>
								<p class='".$intermediatePriceClass."'>".$clearPriceStr.": $".$finalPrice." ".$currency."</p>
								<p class='".$finalDiscountedPriceClass."'>Promo: $".$promoPrice." ".$currency.$pOffString;
			}
			else{
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$price." ".$currency."</p>
								<p class='".$finalDiscountedPriceClass."'>".$clearPriceStr.": $".$finalPrice." ".$currency.$pOffString;
			}
			
			break;
		}
		default: {
			
			if($promoPrice!="0"&&$promoPrice!="0.00"&&$promoPrice!=""){
				
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$finalPrice." ".$currency."</p>
								<p class='".$finalDiscountedPriceClass."'>Promo: $".$promoPrice." ".$currency.$pOffString;
				
			}
			else{
				$priceString = "<p class='".$regPriceClass."'>".$regPriceStr.": $".$finalPrice." ".$currency."</p>";
			}
			
			break;
		}
	
	}//end switch
		
	return $priceString;
}

//FUNCTIONS ASSOCIATED WITH SHOPPING CART PRICE

//cartPrice = the total price of the items in the cart
//without applying any promos that deal with cart totals 
//and without applying taxes or shipping
//note that promos that apply to INDIVIDUAL products will be applied
function calculatePrice($con, $originalCartPrice){
	
	$currency = $_SESSION["currency"];
	
	//originalCartPrice is the untouched cartPrice
	//the adjusted one will be the one that goes through all the promos
	$adjustedCartPrice = $originalCartPrice; 
	
	//first we deal with potential sitewide promos
	//which apply to everyone, without a promo
	//and are never mutually exclusive
	//(they behave more like specials)
	
	//we need today's date to be able to know if a promo is 
	//expired / released or not
	$date = date("Y-m-d");
	
	//note that we only care for promos that deal with the cart total
	$sqlPromo = "SELECT *
				FROM promos
				WHERE expiryDate>'$date'
				AND releaseDate<='$date'
				AND type LIKE 'sitewide with no promo'
				AND valueThatGetsReduced LIKE 'cart total'";
	
	$resultPromo = mysql_query($sqlPromo, $con) or die(mysql_error());
	
	$counter = 0;
	
	while( $rowPromo = mysql_fetch_array($resultPromo, MYSQL_ASSOC) ){
		
		$cartPrice = getCartPromoEffect($con, $rowPromo, $originalCartPrice, $adjustedCartPrice);
		
	}
	
	//the promos stored in the promoArray are combinable
	//with all other promos in the promoArray
	//the check for a valid promo (ie: expiry date, release date, mutex)
	//is done when the promo is added to cart by the user
	if(isset($_SESSION["promoArray"]) ){
			
		
		foreach($_SESSION["promoArray"] as $promoCode){
		
			//so first we get all ids of the different effects
			//associated with a given promoCode
			$sqlPromo = "SELECT *
						 FROM promos
						 WHERE promoCode LIKE '$promoCode'
						 AND valueThatGetsReduced LIKE 'cart total'";
			
			$resultPromo = mysql_query($sqlPromo, $con) or die(mysql_error());
			
			while( $rowPromo = mysql_fetch_array($resultPromo, MYSQL_ASSOC) ){
				
				//now the cartPrice is affected by each applicable effect 
				//related to the promoCode defined by $promoCode
				$adjustedCartPrice = getCartPromoEffect($con, $rowPromo, $originalCartPrice, $adjustedCartPrice);
			}
		}
		
	}//end if isset($_SESSION["promoArray"])
	
	return $adjustedCartPrice;
}

?>