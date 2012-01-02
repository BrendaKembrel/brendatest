<?
	include("../includes/functions/shoppingCartFunctions.php");
	
	include("../includes/connect.php");
	
	$productId = $_POST["productId"];
	$size = $_POST["size"];
	$defaultColor = $_POST["defaultColor"];
	
	//these are comma separated lists
	//that we want to turn into arrays
	$colors = $_POST["colors"];
	$swatches = $_POST["swatches"];
	$qties = $_POST["qties"];
	
	$purchasedColorsArray = explode(",", $colors);
	array_pop($purchasedColorsArray);
	
	$purchasedSwatchesArray = explode(",", $swatches);
	array_pop($purchasedSwatchesArray);
	
	$purchasedQtiesArray = explode(",", $qties);
	array_pop($purchasedQtiesArray);
	
	//first we prepare the header part
	$sql = "SELECT productName
				FROM product
				WHERE productId = '$productId'";
		
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$productName = stripslashes($row["productName"]);
	
	$str= "
	<p class='addToCartp'>
			You have added <span style='font-weight:bold;'> $productName </span> in size <span style='font-weight:bold;'> $size </span> to your bag.
		 </p>
		 <p style='float: left;margin: 5px 0 0 5%; width: 45%;text-align:left;font-size:14px;font-weight:bold;'>
			Color
		 </p>
		 <p style='float: left;margin: 5px 5% 0 0; width: 45%;text-align:right;font-size:14px;font-weight:bold;'>
			Qty
		 </p>
		 <br style='clear: both'>";

	
	//now we start adding to cart
	
	for($i=0; $i<count($purchasedColorsArray); $i++){
	
		$color = $purchasedColorsArray[$i];
		$quantity = $purchasedQtiesArray[$i];
		$swatch = $purchasedSwatchesArray[$i];
		
		$productKey = $productId."*".$defaultColor;
		
		if(!isset($_SESSION["productArray"])){
			
			$_SESSION["productArray"][$productKey][$color][$size] = $quantity;
		}
		else{
			
			if(!isset($_SESSION["productArray"][$productKey])){
				
				
				$_SESSION["productArray"][$productKey][$color][$size]=$quantity;
			}
			else{
				
				if(!isset($_SESSION["productArray"][$productKey][$color]) ){
				
				$_SESSION["productArray"][$productKey][$color][$size]=$quantity;
				
				}
				else{
					if(!isset($_SESSION["productArray"][$productKey][$color][$size])){
					
						$_SESSION["productArray"][$productKey][$color][$size]=$quantity;
						
					}
					else{
						$_SESSION["productArray"][$productKey][$color][$size]+=$quantity;
					}
				
				}
			
			}
		}
		
		$str.= "<div style='float: left;margin: 3px 0 0 5%; width: 45%;'>
					<p style='text-align:left;'><img src='".$swatch."' alt='".$color."' style='width:90px;height:20px;'></p>
				</div>
				<div style='float: left;margin: 10px 5% 0 0; width: 45%;'>
					<p style='text-align:right;font-size:14px;'> ".$quantity." </p>
				</div>
			  <br style='clear: both'>";
		
	}
	
	//now, we finalize the cart
	$currency = "";
	$promoArray = array();
	
	if(isset($_SESSION["currency"]) ){
		
		$currency = $_SESSION["currency"];
	}
	
	if($currency==""){
	
		$currency = "CAD";
	}
	
	if(isset($_SESSION["promoArray"])){
		
		$promoArray = $_SESSION["promoArray"];
		
	}
	
	$totalNumberOfItems = getTotalNumberItemsInCart();
	$totalValue = getTotalCostOfItemsInCart($con, $currency, $promoArray);
	
	$str.="
		<p class='addToCartp'>
			Your cart contains ".$totalNumberOfItems." items. <br/>
			Total Cost: $".$totalValue." $currency<br/>
			View and Modify Shopping Bag
		</p>
	";
	
	echo $str;
	
?>