<?
	session_start();
	
	$promo = $_POST["promo"];
	
	if(isset($_SESSION['promoArray'] ) ){
	
		if(in_array($promo, $_SESSION['promoArray']) ){
			
			
			$index = array_search (  $promo , $_SESSION['promoArray'] );
			
			$_SESSION['promoArray'][$index] = "";
			
		}
	
	}
	
	echo "";

?>