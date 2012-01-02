<?
	
	include("../include/connect.php");
	
	$subtypeId = mysql_real_escape_string($_POST["subtypeId"]);
	$property = mysql_real_escape_string($_POST["property"]);
	
	
	//first we check for the existence of this property
	$sql = "SELECT *
			FROM properties
			WHERE name LIKE '$property'
			AND subtypeId = '$subtypeId'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(!empty($row)){
		
		echo "<p id='errorMsg' style='color: red; font-size:14px;'>The property ".$property." already exists for the current subtype.<br style='clear: both' /></p>";
	
	}
	elseif($property==""){
		
		//we don't want any empty subtypes
		echo "<p id='errorMsg' style='color: red; font-size:14px;'>You can't add an empty property.<br style='clear: both' /></p>";
	}
	else{
	
		$sql = "SELECT max(id) as max
				FROM properties";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$propertyId = $row["max"] + 1;
		
		//now we insert
		
		$sql = "INSERT INTO properties
				(subtypeId, id, name)
				VALUES
				('$subtypeId', '$propertyId', '$property')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
				
		echo "		<div id='$propertyId' style='float: left; margin: 10px 4px 10px 4px;'>
					<span style='cursor: pointer; font-size: 14px; color: purple;' onclick=\"addPropertyToCateg('".$propertyId."' , '".$property."')\">
						".$property."
					</span>
					<button type='button' onclick=\"removeProperty('$propertyId', '$property')\">-</button>
					</div>";
	
	}
?>