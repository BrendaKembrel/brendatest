<?
	
	//this is the file that helps insert everything into the database
	
	include("include/connect.php");

	//note, for all info coming from step 0, all the stripslashing
	//and trimming was already done in step 1
	
	//these variables are from step 0 via step 1
	$productId = mysql_real_escape_string($_POST["productId"]);
	//note again that the newProductId is for inserting and copying
	//it will be the productId (unless it's taken in the interim)
	//of the new product that will be inserted into the database
	$newProductId = mysql_real_escape_string($_POST["newProductId"]);
	$action = mysql_real_escape_string($_POST["action"]);
	
	//now, note that if the action isn't a modify, but instead
	//a copy or an insert, then we're going to have to create a new
	//unique productId.  If someone else created a product in the interim,
	//we might have to change our value of newProductId
	if($newProductId!=-1){
	
		$sql = "SELECT max(productId) as max
						FROM product";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$newProductId = $row["max"]+1;
		$productId = $newProductId;
		
		if(strcasecmp("insert", $action)==0){
			
			//now we do an insert, claiming this productId
			$sql = "INSERT INTO 
					product (productId) VALUES ('$productId')";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			echo "<span style='color:green;'>we get here wtih $productId with $sql<br/></span>";
		
		}
		
	}
	else{
	
		echo "<span style='color:purple;'>yay wtih $productId and $newProductId with $sql<br/></span>";
	}
	
	$modelNumber = mysql_real_escape_string($_POST["modelNumber"]);
	$collection = mysql_real_escape_string($_POST["collection"]);
	$descWord = mysql_real_escape_string($_POST["descWord"]);
	$brand = mysql_real_escape_string($_POST["brand"]);
	
	//now, we want to insert the collection into the collection table
	//if it does not exist
	$sql = "SELECT *
			FROM collection
			WHERE brand LIKE '$brand'
			AND collection LIKE '$collection'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(empty($row)){
		
		//then we need to insert the collection
		
		//we first need to give the collection a unique id
		$sql = "SELECT max(collectionId) as max
				FROM collection";
		$result = mysql_query($sql, $con) or die(mysql_error());
	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$collectionId = $row["collectionId"]+1;
		
		$sql = "INSERT INTO
				collection (collectionId, brand, collection)
				VALUES ('$collectionId', '$brand', '$collection')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
			
	$itemType = mysql_real_escape_string($_POST["itemType"]);
	$season = mysql_real_escape_string($_POST["season"]);
	$year = mysql_real_escape_string($_POST["year"]);
	$sizingFormat = mysql_real_escape_string($_POST["sizingFormat"]);
	
	$itemNumber = mysql_real_escape_string($_POST["itemNumber"]);
	$productName = mysql_real_escape_string($_POST["productName"]);
	
	$title = $productName." | Now That's Lingerie";
	
	$numberOfColors = mysql_real_escape_string($_POST["numberOfColors"]);
	
	//PLEASE NOTE: In the case of a modification of a product
	//we will do the following.
	//for all other tables than the main product one
	//(eg: productColor, productFabric, etc)
	//we're going to delete all old values 
	//so we can fill them with the new current ones
	//and not need to worry about keeping track of what the user decides to delete
	
	
	echo "<p style='font-color:green'>
			productId: $productId<br/>
			newProductId: $newProductId<br/>
			action: $action<br/><br/>
			modelNumber: $modelNumber<br/>
			collection: $collection<br/>
			descWord: $descWord<br/>
			brand: $brand<br/><br/>
			itemType: $itemType<br/>
			season: $season<br/>
			year: $year<br/>
			sizingFormat: $sizingFormat<br/><br/>
			itemNumber: $itemNumber<br/>
			productName: $productName<br/>
			title: $title<br/><br/>
			numberOfColors: $numberOfColors<br/><br/>
		 </p>";
	
	//We are going to deal with colors now
	//we are going to keep track of an array
	//of colors that are current.  
	//If a product was modified and one of
	//its previously existing colors is not in the arrayOfColors
	//then we know that this color was deleted and we must remove it
	//from various tables
	$arrayOfColors = array();
	
	//now we have to get the info for each color, and insert that info into the 
	//productColor table
	for($i=1; $i<=$numberOfColors; $i++){
		
		$colorName = "color".$i;
		
		//we need the following boolean value to know if this is a brand new color
		//or if it was one that was already used
		$newColor = false;
		
		//note all the trimming and stripslashes stuff was already done in step 1
		$color = mysql_real_escape_string($_POST[$colorName]);
		
		$colorToUseForSwatch = str_replace(" ", "", $color);
		$colorToUseForSwatch = str_replace("/", "", $colorToUseForSwatch);
		
		$activationDate = mysql_real_escape_string($_POST[$colorName."activationDate"]);
		$deactivationDate = mysql_real_escape_string($_POST[$colorName."deactivationDate"]);
		$isNewUntil = mysql_real_escape_string($_POST[$colorName."isNewUntil"]);
		
		if(!isset($_POST["swatch".$i])){
		
			$swatch = "";
		}
		else{
			$swatch = mysql_real_escape_string($_POST["swatch".$i]);
		}
		
		if($swatch == ""){
			
			//we also won't get any swatch options so we're going to use the brand's
			//default 
			$sql = "SELECT defaultSwatch
					FROM brand
					WHERE brand LIKE '$brand'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$defaultSwatch = $row["defaultSwatch"];
			
			if($defaultSwatch==0){
				
				//color.jpg
				$swatch = $colorToUseForSwatch.".jpg";
			}
			else{
				
				//collectioncolor.jpg
				$swatch = $collection.$colorToUseForSwatch.".jpg";
			}
			
			$swatch = strtolower(str_replace("/", "", $swatch));
			$swatch = strtolower(str_replace(" ", "", $swatch));
		}
		
		//we want to know if this color already exists
		//for the product (in the case of modified prods).  If so, we get its cid
		//otherwise, we will have to create a new cid
		$cid = -1;
		if(strcasecmp($action, "modify")==0){
			
			$sql = "SELECT cid
					FROM productColor
					WHERE productId LIKE '$productId'
					AND color LIKE '$color'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			if(!empty($row)){
				
				$cid = $row["cid"];
			}
			
			//since we have an existing cid for this color
			//already, we know that we can update the productColor table
			$sql = "UPDATE productColor
					SET image = '$swatch', activationDate = '$activationDate',
					deactivationDate = '$deactivationDate', isNewUntil='$isNewUntil'
					WHERE productId = '$productId' AND cid='$cid'";
			
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			echo "<br/>".$sql."<br/>";
		}
		
		if($cid==-1){
			
			$newColor = true;
			
			//then we haven't yet a cid for this color
			$sql = "SELECT max(cid) as max
					FROM productColor";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$cid = $row["max"] + 1;
			
			//now that we have our brand new cid, and we know that
			//this color hasn't existed yet, we can go ahead and
			//make an insert
			
			$sql = "INSERT INTO productColor
					(productId, color, image, cid, activationDate, deactivationDate, isNewUntil, imageId)
					VALUES
					('$productId', '$color', '$swatch', '$cid', '$activationDate', '$deactivationDate', '$isNewUntil', '-1')";
			
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			echo "<br/>".$sql."<br/>";
		}
		
	
		
		array_push($arrayOfColors, $cid);
		
	
		//now, because sizing is on a color by color basis, we also deal with the sizes for this color
		
		//first of all, if we're not dealing with a new color, then we must delete
		//all sizing associated with this color in the current sizing format
		//so we can start with a clean slate
		//(notice that we only do this for the current sizing format.  We maintain the values
		//for older and different sizing formats because we want these to always be stored in case 
		//the product's sizing format changes)
		
		if(!$newColor){
			
			$sql = "DELETE 
					FROM productSizeColor
					WHERE cid = '$cid'
					AND size IN
					(
						SELECT sd.size
						FROM sizeDefault sd
						WHERE sd.typeId='$sizingFormat'
					)";
					
			$result = mysql_query($sql, $con) or die(mysql_error());

		}
		
		//so now we're free to insert our sizes for this specific color!
		
		if($sizingFormat==1){
			
			//then we're dealing with bra sizes which work slightly differently
			
			//first we deal with the sizing originally present
			$numberOfSizes = mysql_real_escape_string($_POST["numberOfSizes".$i]);
			
			echo "the numberOfSizes is $numberOfSizes <Br/>";
			for($j=1; $j<$numberOfSizes; $j++){
				
				$cup = mysql_real_escape_string($_POST["cup".$i.$j]);
				
				if(!isset($cup)||strcasecmp($cup, "none")==0){
					
					continue;
				
				}
				
				$range1 = trim( mysql_real_escape_string($_POST["range1".$i.$j]) );
				$range2 = trim( mysql_real_escape_string($_POST["range2".$i.$j]) );
				$exclude = trim( mysql_real_escape_string($_POST["exclude".$i.$j]) );
				
				if($range1==""||$range2==""){
					
					continue;
				}
				
				//note that, if exclude isn't empty, we want to make sure it ends with a comma
				if($exclude!=""){
					
					if(substr($exclude, -1)!=","){
						
						$exclude.=",";
					}
				}
				
				//now that we have all of our values, we need to break them up and insert them 
				//accordingly
				$excludeArray = explode(",", $exclude);
				if(!empty($excludeArray)){
					
					array_pop($excludeArray);
				}
				
				//we increment by 2 as band sizes always do
				for($k=$range1; $k<=$range2; $k+=2){
					
					if(!empty($excludeArray)&&in_array($k, $excludeArray)){
						
						//then we know to exclude this specific size
						continue;
					}
					else{
						
						$size = $k.$cup;
						
						$sql = "SELECT max(sid) as max
						FROM productSizeColor";
						
						$result = mysql_query($sql, $con) or die(mysql_error());
						
						$row = mysql_fetch_array($result, MYSQL_ASSOC);
						
						$sid = $row["max"]+1;
								
						$sql = "INSERT INTO 
								productSizeColor(productId, sid, size, cid)
								VALUES('$productId', '$sid', '$size', '$cid')";
								
						echo "<br/>".$sql."<br/>";
						$result = mysql_query($sql, $con) or die(mysql_error());
					
					}
				
				}
			}//end looping through numberOfSizes
			
			//next we deal with added sizes
			$addedSizes = mysql_real_escape_string($_POST["addedSizes".$i]);
			echo "<font color='green'>the addedsizes is $addedSizes <Br/></font>";
			for($j=1; $j<=$addedSizes; $j++){
				
				$cup = mysql_real_escape_string($_POST["addedcup".$i.$j]);
				
				echo "<span style='color: red'>we enter here with the cup $cup</span><br/>";
				
				if(!isset($cup)||strcasecmp($cup, "none")==0){
					
					continue;
				
				}
				
				$range1 = trim( mysql_real_escape_string($_POST["addedrange1".$i.$j]) );
				$range2 = trim( mysql_real_escape_string($_POST["addedrange2".$i.$j]) );
				$exclude = trim( mysql_real_escape_string($_POST["addedexclude".$i.$j]) );
				
				echo "<span style='color: red'>we even get here with $range1 $range2 $exclude</span><br/>";
				
				if($range1==""||$range2==""){
					
					continue;
				}
				
				
				
				//note that, if exclude isn't empty, we want to make sure it ends with a comma
				if($exclude!=""){
					
					if(substr($exclude, -1)!=","){
						
						$exclude.=",";
					}
				}
				
				//now that we have all of our values, we need to break them up and insert them 
				//accordingly
				$excludeArray = explode(",", $exclude);
				if(!empty($excludeArray)){
					
					array_pop($excludeArray);
				}
				
				//we increment by 2 as band sizes always do
				for($k=$range1; $k<=$range2; $k+=2){
					
					if(!empty($excludeArray)&&in_array($k, $excludeArray)){
						
						//then we know to exclude this specific size
						continue;
					}
					else{
						
						$size = $k.$cup;
						
						$sql = "SELECT max(sid) as max
						FROM productSizeColor";
						
						$result = mysql_query($sql, $con) or die(mysql_error());
						
						$row = mysql_fetch_array($result, MYSQL_ASSOC);
						
						$sid = $row["max"]+1;
								
						$sql = "INSERT INTO 
								productSizeColor(productId, sid, size, cid)
								VALUES('$productId', '$sid', '$size', '$cid')";
								
						echo "<br/>".$sql."<br/>";
						$result = mysql_query($sql, $con) or die(mysql_error());
					
					}
				
				}
			}//end looping through addedSizes
			
		}
		else{
			
			$sizeArray = array();
			$sizeArray = $_POST["sizeSelection".$i];
			
			if(!empty($sizeArray)){
				foreach($sizeArray as $size){
					
					echo "$size<br/>";
					
					//when we insert, we need to make sure we have
					//a unique sid
					$sql = "SELECT max(sid) as max
							FROM productSizeColor";
					
					$result = mysql_query($sql, $con) or die(mysql_error());
					
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
							
					$sid = $row["max"] + 1;
					
					//now we can insert
					$sql = "INSERT INTO 
							productSizeColor (productId, sid, cid, size)
							VALUES ('$productId', '$sid', '$cid', '$size')";
					
					$result = mysql_query($sql, $con) or die(mysql_error());
				}
			}
		}
		
	}//end for $i = looping through colors
	
	//now's the clean up phase for colors
	//at this stage, we have our arrayOfColors
	//which contains the cid of every color that is currently associated with this product
	//if we're modifying a product, then we know that all cids that are not in the array
	//belong to colors that no longer should be associated with this product
	//and so we must delete them from the appropriate tables
	
	if(strcasecmp($action, "modify")==0){
		
		$sql = "SELECT cid
			FROM productColor
			WHERE productId = '$productId'";
			
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		while( $row = mysql_fetch_array($result, $con) ){
		
			$cid = $row["cid"];
			
			//now we check if the cid is in the $arrayOfColors
			if(!in_array($cid, $arrayOfColors)){
			
				//then we delete the cid from several places
				
				//first from productColor
				$sqlDel = "DELETE
						   FROM productColor
						   WHERE productId = '$productId'
						   AND cid = '$cid'";
				$resultDel = mysql_query($sqlDel, $con) or die(mysql_error());
				
				$sidArray = array();
				
				//before we can delete from the productWarehouse,
				//we need to know all sids associatd with this color
				$sqlSid = "SELECT sid
						FROM productSizeColor
						WHERE productId = '$productId'
						AND cid = '$cid'";
						
				$resultSid = mysql_query($sqlSid, $con) or die(mysql_error());
				
				while($rowSid = mysql_fetch_array($resultSid, MYSQL_ASSOC)){
					
					$sid = $rowSid["sid"];
					
					//now we can delete from the warehouse
					// from productWarehouse
					$sqlDel = "DELETE
							   FROM productWarehouse
							   WHERE productId = '$productId'
							   AND sid = '$sid'";
					$resultDel = mysql_query($sqlDel, $con) or die(mysql_error());
				}
				
				//and finally from productSizeColor
				$sqlDel = "DELETE
						   FROM productSizeColor
						   WHERE productId = '$productId'
						   AND cid = '$cid'";
				$resultDel = mysql_query($sqlDel, $con) or die(mysql_error());
				
			}	
		}
		
	}	
	
	//the following variables are from step 1
	
	//these are from the first 2 panels
	$description = trim( stripslashes ( mysql_real_escape_string($_POST["description"]) ) );
	$frenchDescription = trim( stripslashes ( mysql_real_escape_string($_POST["frenchDescription"]) ) );
	$fd1 = htmlspecialchars($frenchDescription);
	$fd2 = htmlentities($frenchDescription);
	$braDoctorHelp = trim( stripslashes ( mysql_real_escape_string($_POST["braDoctorHelp"]) ) );
	
	$cost = number_format( trim ( mysql_real_escape_string($_POST["cost"]) ) , 2);
	$price = number_format( trim( stripslashes ( mysql_real_escape_string($_POST["price"]) ) ), 2);
	$finalPrice = number_format( trim( stripslashes ( mysql_real_escape_string($_POST["finalPrice"]) ) ), 2);
	$canPrice = number_format( trim( stripslashes ( mysql_real_escape_string($_POST["canPrice"]) ) ), 2);
	$canFinalPrice = number_format( trim( stripslashes ( mysql_real_escape_string($_POST["canFinalPrice"]) ) ), 2);
	$priceType = mysql_real_escape_string($_POST["priceType"]);
	$canPriceType = mysql_real_escape_string($_POST["canPriceType"]);
	
	if($priceType==0){
		
		if($finalPrice!=""&&$finalPrice!=0&&$finalPrice!=$price){
			
			//then we set it to our price
			$priceType = 2;//our price
		}
		else{
			
			
			$priceType = 1;//regular
		}
		
	}
	
	if($finalPrice==""|| strcasecmp($finalPrice, "0")==0 ||  strcasecmp($finalPrice, "0.00")==0){
		
		$finalPrice = $price;
	}
	
	if($canPrice==""|| strcasecmp($canPrice, "0")==0 ||  strcasecmp($canPrice, "0.00")==0){
		
		$canPrice = $price;
		$canFinalPrice = $finalPrice;
	}
	
	if($canFinalPrice==""|| strcasecmp($canFinalPrice, "0")==0 ||  strcasecmp($canFinalPrice, "0.00")==0){
		
		$canFinalPrice = $canPrice;
	}
	
	if($canPriceType==0){
	
		if($canFinalPrice!=""&&$canFinalPrice!=0){
			
			//then we set it to our price
			$canPriceType = 2;//our price
		}
		else{
			
			$canPriceType = 1;//regular
		}
	}
	
	//now we're onto fabrics
	
	//these are for original fabrics
	$fabricCounter = mysql_real_escape_string($_POST["fabricCounter"]);
	
	//these are for additional fabrics
	$addedFabrics = mysql_real_escape_string($_POST["addedFabrics"]);
	
	echo "orig fabrics: <br/>";
	
	//we're going to first delete
	//all fabrics associated with this product
	//and then add all the ones that were passed over
	//from productStep1 (which = all current fabrics)
	
	if(strcasecmp($action, "modify")==0){
		
		$sql = "DELETE FROM
			productFabric
			WHERE productId = '$productId'";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
	
	}
	
	
	//here we use a < because the fabricCounter is only incremented at the end
	for($i=1; $i<$fabricCounter; $i++){
		
		$type = strtolower( trim( mysql_real_escape_string($_POST["fabricType".$i]) ) );
		$fabric = mysql_real_escape_string($_POST["fabric".$i]);
		$percentage = trim( mysql_real_escape_string($_POST["percentage".$i]) );
		
		$sql = "INSERT INTO 
				productFabric
				(productId, fabric, percentage, type)
				VALUES
				('$productId', '$fabric', '$percentage', '$type')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());	
		
		echo "<br/>".$sql."<br/>";
		
	}
	
	echo "added fabrics: <br/>";
	for($i=1; $i<=$addedFabrics; $i++){
		
		$type = strtolower( trim( mysql_real_escape_string($_POST["addedFabricType".$i]) ) );
		
		if($type==""){
			
			$type = "fabric";
		}
		
		$fabric = mysql_real_escape_string($_POST["addedFabric".$i]);
		$percentage = trim( mysql_real_escape_string($_POST["addedPercentage".$i]) );
		
		if(strcmp($percentage, "0")==0||$percentage==""){
		
			continue;
		}
		
		$sql = "INSERT INTO 
				productFabric
				(productId, fabric, percentage, type)
				VALUES
				('$productId', '$fabric', '$percentage', '$type')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());	
		
		echo "<br/>".$sql."<br/>";
	}
	
	echo "<p style='color: blue'>
			description: $description<br/>
		  frenchDescription: $frenchDescription<br/>
		  fd1 : $fd1<br/>
		  fd2: $fd2<br/>
		  braDoctorHelp : $braDoctorHelp<br />
		  cost: $cost<br />
		  price: $price<br/>
		  finalPrice: $finalPrice<br/>
		  canFinalPrice: $canFinalPrice<br/>
		  canPrice: $canPrice<br/>
		  priceType: $priceType<br/>
		  canPriceType: $canPriceType<br/><br/>
		  </p>";
	
	//now we're at the images part
	//if we're modifying, we want to make sure
	//that we delete everything from the images table
	//before inserting images all over again
	//Just a small recap: 
	//images are stored with a unique imageId
	//if a specific image is to be the product image
	//representing a certain color (eg: the green version of the bra)
	//then this means that, in the productColor table, were color=green
	//then imageId will = imageId of the image in the images table
	//if a color doesn't have such an image, then the imageId field in 
	//the productColor table will be set to -1
	
	//so, at this point, we already have all up to date colors stored
	//in the productColor table (though we don't have their potentially associated imageIds yet)
	//in the case of a modification
	//we just want to delete the rows from the images table corresponding to this product
	
	if(strcasecmp($action, "modify")==0){
	
		$sql = "DELETE
				FROM images
				WHERE productId = '$productId'";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	
	//now we can start getting the info for our images
	$numberOfImages = mysql_real_escape_string($_POST["numberOfImages"]);
	
	for($i=1; $i<$numberOfImages; $i++){
		
		$image = mysql_real_escape_string($_POST["image".$i]);
		
		$imageType = mysql_real_escape_string($_POST["imageType".$i]);
		
		if(strcasecmp($imageType, "none")==0){
			
			continue;
		}
		
		$generate = mysql_real_escape_string($_POST["generate".$i]);
		
		
		//we need to get the max imageId already stored
		//so that we get unique imageIds
		$sql = "SELECT max(imageId) as max
				FROM images";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(empty($row)){
			
			$imageId = 1;
		}
		else{
			
			$imageId = $row["max"]+1;
		}
		
		echo "the value of imageId is $imageId and imageType is $imageType<br/>";
		
		$sql = "INSERT INTO 
			images (productId, imageId, image, type)
			VALUES ('$productId', '$imageId', '$image', '$imageType')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());	

		echo "<br/>".$sql."<br/>";
		
		//now we update the appropriate color in the event that generate
		//is one of the colors
		//NOTE: DEFAULT IMAGES CAN'T HAVE SPECIAL COLORS ASSOC'D WITH THEM
		//BECAUSE THEY'D AUTOMATICALLY BE ASSOC'D WITH THE COLOR ANYWAY
		if(strcasecmp($imageType, "default")!=0&&strcasecmp($generate, "generate")!=0){
			
			//then we know that we must update the appropriate color
			//to have imageId=$imageId
			$sql = "UPDATE productColor
					SET imageId = '$imageId'
					WHERE color LIKE '$generate'
					AND productId = '$productId'";
			
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			echo "<br/>".$sql."<br/>";
		}
		
	}
	
	//now we do our inserting/updating
	//note that we're only doing updates here
	//since we already claimed our productId
	//in the event of a modification, a record with the productId already exists
	//clearly.
	//in the event of a copy or insert, at the beginning of this file, we claimed
	//the productId by inserting a new row into the table with this productId
	
	//note, we have a datePortion here, because we don't want to overwrite the value
	//for the createdDate for a modified product
	$datePortion = "";
	$date = date("Y-m-d");
	
	if(strcasecmp("modify", $action)!=0){
		
		$datePortion = "date='$date',";
	
	}
	
	$sql = "UPDATE product
			SET ".$datePortion."itemNumber = '$itemNumber', modelNumber = '$modelNumber', brand='$brand', productName=\"$productName\",
			title=\"$title\", description=\"$description\", frenchDescription=\"$frenchDescription\", braDoctorHelp='$bradoctorHelp',
			year='$year', season='$season', modifiedDate='$date', price='$price', finalPrice='$finalPrice', cost='$cost',
			canPrice='$canPrice', canFinalPrice='$canFinalPrice', priceType='$priceType', canPriceType='$canPriceType',
			itemType='$itemType', collection = '$collection', descWord = '$descWord', sizingFormat='$sizingFormat'
			WHERE productId = '$productId'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	echo "<br/>".$sql."<br/>";
	
?>