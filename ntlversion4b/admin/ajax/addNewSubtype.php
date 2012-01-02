<?php
	
	include("../include/connect.php");
	
	$itemTypeId = mysql_real_escape_string( $_POST["itemTypeId"] );
	$subtype = mysql_real_escape_string( trim( $_POST["subtype"] ) );
	
	//first we check for the existence of the subtype.
	//if it exists, we won't add the new one to the db
	//but instead display a message to the user
	$sql = "SELECT *
			FROM subtype
			WHERE subtype LIKE '$subtype'
			AND itemTypeId = '$itemTypeId'";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(!empty($row)){
		
		echo "<p id='errorMsg' style='color: red; font-size:14px;'>The subtype ".$subtype." already exists for the current itemType.<br style='clear: both' /></p>";
	
	}
	elseif($subtype==""){
		
		//we don't want any empty subtypes
		echo "<p id='errorMsg' style='color: red; font-size:14px;'>You can't add an empty subtype.<br style='clear: both' /></p>";
	}
	else{
		
		//we add the new subtype
		//note that it clearly has no properties yet
		
		$sql = "SELECT max(subtypeId) as max
				FROM subtype";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"] + 1;
		
		//now we insert
		
		$sql = "INSERT INTO subtype
				(subtypeId, subtype, itemTypeId)
				VALUES
				('$subtypeId', '$subtype', '$itemTypeId')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		echo "<div style='margin: 10px 5px 10px 5px;' id=".$subtype.$subtypeId.">
				<h2 style='font-size: 18px;'>".$subtype."</h2>
				<p><button type='button' onclick=\"removeSubtype('".$subtypeId."', '".$subtype."')\">Remove subtype $subtype</button></p>
				<div id='properties".$subtypeId."'>
				</div>
				<br style='clear:both' />
				<input type='text' name='newProperty".$subtypeId."' />
				<button type='button' onclick=\"addNewProperty('".$subtypeId."')\">+</button>
			</div>";
	}
?>