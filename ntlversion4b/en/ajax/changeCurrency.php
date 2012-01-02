<?
	session_start();
	
	$currency = $_POST["currency"];
	
	$_SESSION["currency"] = $currency;
	
	echo "";
?>