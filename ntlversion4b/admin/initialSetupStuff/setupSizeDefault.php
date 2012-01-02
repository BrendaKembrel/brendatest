<?

include("../include/connect.php");

$sql = "DELETE FROM
		sizeDefault";
	
$result = mysql_query($sql, $con) or die(mysql_error());



$arrayofletters = array("aa", "a", "b", "c","d", "dd", "ddd", "e", "ee", "f", "ff", "g", "gg", "h", "hh", "i", "j", "k", "l");

$arrayofaccessories = array("one size", "1 hook", "2 hook", "2 hook euro style", "3 hook", "4 hook", "extra small", "small", "medium", "large", "extra large", "extra extra large");

$arrayOfPanties = array("Extra Extra Small", "Extra Small", "Small", "Medium", "Large", "Extra Large", "Extra Extra Large", "1X", "2X", "3X", "4X", "1XL", "2XL", "3XL", "4XL", "5XL", "6XL", "7XL", "8XL", "9XL");

$arrayOfDashes = array("30-32", "32-34", "34-36", "36-38", "38-40", "40-42", "42-44", "44-46", "46-48", "48-50", "XS-S", "S-M", "M-L", "L-XL", "XL-XXL");

$arrayOfClothing = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "18");

$braSizes = array();

foreach($arrayofletters as $letter){

	for($k=28; $k<=50; $k=$k+2){
	
		array_push($braSizes, $k.$letter);
	
	}

}

$arrayOfBras = array();

$i = 1;
$counter = 1;
foreach($braSizes as $value){

	$sql = "INSERT INTO
			sizeDefault(id, type, size, typeId)
			VALUES ('$i', 'eg) 32a,44dd', '$value', '$counter')";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$i++;
	
}
$counter++;
foreach($arrayOfPanties as $value){

	$sql = "INSERT INTO
			sizeDefault(id, type, size, typeId)
			VALUES ('$i', 'eg) small,1X', '$value', '$counter')";
			
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$i++;
	
}
$counter++;
foreach($arrayOfClothing as $value){

	$sql = "INSERT INTO
			sizeDefault(id, type, size, typeId)
			VALUES ('$i', 'eg) 2,4,16', '$value','$counter')";
			
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$i++;
	
}
$counter++;
foreach($arrayofaccessories as $value){

	$sql = "INSERT INTO
			sizeDefault(id, type, size, typeId)
			VALUES ('$i', 'eg) one size, 4 hook', '$value', '$counter')";
			
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$i++;
	
}
$counter++;
foreach($arrayOfDashes as $value){

	$sql = "INSERT INTO
			sizeDefault(id, type, size, typeId)
			VALUES ('$i', 'eg) S-M,32-34', '$value', '$counter')";
			
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$i++;
	
}
$counter++;
for($j = 28; $j<=50; $j=$j+2){

$sql = "INSERT INTO
			sizeDefault(id, type, size, typeId)
			VALUES ('$i', 'eg) 32,34,44', '$j', '$counter')";
			
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$i++;
	
}


?>