<?
	include("../include/connect.php");
	
	$subtypeId = mysql_real_escape_string( $_POST["subtypeId"] );
	$subtype = mysql_real_escape_string( trim( $_POST["subtype"] ) );
	
	//note, a subtype will only be removed if all its properties are removed
	//if so, we return true (echo true, that is)
	
	$sql = "SELECT *
			FROM properties
			WHERE subtypeId = '$subtypeId'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(empty($row)){
		
		//we can proceed
		$sql = "DELETE FROM	
				subtype
				WHERE subtypeId = '$subtypeId'";
				
		$result = mysql_query($sql, $con) or die(mysql_error());	
		
		echo "true";
	}
	else{
		
		//we can't delete the subtype
		echo "<script type='text/javascript'>
				alert('The subtype $subtype can't be deleted until all of its properties are removed');
			</script>";
		
		
	}

?>