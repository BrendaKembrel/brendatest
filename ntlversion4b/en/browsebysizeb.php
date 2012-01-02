<?
	
	include("includes/connect.php");
	
	$sizeArray = array();
	
	$sql = "SELECT P.size
			FROM productSizeColor P
			WHERE P.size
			IN
			(
				SELECT sd.size
				FROM sizeDefault sd
				WHERE sd.typeId = '1'
			)
			GROUP BY P.size
			ORDER BY P.size ASC";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
		
		array_push($sizeArray, $row["size"]);
	}
	
	foreach($sizeArray as $size){
		
		echo $size."<br/>";
	}
?>