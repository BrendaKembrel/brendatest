<?
	
	include("../include/connect.php");
	
	$itemTypeId = mysql_real_escape_string($_POST["itemTypeId"]);
	
	$str = "";
	
	$sql = "SELECT *
			FROM subtype
			WHERE itemTypeId = '$itemTypeId'
			ORDER BY subtype ASC";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
	
		$subtypeId = $row["subtypeId"];
		$subtype = $row["subtype"];
		
		$str.="<div style='margin: 10px 5px 10px 5px;' id=".$subtype.$subtypeId.">
				<h2 style='font-size: 18px;'>".$subtype."</h2>
				<p><button type='button' onclick=\"removeSubtype('".$subtypeId."', '".$subtype."')\">Remove subtype $subtype</button></p>
				<div id='properties".$subtypeId."'>";
		
		//now that we have the subtypeId, 
		//we also want all properties associated with this subtypeId
		
		$sqlProp = "SELECT *
					FROM properties
					WHERE subtypeId = '$subtypeId'
					ORDER BY name ASC";
					
		$resultProp = mysql_query($sqlProp, $con) or die(mysql_error());
		
		$countProperties = 0;
		
		while( $rowProp = mysql_fetch_array($resultProp, MYSQL_ASSOC) ){
			
			$property = $rowProp["name"];
			$propertyId = $rowProp["id"];
			
			$str.="<div id='$propertyId' style='float: left; margin: 10px 4px 10px 4px;'>
					<span style='cursor: pointer; font-size: 14px; color: purple;' onclick=\"addPropertyToCateg('".$propertyId."' , '".$property."')\">
						".$property."
					</span>
					<button type='button' onclick=\"removeProperty('$propertyId', '$property')\">-</button>
					</div>";
			
			if($countProperties%4==3){
				
				$str.="<br style='clear:both' />";
			}
			
			$countProperties++;
		}
		
		$str.="</div>
				<br style='clear:both' />
				<input type='text' name='newProperty".$subtypeId."' />
				<button type='button' onclick=\"addNewProperty('".$subtypeId."')\">+</button>";
			
		
		$str.="</div>";
	}
	
	echo $str;

?>