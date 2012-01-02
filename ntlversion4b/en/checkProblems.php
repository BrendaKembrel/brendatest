<?

	include("includes/connect.php");
	include("classes/productClass.php");
	
	$sql = "SELECT *
			FROM product";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
		
		$itemNumber = $row["itemNumber"];
		
		$product = new Product($con, $itemNumber, "", "CAD", array());
		
		//sizesArray will have the form sizesArray[size]  = index of matching color1 in colornamesArray and colorswatchesArray,index of matching color2, etc
		//where the colors have the statuses defined by arrayOfActivationStatuses
		$arrayOfDesiredArrays = $product->getDesiredSizeColorArrays(array("released"));
		
		$sizesArray = $arrayOfDesiredArrays[0];
		$colorswatchesArray = $arrayOfDesiredArrays[1];
		$colornamesArray = $arrayOfDesiredArrays[2];
		
		
		
		
			
		$counter = 0;
		foreach($sizesArray as $size=>$col){
			
			if($size=="Select Your Size"){
			
			}
			else{
				$counter++;
			}
			
		}
			
		if($counter==0){
			
			echo "there's a problem for $itemNumber which is a ".$row['itemType']." with product id ".$row["productId"]."<br/>";
			echo "___________________________________________________________<br/>";
		}

		
	
	}
?>