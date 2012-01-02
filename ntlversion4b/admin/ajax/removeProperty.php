<?
	
	include("../include/connect.php");
	
	$propertyId = mysql_real_escape_string($_POST["propertyId"]);
	//we now delete any reference to the property defined by $propertyId
	//from the database
	//this means we have to cover the following tables
	//1) properties
	//2) propertiesCategory
	
	$sql = "DELETE FROM
			properties 
			WHERE id = '".$propertyId."'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "DELETE FROM
			propertiesCategory
			WHERE propertyId = '".$propertyId."'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());

?>