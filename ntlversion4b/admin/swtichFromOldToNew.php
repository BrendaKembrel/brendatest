<?

	include("include/connect.php");
	
	$sql = "DELETE FROM
			collection
			WHERE collection = ''";
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "DELETE FROM
			productColor
			WHERE productId!='2'";
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "DELETE FROM
			productSizeColor
			WHERE productId!='2'";
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "DELETE FROM
			product
			WHERE productId!='2'";
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "DELETE FROM
			images
			WHERE productId!='2'";
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "DELETE FROM
			productFabric
			WHERE productId!='2'";
	$result = mysql_query($sql, $con) or die(mysql_error());
			
	mysql_select_db("bradocto_bltcms", $con);
	
	$sql = "SELECT *
			FROM product
			WHERE activity LIKE 'released'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
	
		$itemNumber = $row["itemNumber"];
		$modelNumber = $row["modelNumber"];
		$collection = stripslashes( $row["collection"] );
		$brand = stripslashes( $row["brand"] );
		$year = $row["year"];
		$season = strtolower( $row["season"] );
		
		if($collection!=""){
			mysql_select_db("bradocto_ntlversion4", $con);
			
			//now, we want to insert the collection into the collection table
			//if it does not exist
			$sqlC = "SELECT *
					FROM collection
					WHERE brand LIKE '$brand'
					AND collection LIKE '$collection'";
			
			$resultC = mysql_query($sqlC, $con) or die(mysql_error());
			
			$rowC = mysql_fetch_array($resultC, MYSQL_ASSOC);
			
			if(empty($rowC)){
				
				//then we need to insert the collection
				
				//we first need to give the collection a unique id
				$sqlC = "SELECT max(collectionId) as max
						FROM collection";
				$resultC = mysql_query($sqlC, $con) or die(mysql_error());
			
				$rowC = mysql_fetch_array($resultC, MYSQL_ASSOC);
				
				$collectionId = $rowC["max"]+1;
				
				$sqlC = "INSERT INTO
						collection (collectionId, brand, collection)
						VALUES ('$collectionId', '$brand', '$collection')";
						
				$resultC = mysql_query($sqlC, $con) or die(mysql_error());
			}
			
			mysql_select_db("bradocto_ntlversion4", $con);
		}
		
		$wordsArray = explode("-", $itemNumber);
		
		$descWord = "";
		
		if(empty($wordsArray)||count($wordsArray)==1){
			
			$wordsArray = explode("_", $itemNumber);
		}
		
		foreach($wordsArray as $word){
			
			if(strcasecmp($word, "inch")==0){
				
				continue;
			}
			
			$isNum = false;
			
			if(is_numeric($word)){
				
				continue;
			}
			else{
				
				for($i=1; $i<strlen($word); $i++){
					
					if(is_numeric(substr($word, $i, 1))==1){
						
						$isNum = true;
						break;
					}
				
				}
				
			}
			
			if($isNum){
				
				continue;
			}
			
			if(strcasecmp($brand, "valisere lingerie")==0){
				
				if(stripos("triumph", $word)!==false || stripos($word, "triumph")!==false){
				
					continue;
				}
				
				if(stripos("lingerie", $word)!==false || stripos($word, "lingerie")!==false){
				
					continue;
				}
			}
			
			if(stripos($modelNumber, $word)!==false || stripos($word, $modelNumber)!==false){
				
				continue;
			}
			elseif(stripos($collection, $word)!==false || stripos($word, $collection)!==false){
				
				continue;
			}
			elseif(stripos($brand, $word)!==false || stripos($word, $brand)!==false){
				
				continue;
			}
			else{
				
				$descWord .= $word." ";
			}
		
		}
		
		$descWord = trim($descWord);
		
		echo "<span style='color:red'>$descWord</span><br/><br/>";
		
		$productName = stripslashes( $row["productName"] );
		$productName = substr($productName, 0, ( stripos($productName, "by") ) );
		
		echo "<font color='purple' > the productName is $productName </font><br/>";
		
		$title = stripslashes( $row["title"] );
		$description = substr($row["longDesc"], stripos($row["longDesc"], "*"));
		$description = str_replace("32\"", "32 inches", $description);
		$description = str_replace("\"", "'", $description);
		$description = str_replace(" - ", " to ", $description);
		$description = str_replace("*", "", $description);
		$description = str_replace("<br>", "<br/>", $description);
		
		
		$frenchDescription = stripslashes( $row["longDescFrench"] );
		
		$date = date("Y-m-d");
		$modifiedDate = date("Y-m-d");
		
		$price = $row["price"];
		$finalPrice = $row["salePrice"];
		$priceType = $row["priceType"];
		
		$canPrice = $row["canPrice"];
		$canFinalPrice = $row["canSalePrice"];
		$canPriceType = $row["canPriceType"];
		
		if($finalPrice==""){
			
			$finalPrice = $price;
		}
		
		if($canPrice==""||$canPrice=="0"||$canPrice=="0.00"){
		
			$canPrice = $price;
			
			if($canFinalPrice==""||$canFinalPrice=="0"||$canFinalPrice=="0.00"){
			
				$canFinalPrice = $finalPrice;
			}
		}
		elseif($canFinalPrice==""||$canFinalPrice=="0"||$canFinalPrice=="0.00"){
			
				$canFinalPrice = $canPrice;
		}
		
		
	
		if($canPriceType==""||$canPriceType=="0"){
	
		$canPriceType = $priceType;
		}
			
		echo "so we have $price $finalPrice $canPrice $canFinalPrice <br/>";
		
		$itemType = $row["itemType"];
		
		
		switch($itemType){
			
			case(1):{
				
				if(strcasecmp($brand, "elita lingerie")==0){
					
					//mini camisole
					$itemType = 5;
				}
				else{
					
					$itemType = 1;
				}
				
				
			}break;
			case(2):{
				
				$itemType = 3;
				
			}break;
			case(3):{
				
				$itemType = 2;
				
			}break;
			case(4):{
				
				$itemType = 4;
				
			}break;
			case(5):{
				
				$itemType = 8;
				
			}break;
			case(6):{
				
				$itemType = 7;
				
			}break;
			case(8):{
				
				$itemType = 13;
				
			}break;
			case(9):{
				
				$itemType = 19;
				
			}break;
			case(10):{
				
				$itemType = 9;
				
			}break;
			case(11):{
				
				$itemType = 18;
				
			}break;
		
		}
		
		if($itemType==18){
			
			echo "<font color='green'>we get here wtih $itemNumber</font><br/>";
			continue;
		}
		
		if(stripos($productName, "bodysuit")!==false){
			
			$itemType = "12";
		}
		
		if(stripos($productName, "robe")!==false){
			
			$itemType = "15";
		}
		
		if(stripos($productName, "top")!==false||stripos($productName, "sleeve ")!==false){
			
			$itemType = "6";
		}
		
		if(stripos($productName, "shorts")!==false){
			
			$itemType = "10";
		}
		
		if(stripos($productName, "garter")!==false){
			
			$itemType = "11";
		}
		
		if(stripos($productName, "slip")!==false){
			
			$itemType = "14";
		}
		
		if(stripos($productName, "legging")!==false){
			
			$itemType = "17";
		}
		
		$sizingFormat = "";
		
		mysql_select_db("bradocto_ntlversion4", $con);
		
		$sql2 = "SELECT sizeDefault
				FROM itemType
				WHERE itemTypeId = '$itemType'";
				
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
		$row2 =  mysql_fetch_array($result2, MYSQL_ASSOC);
		
		$sizingFormat = $row2["sizeDefault"];
		
		mysql_select_db("bradocto_bltcms", $con);
		
		$diffSizeDiffColor = $row["diffSizeDiffColor"];

		if($diffSizeDiffColor){
			
			$sizesArray = explode("/", $row["sizes"]);
			
			$index = 0;
			mysql_select_db("bradocto_ntlversion4", $con);
			foreach($sizesArray as $s){
			
				//now we have a bunch of comma-separated strings
				$sizeArrayByColor = explode(",", $s);
				array_pop($sizeArrayByColor);
				
				foreach($sizeArrayByColor as $size){	
					
					if($size!=""){
						
						$sqlCheck = "SELECT *
									FROM sizeDefault
									WHERE size LIKE '$size'
									AND typeId = '$sizingFormat'";
						
						$resultCheck = mysql_query($sqlCheck, $con) or die(mysql_error());
						
						$rowCheck = mysql_fetch_array($resultCheck, MYSQL_ASSOC);
						
						if(empty($rowCheck)){
							
							$sqlCheck2 = "SELECT typeId
									FROM sizeDefault
									WHERE size LIKE '$size'";
						
							$resultCheck2 = mysql_query($sqlCheck2, $con) or die(mysql_error());
							
							$rowCheck2 = mysql_fetch_array($resultCheck2, MYSQL_ASSOC);
							
							$sizingFormat = $rowCheck2["typeId"];
						}
						
					}
			
				}
			}
			
		}
		else{
			mysql_select_db("bradocto_bltcms", $con);
			$sizesArray = explode(",", str_replace("br", "", $row["sizes"]));
			mysql_select_db("bradocto_ntlversion4", $con);
			foreach($sizesArray as $size){
				
				if($size!=""){
						
						$sqlCheck = "SELECT *
									FROM sizeDefault
									WHERE size LIKE '$size'
									AND typeId = '$sizingFormat'";
						
						$resultCheck = mysql_query($sqlCheck, $con) or die(mysql_error());
						
						$rowCheck = mysql_fetch_array($resultCheck, MYSQL_ASSOC);
						
						if(empty($rowCheck)){
							
							$sqlCheck2 = "SELECT typeId
									FROM sizeDefault
									WHERE size LIKE '$size'";
						
							$resultCheck2 = mysql_query($sqlCheck2, $con) or die(mysql_error());
							
							$rowCheck2 = mysql_fetch_array($resultCheck2, MYSQL_ASSOC);
							
							$sizingFormat = $rowCheck2["typeId"];
						}
						
				}
				
			}
		
		}
		$sql2 = "SELECT max(productId) as max
				FROM product";
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
		$row2 =  mysql_fetch_array($result2, MYSQL_ASSOC);

		if(empty($row2)){
			
			$productId = 1;
		}
		else{
			
			$productId = $row2["max"]+1;
		}
		
		$sql2 = "INSERT INTO 
				product(productId, itemNumber, modelNumber, brand, productName, title, description, price, finalPrice, canPrice,
				canFinalPrice, year, season, date, modifiedDate, priceType, canPriceType, collection, descWord,itemType, sizingFormat)
				VALUES
				('$productId', '$itemNumber', '$modelNumber', '$brand', \"$productName\", \"$title\", \"$description\", '$price', '$finalPrice', '$canPrice',
				'$canFinalPrice', '$year', '$season', '$date', '$modifiedDate', '$priceType', '$canPriceType', '$collection',
				'$descWord', '$itemType', '$sizingFormat')";
				
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
		echo $sql2."<br/><br/>";
		mysql_select_db("bradocto_bltcms", $con);
		
		//now, we deal with colors
	
	$colorsArray = explode(",", $row["colors"]);
	
	array_pop($colorsArray);
	
	$diffSizeDiffColor = $row["diffSizeDiffColor"];
	
	mysql_select_db("bradocto_ntlversion4", $con);
	
	$arrayOfCids = array();
	
	foreach($colorsArray as $color){
		
		$sql2 = "SELECT max(cid) as max
				FROM productColor";
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
	
		$row2 =  mysql_fetch_array($result2, MYSQL_ASSOC);

		if(empty($row2)){
			
			$cid = 1;
		}
		else{
			
			$cid = $row2["max"]+1;
		}
		
		
		$sql2 = "SELECT defaultSwatch
				FROM brand
				WHERE brand LIKE '$brand'";
				
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
		$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		$defaultSwatch = $row2["defaultSwatch"];
		
		$colorToUseForSwatch = $color;
		
		if($defaultSwatch==0){
			
			//color.jpg
			$swatch = $colorToUseForSwatch.".jpg";
		}
		else{
			
			//collectioncolor.jpg
			$swatch = $collection.$colorToUseForSwatch.".jpg";
		}
		
		
		$swatch = strtolower(str_replace("/", "", $swatch));
		$swatch = str_replace(" ", "", $swatch);
		
		echo "we're here with $cid <br/>";
		
		$sql2 = "INSERT INTO
				productColor(productId, color, cid, image, imageId, activationDate)
				VALUES
				('$productId', '".strtolower($color)."', '$cid', '$swatch', '-1', '".date("Y-m-d")."')";
				
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
		echo $sql2."<br/><br/>";
		
		array_push($arrayOfCids, $cid);
	}
	
	mysql_select_db("bradocto_bltcms", $con);
	
	if($diffSizeDiffColor){
		
		$sizesArray = explode("/", $row["sizes"]);
		
		$index = 0;
		mysql_select_db("bradocto_ntlversion4", $con);
		foreach($sizesArray as $s){
		
			//now we have a bunch of comma-separated strings
			$sizeArrayByColor = explode(",", $s);
			array_pop($sizeArrayByColor);
			
			foreach($sizeArrayByColor as $size){
				
				$size = str_replace("br", "", $size);
				
				$sql2 = "SELECT max(sid) as max
					FROM productSizeColor";
				$result2 = mysql_query($sql2, $con) or die(mysql_error());
			
				$row2 =  mysql_fetch_array($result2, MYSQL_ASSOC);

				if(empty($row2)){
					
					$sid = 1;
				}
				else{
					
					$sid = $row2["max"]+1;
				}
				
				echo "we're here with $index and $arrayOfCids[$index] <br/>";
		
				if($size!=""){
				
					$sql2 = "INSERT INTO productSizeColor
							(productId, sid, cid, size)
							VALUES
							('$productId', '$sid', '$arrayOfCids[$index]', '$size')";
							
					$result2 = mysql_query($sql2, $con) or die(mysql_error());
					
					echo $sql2."<br/><br/>";
				}
			}
			
			$index++;
		}
		
	}
	else{
		
		$sizesArray = explode(",", $row["sizes"]);
		array_pop($sizesArray);
		
		$index = 0;
		
		mysql_select_db("bradocto_ntlversion4", $con);
		
		foreach($sizesArray as $size){
			
			$size = str_replace("br", "", $size);
			
			for($i = $index; $i<count($arrayOfCids); $i++){
				
				$sql2 = "SELECT max(sid) as max
					FROM productSizeColor";
				$result2 = mysql_query($sql2, $con) or die(mysql_error());
			
				$row2 =  mysql_fetch_array($result2, MYSQL_ASSOC);

				if(empty($row2)){
					
					$sid = 1;
				}
				else{
					
					$sid = $row2["max"]+1;
				}
				
			
				$sql2 = "INSERT INTO productSizeColor
						(productId, sid, cid, size)
						VALUES
						('$productId', '$sid', '$arrayOfCids[$i]', '$size')";
						
				$result2 = mysql_query($sql2, $con) or die(mysql_error());
				
				echo $sql2."<br/><br/>";
			}
		}
		
	}
	
	mysql_select_db("bradocto_bltcms", $con);
	
	echo "<br/><br/>";
	
	//now we deal with fabrics
	$fabricString = strtolower( $row["fabricComp"] );
	
	$liningIndex = stripos($fabricString, "<br/>");
	
	if($liningIndex!==false){
		
		$fabric = substr($fabricString, 0, $liningIndex);
	}
	else{
		
		$fabric = $fabricString;
	}
	
	$fabricArray = explode(",", $fabric);
	
	foreach($fabricArray as $fab){
		
		if($fab==""){
			
			continue;
		}
		
		$type = "fabric";
		$percentage = substr($fab, 0, stripos($fab, "% "));
		$fabricMaterial = substr($fab, stripos($fab, "% ")+2, strlen($fab)-(stripos($fab, "% ")+2));
		
		mysql_select_db("bradocto_ntlversion4", $con);
		
		$sql2 = "INSERT INTO 
				productFabric
				(productId, fabric, percentage, type)
				VALUES
				('$productId', '$fabricMaterial', '$percentage', '$type')";
				
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
		mysql_select_db("bradocto_bltcms", $con);
		
		echo $sql2."<br/><br/>";
	}
	
	if($liningIndex!==false){
	
		$lining = substr($fabricString, $liningIndex+5, strlen($fabricString)-($liningIndex+5));
		
		echo "<span style='color:blue'>the lining is $lining<br/></span>";
		
		$liningIndex = stripos($lining, ":");
		
		$type = substr($lining, 0, $liningIndex);
		
		$fabricArray = explode(",", substr($lining, $liningIndex+1, strlen($lining)-($liningIndex+1) ) );
		
		foreach($fabricArray as $fab){
		
			if($fab==""){
				
				continue;
			}
			
			$percentage = substr($fab, 0, stripos($fab, "%"));
			$blah = stripos($fab, "% ");
			$blah2 = strlen($fab)-($blah+2);
			$fabricMaterial = trim ( substr($fab, $blah+2) );
			
			mysql_select_db("bradocto_ntlversion4", $con);
			
			$sql2 = "INSERT INTO 
				productFabric
				(productId, fabric, percentage, type)
				VALUES
				('$productId', '$fabricMaterial', '$percentage', '$type')";
			
			$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
			echo $sql2."<br/><br/>";
			
			mysql_select_db("bradocto_bltcms", $con);
		}
	}
	
	//now we deal with images
	$defaultB = $row["defaultB"];
	
	if($defaultB!=""){
		
		$image = $defaultB."p.jpg";
	}
	else{
		
		$image = $modelNumber."p.jpg";
	}
	
	mysql_select_db("bradocto_ntlversion4", $con);
	$sql2 = "SELECT max(imageId) as max
			FROM images";
	
	$result2 = mysql_query($sql2,$con) or die(mysql_error());
	
	$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
	
	if(empty($row2)){
	
		$imageId = 1;
	}
	else{
	
		$imageId = $row2["max"]+1;
	}
			
	
	
	$sql2 = "INSERT INTO 
			images (productId, imageId, image, type)
			VALUES ('$productId', '$imageId', '$image', 'default')";
	
	$result2 = mysql_query($sql2, $con) or die(mysql_error());
	
	echo $sql2."<br/><br/>";
	
	echo "<br/><br/>";
	
	mysql_select_db("bradocto_bltcms", $con);
	
	$otherImages = $row["otherImages"];
	
	$otherImagesArray = explode(",", $otherImages);
	
	foreach($otherImagesArray as $oi){
		
		if($oi==""){
		
			continue;
		
		}
		
		$image = $oi."p.jpg";
		
		mysql_select_db("bradocto_ntlversion4", $con);
		$sql2 = "SELECT max(imageId) as max
				FROM images";
		
		$result2 = mysql_query($sql2,$con) or die(mysql_error());
		
		$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		if(empty($row2)){
		
			$imageId = 1;
		}
		else{
		
			$imageId = $row2["max"]+1;
		}
				
		
		$sql2 = "INSERT INTO 
				images (productId, imageId, image, type)
				VALUES ('$productId', '$imageId', '$image', 'product view')";
		
		$result2 = mysql_query($sql2, $con) or die(mysql_error());
		
		echo $sql2."<br/><br/>";
		
		mysql_select_db("bradocto_bltcms", $con);
	
	}
	
}//end while
	
	

?>