<?
	include("../includes/functions/shoppingCartFunctions.php");
	include("../includes/connect.php");
	
	$productId = $_POST["productId"];
	$currency = $_POST["currency"];
	
	//image here is the default product image
	//(so if the user clicked on the green version of
	//a product, for example, $image= the green pic)
	$image = $_POST["image"];
	
	//imagePath is the path to any image of interest,
	//such as colourswatches, so we don't have to recalculate it
	$imagePath = $_POST["imagePath"];
	
	$sql = "SELECT *
			FROM product
			WHERE productId = '$productId'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	
	$itemType = $row["itemType"];
	
	//this part is for dealing with the price

	$formatPriceArray = array("productreg", "productintermediateprice", "productfinalprice");
	
	
	$priceString = formatPrice($currency, getPriceInfo($con, $row, $currency), $formatPriceArray, false);
	
	//now we deal with keeping track of sizes that exist for active colors
	//here we will set up the colorArray
	$colorArray = array();
	
	//the next array is where we will store colorswatches
	$colorswatchesArray = array();
	
	//the next array is where we will store all sizes
	//associated with this product
	//note we only want the sizes associated with the
	//current selected itemType of the product
	$sizesArray = array();
	
	//sizesArray will have the form sizesArray[size]  = index of matching color1 in colorArray and colorswatchesArray,index of matching color2, etc
	//where the colors are active
	
	$orderBy = "";
			
	if($itemType=="1"){
		
		//we do order desc because the sizesArray
		//will be accessed in reverse order
		$orderBy = "ORDER BY size DESC";
	}
	
	$sqlSizes = "SELECT size
				FROM productSizeColor
				WHERE size IN 
				(SELECT size
				 FROM sizeDefault
				 WHERE typeId = '$itemType')
				 $orderBy";
	
	$resultSizes = mysql_query($sqlSizes, $con) or die(mysql_error());
	
	while( $rowSizes = mysql_fetch_array($resultSizes, MYSQL_ASSOC) ){
		
		$size = $rowSizes["size"];
		
		if(!array_key_exists($size, $sizesArray)){
		
			$sizesArray[$size] = "";
		}
	
	}
	
	//we want an place in the array for the select option
	$sizesArray["Select Your Size"] = "";
	
	//first we are only interested in currently
	//active colors, so we must compare their dates
	//to today's date:  
	$date = date("Y-m-d");
	
	$sqlColor = "SELECT *
				FROM productColor
				WHERE productId = '$productId'
				AND activationDate <= '$date'
				AND ( deactivationDate > '$date'
				OR deactivationDate LIKE '0000-00-00')";
				
	$resultColor = mysql_query($sqlColor, $con) or die(mysql_error());
	
	//we're going to use this counter here to keep track of the index
	$countColors = 0;
	
	while ( $rowColor = mysql_fetch_array($resultColor, MYSQL_ASSOC) ){
		
		$color = $rowColor["color"];
		$cid = $rowColor["cid"];
		
		array_push($colorArray, $color);
		array_push($colorswatchesArray, $rowColor["image"]);
		
		//now, we want to check if this color is associated
		//with a some sizes in the sizesArray
		$sqlSize = "SELECT size
					FROM productSizeColor
					WHERE cid = '$cid'";
		
		$resultSize = mysql_query($sqlSize, $con) or die(mysql_error());
		
		while( $rowSize = ( mysql_fetch_array($resultSize, MYSQL_ASSOC) ) ){
			
			$size = $rowSize["size"];
			
			if($sizesArray[$size]==""||$sizesArray[$size]==null){
				$sizesArray[$size] = $countColors.",";
			}
			else{
				$sizesArray[$size] .= $countColors.",";
			}
			
		}
		
		//we want every color to display for the Select Your Size option
		//so we will add every color to it
		if($sizesArray["Select Your Size"]==""){
		
			$sizesArray["Select Your Size"] = $countColors.",";
			
		}
		else{
			$sizesArray["Select Your Size"] .= $countColors.",";
		}
				
		$countColors++;
	}
	
	//now we prepare our equivalent javascript arrays
	echo "<script type='text/javascript'>";
	echo 'colorArrayColl = new Array("', join($colorArray ,'","'), '");';
	echo 'colorswatchesArrayColl = new Array("', join($colorswatchesArray ,'","'), '");';
	echo "</script>";
	
	$str="
	
	<div style=''>
		<div style='float:left;margin:5px;'>
			<img class='productmainimage' src='".$image."' />
		</div>
		<div style='float:left;margin:5px;'>"
			.$priceString.
			"<div class='productctawrapper'>
					
						<select onchange=\"getColorsForSize(true, '$productId', '$imagePath' )\" name='sizeSelectionColl' class='productselectyoursize'>";
							
								$sizeSelection = "";
								
								//we go from the bottom up because the order is reverse
								foreach($sizesArray as $size=>$col){
									
									if($col!=""&&$col!=null&&$size!=""){
										//then we know at least one color exists for this size
										//we're also ensuring we don't have an empty size
										$sizeSelection="<option value='".$size."*".$col."'>".ucwords($size)."</option>".$sizeSelection;
									}
									
								}
							
								$str.=
								 $sizeSelection
							
						."</select>
						
						
						
						<br style='clear:both;' />
						
						<div style='float:left;margin:0 0 4px 4px;'>
						
							<p class='productcolorqtytitle'>
							
								Available in...
							
							</p>
							
						</div>
						
						<div style='float:right;text-align:right;width:68px;margin:0 4px 4px 0;'>
						
							<p class='productcolorqtytitle'>
							
								Quantity
							
							</p>
							
						</div>
						
						<br style='clear:both;' />
						
						<div id='colorSectionColl' style='margin:0 0 4px 0;'>
						
						
						</div>
						
						
			</div>
					
			<br style='clear:both;' />
			
		</div>
	</div>
	";
	
	echo $str;
	
?>