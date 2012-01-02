<?
	//note: promo info is stored in 2 tables
	//the promos table includes info about the promo such as
	//expiry date and percentage off.
	//note that a number of rows can all have the same promo code associated with them
	//this is because a promo code may have 10% off when certain conditions are met,
	//15% when other conditions are met, etc.  Meanwhile, every row has a unique id.
	//the promoSpecifics table, meanwhile, deals with outlining the conditions referred to above
	//the column called id refers to the column called id in the promos table, so that you know with which row
	//the condition applies
	
	//here's an example
	//promoCode abcd id 8
	//promoCode abcd id 9
	//in promoSpecifics
	//several rows with id = 8 //THESE rows ARE ANDED together
	//several rows with id = 9 //THESE rows ARE ANDED together
	//if both 8 and 9 are met, then the effect defined by 8 occurs
	//as well as the effect defined by 9
	class Promo{
	
		//the way we want to set this up is as follows
		//an object is defined by a single promo code
		//meanwhile, it will have an array of values
		//defined by the different rows in the promos table
		//that refer to it
		//(it won't have arrays for common values such as expiry date)
		
		//the common vars
		private $promoCode;
		private $valueThatGetsReduced; //cart total or product price
		private $type; //requires promo, sitewide with no promo, single use with promo
		//mutex=1 means promo only works on its own; mutex=0 means promo can be combined.
		//this is because a sitewide promo is more like a special
		//Please note that all sitewide promos combine with any promotion, whether that promotion is "combinable" or not
		private $mutex; 
		private $expiryDate;
		private $createdDate;
		private $releaseDate;
		
		//the array vars
		private $arrayOfRates = array(); //a number
		private $arrayOfDenominations = array();  //fixed or percentage
		private $arrayOfIds = array(); //the unique id of the row
		
		public function __construct($promoCode){
			
			$this->promoCode = $promoCode;
			
			//we get the sql connection from the Utility class
			$con = Utility::getCon();
			
			$sql = "SELECT *
					FROM promos
					WHERE promoCode LIKE '$promoCode'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				$this->valueThatGetsReduced = $row["valueThatGetsReduced"];
				$this->type = $row["type"];
				$this->mutex = $row["mutex"];
				$this->expiryDate = $row["expiryDate"];
				$this->createdDate = $row["createdDate"];
				$this->releaseDate = $row["releaseDate"];
				
				array_push($this->arrayOfRates, $row["rate"]);
				array_push($this->arrayOfDenominations, $row["denomination"]);
				array_push($this->arrayOfIds, $row["id"]);
			}
			
		
		}
		
		//FUNCTIONS FOR PROMOS THAT AFFECT THE CART TOTAL
		
		//the following function takes in:
		//$originalCartPrice: the unreduced value (cart total) of the cart
		//the $adjustedCartPrice (the cart price may have been reduced by other promos)
		//as well as the currency and returns the price
		//which will be reduced if the promo applies
		//note: $value is used to see if conditions are met and $currentValue is used to calculate
		//since it's actually the current state the price is in
		//it gets the price of the cart after going through the current promo 
		//the cart may or may not be affected by the promo of course
		public function getPriceCart($originalCartPrice, $adjustedCartPrice, $currency){
			
			if( strcasecmp($this->valueThatGetsReduced, "cart total")!=0 || $originalCartPrice==0 || $adjustedCartPrice == 0){
				
				//we're not dealing with a cart promo, so clearly
				//automatically, the cart price won't be affected
				return number_format($adjustedCartPrice, 2);
			}
			
			//we get the sql connection from the Utility class
			$con = Utility::getCon();
			
			$originalCartPrice = (real) $originalCartPrice;
			$adjustedCartPrice = (real) $adjustedCartPrice;
			
			//we loop through all i
			for($i=0; $i<count($this->arrayOfRates); $i++){
			
				$rate = (real) $this->arrayOfRates[$i];
				$denomination = $this->arrayOfDenominations[$i];
				$id = $this->arrayOfIds[$i];
				
				//we get the id 
				$sqlOps = "SELECT *
						   FROM promoSpecifics
						   WHERE id = '$id'";
						   
				$resultOps = mysql_query($sqlOps, $con) or die(mysql_error());
				
				//we loop through all of the conditions
				//if a single condition doesn't apply to the original, then we immediately break out of the loop
				//keeping the adjusted cart price in its current state, without further adjustment
				//and instead move onto the next id for the promoCode (and thus the next set of conditions)
				//if we get through the while loop, then we know that all conditions applied associated
				//with this id
				//and therefore we adjust the cartPrice by rate and denomination as appropriate
				while($rowOps = mysql_fetch_array($resultOps, MYSQL_ASSOC) ){
					
					$thing = $rowOps["thing"];
					$operator = $rowOps["operator"];
					$value = (real) $rowOps["value"];
					
					
					if(strcasecmp($thing, "currency")==0){
						
						if(strcasecmp($currency, $value)!=0){
							
							//clearly we are not affected by the effect
							break;
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
								break;
							}
						}
						elseif(strcasecmp(">=", $operator)==0){
							
							if($originalCartPrice>=$value){
								//we're ok
							}
							else{
								break;
							}
						}
						elseif(strcasecmp(">", $operator)==0){
							
							if($originalCartPrice>$value){
								//we're ok
							}
							else{
								break;
							}
						}
						elseif(strcasecmp("<=", $operator)==0){
						
							if($originalCartPrice<=$value){
								//we're ok
							}
							else{
								break;
							}
						
						}
						elseif(strcasecmp("<", $operator)==0){
							
							if($originalCartPrice<$value){
								//we're ok
							}
							else{
								break;
							}
						}
						else{
							//it's a != case
							if($originalCartPrice!=$value){
								//we're ok
							}
							else{
								break;
							}
						}
						
					}//end elseif the thing is the cartTotal
					
					//now, if we get here, we know we have to adjust the cart price
				
					if(strcasecmp($denomination, "percentage")==0){
						
						$adjustedCartPrice = $adjustedCartPrice* (1.0-($rate/100));
						
					}
					else{
						
						$adjustedCartPrice -= $rate;
					}
					
				}//end while
				
				
				
			}//now we loop through other ids to see if there is further adjustment
			
			return number_format($adjustedCartPrice, 2);
		
		}
		
		//FUNCTIONS FOR PROMOS THAT AFFECT THE PRODUCT PRICE
		
		//this function takes in the currentValue of the product 
		//(ie: the potentially reduced value of the product due to other promos)
		//the currency 
		//and the product object
		//it then returns the reduced value of the product, if all conditions
		//defined in promoSpecifics are met for the current promo ($this)
		public function getPriceProduct($currentValue, $currency, $product){
		
			if( strcasecmp($this->valueThatGetsReduced, "product price")!=0){
				
				//we're not dealing with a cart promo, so clearly
				//automatically, the cart price won't be affected
				return number_format($currentValue, 2);
			}

			
			$con = Utility::getCon();
			
			//THEY ARE ALL PRODUCT=NULL!!!
			if($product==null){
				
				return $currentValue;
			}
			
			$productId = $product->getProductId();
			
			for($i=0; $i<count($this->arrayOfRates); $i++){
			
				$rate = $this->arrayOfRates[$i];
				$denomination = $this->arrayOfDenominations[$i];
				$id = $this->arrayOfIds[$i];
				
				//this is the sqlString we will build up
				//to figure out if our product is affected by the effect with id $id
				//in other words, at the end, we will make a sql call with this sqlString
				//if it meets the conditions that were built up, a row will be returned
				//otherwise no rows will be returned
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
						if(!$product->IsNew()){
							
							break;
						}
						//if yes, then we can continue going through the loop
						//because we know that the finalPrice still has a chance  
						
					}
					elseif(strcasecmp($thing, "currency")==0){
						
						if(strcasecmp($currency, $value)!=0){
							
							//clearly we are not affected by the effect
							break;
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
						
						$currentValue = round(  ( 1.00-( (real)($rate) / 100.00 ) )*$currentValue, 2);
						
					}
					else{
						
						//we're dealing with a fixed amount
						$currentValue = round( ($currentValue - $rate) , 2);
					}
				
				}
			}
			
			return $currentValue;
		}
		
		//GETTERS
		
		public function getPromoCode(){
			
			return $this->promoCode;
		}
		
		public function getValueThatGetsReduced(){
		
			return $this->valueThatGetsReduced;
		}
		
		public function getType(){
			return $this->type;
		}
		
		public function getMutex(){
		
			return $this->mutex;
		}
		
		public function getExpiryDate(){
		
			return $this->expiryDate;
		}
		
		public function getCreatedDate(){
		
			return $this->createdDate;
		}
		
		public function getReleaseDate(){
		
			return $this->releaseDate;
		}
		public function getArrayOfRates(){
		
			return $this->arrayOfRates;
		}
		
		public function getArrayOfDenominations(){
		
			return $this->arrayOfDenominations;
		}
		
		public function getArrayOfIds(){
		
			return $this->arrayOfIds;
		}
	
	}
	
?>