<?
	
	include("../include/connect.php");
	$categoryId = mysql_real_escape_string($_POST["categoryId"]);
	
	$str="";
	
	$sql = "SELECT propertyId
			FROM propertiesCategory
			WHERE categoryId = '$categoryId'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
		
		$propertyId = $row["propertyId"];
		
		$sqlProp = "SELECT name
					FROM properties
					WHERE id = '$propertyId'";
		$resultProp = mysql_query($sqlProp, $con) or die(mysql_error());
		
		$rowProp = mysql_fetch_array($resultProp, MYSQL_ASSOC);
		
		$property = $rowProp["name"];
		
		$str.="<div id='propertyInCateg".$propertyId."' style='float: left; margin: 10px 4px 10px 4px; font-size: 14px; color: purple;' >"
					.$property.
				"<button type='button' onclick=\"removePropertyFromCateg('".$propertyId."')\">-</button>
				</div>";
	
	}
	
	echo $str;
	
?>