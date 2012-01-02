<?
	include("../classes/utilityClass.php");
	include("../classes/fabricClass.php");
	include("../classes/colorClass.php");
	include("../classes/priceClass.php");
	include("../classes/promoClass.php");
	include("../classes/productClass.php");

	session_start();
	
	function getTotalNumberItemsInCart(){
		
		$total = 0;
		
		if(isset($_SESSION["productArray"])){
			
			foreach($_SESSION["productArray"] as $productKey=>$colorArray){
				
				foreach($colorArray as $color=>$sizeArray){
					
					foreach($sizeArray as $size=>$quantity){
						
						$total+=$quantity;
					
					}
				
				}
			
			}
			
		}
		
		return $total;
	}
	
	function getTotalCostOfItemsInCart($con, $currency, $promoArray){
		
		
		$total = 0;
		
		//this all depends on the current promos
		//but worry about that later
		if(isset($_SESSION["productArray"])){
			
			foreach($_SESSION["productArray"] as $productKey=>$colorArray){
				
				//$productKey = $productId."*".$defaultColor
				
				$prodStuff = explode("*", $productKey);
				
				$productId = $prodStuff[0];
				$defaultColor = $prodStuff[1];
				
				//we create a new Product
				$prod = new  Product($productId, $defaultColor);
				
				//we get its final price
				//which will be a promo price if a promo applies
				//otherwise it will be the usual finalPrice
				
				$priceToUse = $prod->getPrice()->getPriceToPay();
				
				foreach($colorArray as $color=>$sizeArray){
					
					foreach($sizeArray as $size=>$quantity){
						
						$total+=$quantity*$priceToUse;
					}
				
				}
			
			}
			
			
		}//end if isset
		
		return number_format($total, 2);
	}

?>