<?
	
	include("../includes/functions/shoppingCartFunctions.php");
	session_start();
	
	$numItemsInCart = getTotalNumberItemsInCart();
	
	if($numItemsInCart==0){
	
	}
	else{
		echo "You have $numItemsInCart items in your cart.";
	}
?>