<?
	session_start();
	
	$promo = $_POST["promo"];
	
	array_push($_SESSION['promoArray'] , $promo);
	echo "<span style='color:green;font-size: 12px;'>Promo $promo added!</span>";
	
?>