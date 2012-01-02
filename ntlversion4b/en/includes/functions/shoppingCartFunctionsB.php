<?
	function displayShoppingBag($con){
		
		
		//first we get our array of promotions
		$promoArray = array();
		
		if(isset($_SESSION['promoArray'])){
		
			$promoArray = $_SESSION['promoArray'];
		}
		
		if(isset($_SESSION["currency"]) ){
		
			$currency = $_SESSION["currency"];
		}
		else{
			
			$currency = "CAD";
		}
		
		$counter=0;
		
		$str = "<div class='shoppingCartrowDiv' id='titleDiv'>
					<div class='shoppingCartimgDiv'>
						
					</div>
					<div class='shoppingCartproductNameDiv'>
						<p style='font-weight:bold;text-align:center;margin:5px 3px 5px 3px;'>Product Name</p>
					</div>
					<div class='shoppingCartsizeDiv'>
						<p style='font-weight:bold;text-align:center;margin:5px 3px 5px 3px;'>Size</p>
					</div>
					<div class='shoppingCartcolorDiv'>
						<p style='font-weight:bold;text-align:center;margin:5px 3px 5px 3px;'>Color</p>
					</div>
					<div class='shoppingCartpriceDiv'>
						<p style='font-weight:bold;text-align:center;margin:5px 3px 5px 3px;'>Price</p>
					</div>
					<div class='shoppingCartqtyDiv'>
						<p style='font-weight:bold;text-align:center;margin:5px 3px 5px 3px;'>Qty</p>
					</div>
					<div class='shoppingCarttotalPriceDiv'>
						<p style='font-weight:bold;text-align:center;margin:5px 3px 5px 3px;'>Total Price</p>
					</div>
					<div class='shoppingCartremoveDiv'>
						
					</div>
					<br style='clear:both;'>
				</div>
				";
				
		$counterForPaypal = 1;
		$paypalStr = "";
		
		if(strcasecmp($currency, "CAD")==0){
			$counterForPaypal = 2;
		}
		
		
		if(isset($_SESSION['productArray'])&&!empty($_SESSION["productArray"])){
			
			foreach($_SESSION['productArray'] as $productKey => $colorArray){
				
				//$productKey = $productId."*".$defaultColor
				
				$prodStuff = explode("*", $productKey);
				
				$productId = $prodStuff[0];
				$defaultColor = $prodStuff[1];
				
				foreach($colorArray as $color=> $sizeArray){
				
					foreach($sizeArray as $size=>$qty){
					
						$counter++;
					
						//here we get the item's product name and price
						//to do so, we make a new product
						//and feed it the current promoArray
					
						$product = new Product($productId, $defaultColor);
						
						$productName = $product->getProductName();
						
						$imageRegular = $product->getImageWithPath($defaultColor);
						
						$itemNumber = $product->getItemNumber();
						
						//this is the price we will pass to Paypal
						//note that it will be refreshed in refreshPrices()
						//in the event that a person adds a promo or something
						$priceToUse = $product->getPrice()->getPriceToPay();
						
						$arrayOfFormatting = array("style='font-family:Book Antiqua,serif;font-size:12px;color:black;'",
												   "style='font-family:Book Antiqua,serif;font-size:12px;color:#692260;'",
												   "style='font-family:Book Antiqua,serif;font-size:12px;color:red;'",
												   "");
						
						$priceString = $product->getPrice()->getFormattedPrice($arrayOfFormatting, "", false, true, false);
						
						$regPrice = $product->getPrice()->getRegPriceGivenCurrency();
						$finalPrice = $product->getPrice()->getFinalPriceGivenCurrency(); //this = regPrice if product not discounted
						$promoPrice = $priceToUse; //this != finalPrice if a promo is involved
												
						if($promoPrice!=$finalPrice&&$promoPrice!=""&&$promoPrice!="0.00"&&$promoPrice!="0"){
							
							$totalPriceString = "<span style='font-family:Book Antiqua,serif;font-size:12px;text-decoration:line-through;'>$".number_format($regPrice*$qty, 2)." ".$currency."</span><br/>".
												"<span style='font-family:Book Antiqua,serif;font-size:12px;color:red'>$".number_format($promoPrice*$qty, 2)." ".$currency."</span>";
							
						}
						elseif($regPrice!=$finalPrice){
						
							$totalPriceString = "<span style='font-family:Book Antiqua,serif;font-size:12px;text-decoration:line-through;'>$".number_format($regPrice*$qty, 2)." ".$currency."</span><br/>".
												"<span style='font-family:Book Antiqua,serif;font-size:12px;color:red'>$".number_format($finalPrice*$qty, 2)." ".$currency."</span>";
						}
						else{
						
							$totalPriceString = "<span style='font-family:Book Antiqua,serif;font-size:12px;'>$".number_format($finalPrice*$qty, 2)." ".$currency."</span>";
							
						}
					
						
						
						$sel = "<select id='".$counter."' name='".$counter."' onchange=\"updateQty('$counter', '$productId', '$defaultColor', '$size', '$color')\">";
						for($i=0; $i<=30; $i++){
							
							$selected = "";
							
							if($i==$qty){
								$selected = "selected";
							}
							
							$sel.="<option value='$i' $selected>$i</option>";
							
						}
						//qtyitemNumber".$itemNumber."size".$size."color".$color."
						$sel.="</select>";
						
						//<input type='text' id='qtyitemNumber".$itemNumber."size".$size."color".$color."' name='qtyitemNumber".$itemNumber."size".$size."color".$color."' value='$qty' style='width: 30px'><br/>
						$str .= "<div class='shoppingCartrowDiv'>
									<div class='shoppingCartimgDiv'>
										<img src='$imageRegular' width='85px'>
									</div>
									<div class='shoppingCartproductNameDiv'>
										<p style='margin:5px 2px 5px 2px;'>$productName</p>
									</div>
									<div class='shoppingCartsizeDiv'>
										<p style='margin:5px 2px 5px 2px;'>$size</p>
									</div>
									<div class='shoppingCartcolorDiv'>
										<p style='margin:5px 2px 5px 2px;'>$color</p>
									</div>
									<div class='shoppingCartpriceDiv'>
										<p style='margin:5px 2px 5px 2px;'>".$priceString."</p>
									</div>
									<div class='shoppingCartqtyDiv'>
										<p style='margin:5px 2px 5px 2px;'>$sel</p>
										
									</div>
									<div class='shoppingCarttotalPriceDiv'>
										<p style='margin:5px 2px 5px 2px;'>$totalPriceString</p>
									</div>
									<div class='shoppingCartremoveDiv'>
										<img style='cursor: pointer;' src='images/remove.jpg' onclick=\"remove('$counter', '".$productId."', '".$defaultColor."', '$color', '$size')\">
									</div>
									<br style='clear:both;'>
								</div>";
								
						 $paypalStr.= 
							"<input type='hidden' name='quantity_" .$counterForPaypal. "' value='$qty'>
							<input type='hidden' name='on0_" .$counterForPaypal. "' value='Color'>
							<input type='hidden' name='os0_" .$counterForPaypal. "' value='$color'>
							<input type='hidden' name='on1_" .$counterForPaypal. "' value='Size'>
							<input type='hidden' name='os1_" .$counterForPaypal. "' value='$size'>
							<input type='hidden' name='item_number_" .$counterForPaypal. "' value='$itemNumber'>
							<input type='hidden' name='amount_" .$counterForPaypal. "' value='$priceToUse'>
							<input type='hidden' name='item_name_"  .$counterForPaypal. "' value='$productName'>
							
							";  
						
						$counterForPaypal++;
					}
				
				}
			
			}
			
			//we store the product details in the paypal string that we will make use of in ajax/refreshPrices.php
			$_SESSION["paypalStr"] = $paypalStr;
			
			echo $str;
		}//end if isset
		else{
			
			$_SESSION["paypalStr"] = "";
			echo "<div style='width:100%;text-align:center;margin:15px 0 15px 0;'><p style='font-size:20px;font-family:verdana;font-weight:bold;color:red;'>Cart is empty.</p>";
		
		}
		
	}
	
	
	
?>