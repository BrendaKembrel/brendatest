<?
	
	include("../include/connect.php");
	
	$itemType = $_POST["itemType"];
	
	$sql = "SELECT sizeDefault
			FROM itemType
			WHERE itemType LIKE '$itemType'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$sizeDefault = $row["sizeDefault"];
	
	$sql = "SELECT type
			FROM sizeDefault
			WHERE typeId = '$sizeDefault'";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
		
	echo $row["type"];


?>