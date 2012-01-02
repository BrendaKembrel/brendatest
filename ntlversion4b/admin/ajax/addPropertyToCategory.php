<?
	
	include("../include/connect.php");
	
	$categoryId = mysql_real_escape_string($_POST["categoryId"]);
	
	$propertyId = mysql_real_escape_string($_POST["propertyId"]);
	
	$property = mysql_real_escape_string($_POST["property"]);
	
	
	//before we can add a propertyId, categoryId pair to the propertiesCategory table,
	//we need to ensure that the category with categoryId $categoryId exists 
	//in the category table.  If $categoryId=-1, we'll have to create a new row in the category table
	//first, with categoryName = temp
	if($categoryId == -1){
		
		$sqlMax = "	SELECT max(categoryId) as max
					FROM category";
					
		$resultMax = mysql_query($sqlMax, $con) or die(mysql_error());
		
		$rowMax = mysql_fetch_array($resultMax, MYSQL_ASSOC);
		
		$categoryId = $rowMax["max"]+1;
		
		$sql = "INSERT INTO category(categoryId, categoryName)
				VALUE ('$categoryId', 'temp')";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		///we need to set our js variable you'll find in ../subtypePropertiesCategory.php
		//to the new updated value
		echo "<script type='text/javascript'>
				categoryId = $categoryId;
			  </script>";
	
	}
	
	//now that that's over, we can add a row to the propertiesCategory table
	
	//first we ensure such a row doesn't already exist
	//because we don't want to resinsert the same property twice
	$sql = "SELECT id
			FROM propertiesCategory
			WHERE categoryId = '$categoryId'
			AND propertyId = '$propertyId'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(empty($row)){
		
		$sql = "INSERT INTO 
				propertiesCategory(categoryId, propertyId)
				VALUES
				('$categoryId', '$propertyId')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());

		//now that this is done, we display the property to the user
		echo "<div id='propertyInCateg".$propertyId."' style='float: left; margin: 10px 4px 10px 4px; font-size: 14px; color: purple;' >"
					.$property.
				"<button type='button' onclick=\"removePropertyFromCateg('".$propertyId."')\">-</button>
			</div>";
	}
?>