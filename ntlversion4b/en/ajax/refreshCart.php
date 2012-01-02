<?
	
	include("../classes/utilityClass.php");
	include("../classes/productClass.php");
	include("../classes/colorClass.php");
	include("../classes/fabricClass.php");
	include("../classes/promoClass.php");
	include("../classes/priceClass.php");
	
	
	include("../includes/functions/shoppingCartFunctionsB.php");
	include("../includes/connect.php");
	
	session_start();
	
	$currency = "CAD";
	
	if(isset($_SESSION['currency'])){
		
		$currency = $_SESSION['currency'];
	}
	
	displayShoppingBag($con, $currency);
	
?>