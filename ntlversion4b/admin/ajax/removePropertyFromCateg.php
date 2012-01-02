<?
	
	include("../include/connect.php");
	
	$propertyId = mysql_real_escape_string($_POST["propertyId"]);
	$categoryId = mysql_real_escape_string($_POST["categoryId"]);
	
	//so now we remove the appropriate row from the propertiesCategory table
	
	$sql = "DELETE FROM
			propertiesCategory
			WHERE propertyId = '".$propertyId."'
			AND categoryId = '".$categoryId."'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	//echo "";
?>