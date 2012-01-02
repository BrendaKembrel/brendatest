<?
	
	include("../include/connect.php");
	
	
	$sql = "DELETE FROM
			fabric";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	mysql_select_db("bradocto_bltcms", $con);
	
	
	$sql = "SELECT name
			FROM fabric
			ORDER BY name ASC";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$counter = 0;

	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
	
		
		$fabric = $row["name"];
		
		
		mysql_select_db("bradocto_ntlversion4", $con);
		
		$sqlIns = "INSERT INTO fabric (fabricId, fabric)
					VALUES ('$counter', '$fabric')";
		
		$resultInst = mysql_query($sqlIns, $con) or die(mysql_error());
		
		mysql_select_db("bradocto_bltcms", $con);
		
		$counter++;
	}
	


?>