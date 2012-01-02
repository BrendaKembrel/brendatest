<?
	
	include("../classes/utilityClass.php");
	include("../classes/productClass.php");
	include("../classes/colorClass.php");
	include("../classes/fabricClass.php");
	include("../classes/promoClass.php");
	include("../classes/priceClass.php");
	include("../includes/connect.php");
	
	include("../includes/functions/shoppingCartFunctionsB.php");

	session_start();
	
	$taxRate = $_POST["taxes"];
	
	
	
	$currency = "CAD";
	
	if(isset($_SESSION['currency']) ){
		
		$currency = $_SESSION["currency"];
	}
	
	
	$str= "";
	
	//first we want to get the shipping info
	$sqlShipping = "SELECT shipping
				FROM shipping";
					
	$resultShipping = mysql_query($sqlShipping, $con) or die(mysql_error());
	
	$rowShipping = mysql_fetch_array($resultShipping, MYSQL_ASSOC);
	
	$shipping = $rowShipping[shipping];
	
	//other important pricing information will be
	$savingsOnIndividualProducts = 0; //savings product by product
	$savings = 0; //savings in terms of shopping cart promo
	$totalPriceWithoutShippingSavingsTaxes = 0;
	
	//we want to get the price of everything put together, minus all the extras
	
	if(isset($_SESSION['productArray'])&&!empty($_SESSION["productArray"])){
			
		foreach($_SESSION['productArray'] as $productKey => $colorArray){
			
			//$productKey = $productId."*".$defaultColor
				
			$prodStuff = explode("*", $productKey);
			
			$productId = $prodStuff[0];
			$defaultColor = $prodStuff[1];
				
			foreach($colorArray as $color=> $sizeArray){
			
				foreach($sizeArray as $size=>$qty){
					
					
					$product = new Product($productId, $defaultColor);
					
					//this is the price we will pass to Paypal
					//in the event that a person adds a promo or something
					$priceToUse = $product->getPrice()->getPriceToPay();
					
					$regPrice = $product->getPrice()->getRegPriceGivenCurrency();
					
					$finalPrice = $product->getPrice()->getFinalPriceGivenCurrency();
					
					//the promoPrice here applies only for the kind of promo that works
					//on individual items as opposed to the cart as a whole
					$promoPrice = $priceToUse; //if $promoPrice==$finalPrice then there's no applicable promo
								
					if($promoPrice!=$finalPrice&&$promoPrice!=""&&$promoPrice!="0.00"&&$promoPrice!="0"){
						
						$savingsOnIndividualProducts += ($regPrice - $promoPrice);
					}
					else{
						$savingsOnIndividualProducts += ($regPrice - $finalPrice); //might be 0 if finalPrice = regPrice
					}
					
					$totalPriceWithoutShippingSavingsTaxes += number_format($priceToUse*$qty, 2);
				}
			
			}
		
		}
	}

	
	//$customPromos is used to pass a custom variable to paypal..in our case, promos
	//in the form of a comma separate list
	$customPromos="";
	
	if(isset($_SESSION['promoArray'])){
		
		foreach($_SESSION['promoArray'] as $promoC){
		
			if($promoC!=""){
			
				$promo = $promoC;
				$customPromos.=$promoC.",";
				
			}
		}
	
	}
	
	//NOW IS WHEN WE calculate savings based on the cartTotal 
	//we want to calculate what the price is without shipping and taxes, but with savings
	
	$adjustedCartPrice = $totalPriceWithoutShippingSavingsTaxes;
	
	if(isset($_SESSION['promoArray'])){
		
		foreach($_SESSION['promoArray'] as $promo){
		
			if($promo!=""){
				
				$promoI = new Promo($promo);
				$adjustedCartPrice = $promoI->getPriceCart($totalPriceWithoutShippingSavingsTaxes, $adjustedCartPrice, $currency);
				
			}
		}
	
	}
	
	
	
	$totalPriceWithoutShippingTaxes = $adjustedCartPrice;
	
	
	//the savings are the (original price without shipping, savings, taxes) - (the price with savings but no shipping, taxes)
	$savings = number_format($totalPriceWithoutShippingSavingsTaxes-$totalPriceWithoutShippingTaxes, 2);
	
	$totalWithEverythingButTaxes = $totalPriceWithoutShippingSavingsTaxes - $savings + $shipping;
	
	$taxRate = (real)$taxRate/100.00;
	
	$taxes = round($totalWithEverythingButTaxes * $taxRate, 2);
	
	$taxes = number_format($taxes, 2);
	
	if($taxes==0&&$savings==0){
	
		$grandTotal = number_format($totalPriceWithoutShippingSavingsTaxes + $shipping, 2);
		
		$str.="Total without Shipping: $".number_format($totalPriceWithoutShippingSavingsTaxes, 2)." $currency<br/>
			   Shipping: $$shipping $currency<br/><br/>
			   Grand Total: $$grandTotal $currency ";
	}
	elseif($taxes!=0&&$savings==0){
		//only taxes are applied
		$grandTotal = number_format($totalPriceWithoutShippingSavingsTaxes + $shipping + $taxes, 2);
		
		$taxes = number_format($taxes, 2);
		
		$str.="Total without Shipping and Taxes: $".number_format($totalPriceWithoutShippingSavingsTaxes , 2)." $currency <br/>
			   <span style='color: blue'>Estimated Taxes: $$taxes $currency </span><br/>
			   Shipping: $$shipping $currency <br/><br/>
			   Grand Total: $$grandTotal $currency ";
	
	}
	elseif($taxes==0&&$savings!=0){
		//only savings are applied
		
		$grandTotal = number_format($totalPriceWithoutShippingSavingsTaxes + $shipping - $savings, 2);
		
		$savings = number_format($savings, 2);
		
		$str.="Total without Shipping and Savings: $".number_format($totalPriceWithoutShippingSavingsTaxes, 2)." $currency<br/>
			   <span style='color: red'>You save: $$savings $currency</span><br/>
			   Shipping: $$shipping $currency<br/><br/>
			   Grand Total: $$grandTotal $currency";
		
	}
	else{
		//both taxes and savings are applied
		
		$grandTotal = number_format($totalPriceWithoutShippingSavingsTaxes + $shipping + $taxes - $savings, 2);
		
		$taxes = number_format($taxes, 2);
		
		$savings = number_format($savings, 2);
		
		$str.="Total without Shipping, Taxes and Savings: $".number_format($totalPriceWithoutShippingSavingsTaxes, 2)." $currency<br/>
			   <span style='color: red'>You save: $$savings $currency</span><br/>
			   <span style='color: blue'>Estimated Taxes: $$taxes $currency</span><br/>
			   Shipping: $$currency $shipping<br/><br/>
			   Grand Total: $$currency $grandTotal";
	
	}
	
	
	//the rest is the Paypal stuff
	if(strcasecmp($currency, "CAD")==0){
					
		$str.= "
		<input type='hidden' name='cmd' value='_cart'>
		<input type='hidden' name='upload' value='1'>
		<input type='hidden' name='business' value='celine@nowthatslingerie.com'>
		<input type='hidden' name='lc' value='CA'>
		<input type='hidden' name='currency_code' value='CAD'>
		<input type='hidden' name='no_shipping' value='2'>
		<input type='hidden' name='item_number_1" ."' value='paypal_shipping'>
		<input type='hidden' name='quantity_1'". "' value='1'>
		<input type='hidden' name='amount_1'". "' value='$shipping'>
		<input type='hidden' name='item_name_1". "' value='shipping'>
		<input type='hidden' name='bn' value='PP-ShopCartBF' />
		<input type='hidden' name='custom' value='$customPromos' />
		<input type='hidden' name='return' value='http://www.nowthatslingerie.com/en/thank_you_paypal2.php'>
		<input type='hidden'  name='submit3'>";
	}
	else{
	
		//now comes the paypal part
		$str.= "
		<input type='hidden' name='cmd' value='_cart'>
		<input type='hidden' name='upload' value='1'>
		<input type='hidden' name='business' value='celine@nowthatslingerie.com'>
		<input type='hidden' name='lc' value='CA'>
		<input type='hidden' name='currency_code' value='USD'>
		<input type='hidden' name='no_shipping' value='2'>
		<input type='hidden' name='bn' value='PP-ShopCartBF' />
		<input type='hidden' name='custom' value='$customPromos' />
		<input type='hidden' name='return' value='http://www.nowthatslingerie.com/en/thank_you_paypal2.php'>
		<input type='hidden'  name='submit3'>";
	}
	
	if(isset($_SESSION["paypalStr"] ) ){
	
		$str.=$_SESSION["paypalStr"];
	}
	
	if(isset($savings)&&$savings!=0){
	
		$str.="
		   <input type='hidden' name='discount_amount_cart' value='$savings'>
			";  
	
	}
					
	
	
	echo $str;
?>