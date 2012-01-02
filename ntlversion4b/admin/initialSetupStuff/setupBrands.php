<?
	
	include("../include/connect.php");
	
		
	$sql = "DELETE FROM
			brand";
	
	$result = mysql_query($sql, $con) or die(mysql_error());

	
	mysql_select_db("bradocto_bltcms", $con);
	
	$sql = "SELECT brand, shortenedBrand
			FROM brands
			ORDER BY brand ASC";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$counter = 0;
	
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
	
		
		$brand = $row["brand"];
		$shortenedBrand = $row["shortenedBrand"];
		
		
		mysql_select_db("bradocto_ntlversion4", $con);
		
		$sqlIns = "INSERT INTO brand (brandId, brand, shortenedBrand)
					VALUES ('$counter', '$brand', '$shortenedBrand')";
		
		$resultInst = mysql_query($sqlIns, $con) or die(mysql_error());
		
		mysql_select_db("bradocto_bltcms", $con);
		
		$counter++;
	}
	


?>