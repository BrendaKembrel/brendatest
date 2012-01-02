<?
	
	include("../include/connect.php");
	
	$sql = "DELETE FROM
			itemType";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$i = 1;
	
	$itemTypeArray = array("bras"=>"1", "camisoles"=>"2", "panties"=>"2", "chemises"=>"2", "mini camisoles"=>"6", "tops"=>"2", 
	"dresses"=>"2", "pants"=>"2","teddies"=>"2", "shorts"=>"2", "garters"=>"4", "bodysuits"=>"2",  "fashion accessories"=>"4", 
	"slips"=>"2", "robes"=>"2", "corsets"=>"2",  "leggings"=>"2");
	
	foreach($itemTypeArray as $key=>$value){
		
		$sql = "INSERT INTO 
				itemType (itemTypeId, itemType, sizeDefault)
				VALUES ('$i', '$key', '$value')";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$i++;
	}

?>