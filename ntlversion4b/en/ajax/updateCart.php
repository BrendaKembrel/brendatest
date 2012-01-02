<?
	session_start();
	
	$defaultColor = $_POST["defaultColor"];
	$productId = $_POST["productId"];
	$color = $_POST['color'];
	$size = $_POST["size"];
	$qty = $_POST["qty"];
	
	$productKey = $productId."*".$defaultColor;
	
	if(!is_numeric($qty)){
		
		$qty = 1;
	}
	
	if($qty==0){
		
		unset($_SESSION["productArray"][$productKey][$color][$size] );
	
		//we need to check if the productId is empty at this point
		//at which point we unset the productId from the cart
				
		if( empty ( $_SESSION["productArray"][$productKey][$color] ) ){
					
			unset($_SESSION["productArray"][$productKey][$color]);
						
		}
		
		if( empty ($_SESSION["productArray"][$productKey]) ){
					
			unset($_SESSION["productArray"][$productKey]);
						
		}
		
	}
	else{
	
		$_SESSION["productArray"][$productKey][$color][$size] = $qty;
	}
	
	echo "";

?>