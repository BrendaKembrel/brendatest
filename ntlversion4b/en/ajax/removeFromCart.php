<?
	session_start();
	
	$productId = $_POST["productId"];
	$color = $_POST["color"];
	$size = $_POST["size"];
	$defaultColor = $_POST["defaultColor"];
	
	$productKey = $productId."*".$defaultColor;
	
	unset($_SESSION["productArray"][$productKey][$color][$size] );
	
	//we need to check if the productId is empty at this point
	//at which point we unset the productId from the cart
			
	if( empty ( $_SESSION["productArray"][$productKey][$color] ) ){
				
		unset($_SESSION["productArray"][$productKey][$color]);
					
	}
	
	if( empty ($_SESSION["productArray"][$productKey]) ){
				
		unset($_SESSION["productArray"][$productKey]);
					
	}
	
	echo "";

?>