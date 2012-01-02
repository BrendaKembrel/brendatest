<?
	include("includes/headercad.php");
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
   
<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/ntlcurrent.css" />
		
		<meta name="description" content="DATABASE Brand Meta Description" />
		<meta name="keywords" content="DATABASE Brand Tags?" />
		
		<script type="text/javascript" src="scripts/tabber.js"></script>
		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="scripts/popup.js"></script>
		<script type="text/javascript" src="scripts/jquery.jcarousel.min.js"></script>
	<!--
	  jCarousel skin stylesheet
	-->
	<link rel="stylesheet" type="text/css" href="../skins/tango/skin.css" />
		
	<script type="text/javascript">
	
	<!--here are our global variables-->
	
		//at index i, we have colorArray[i]
		//being the color in question
		//and colorswatchesArray[i] being its
		//corresponding swatch
		var colorswatchesArray = new Array();
		var colorArray = new Array();
		
		//this is the same thing, but for
		//the add to cart thing for collections specifically
		var colorswatchesArrayColl = new Array();
		var colorArrayColl = new Array();
		
		var prodId = "";
		var iPath = "";
		
		jQuery(document).ready(function() {
			jQuery('#mycarousel').jcarousel({
				visible:2,
				scroll:2
			});
		});
		
		jQuery(document).ready(function() {
			jQuery('#mycarousel2').jcarousel({
				visible:2,
				scroll:2
			});
		});
		
		jQuery(document).ready(function() {
			
			
			
			getColorsForSize(false, prodId, iPath);
			
		});
		
		//this function is used so that the user sees in the header
		//the number of items that they have in their cart
		function refreshItemsInCart(){
					
			$.post(  "ajax/refreshItemsInCart.php", 
				{
					
				},
				function(responseText){ 
					
					$("#itemsInCart").html(responseText);
				},						
				"html"
			);
		
		}
		
		//this function is used to set the main product image
		//to have src = $image (this is for when you click the tiny
		//images at the bottom to display other views)
		function changeMainImage($image){
			
			$("#mainImage").attr('src', $image);
			$("#mainImage").attr('onclick', "enlargeImage('"+$image+"')");
		}
		
		//this function pops up a window with a 
		//larger image
		function enlargeImage($image){
			
			$("#contactArea").css({
			
				"width": "",
				"height": ""
			});
			
			$("#contactArea").html("<img src='"+$image+"' />");
			//centering with css
			centerPopup();
			//load popup
			loadPopup();
			
		}
		
		//this function will display to the user
		//the relevant colorswatches given
		//the size that they selected
		//$fromColl is a bool telling us whether
		//or not we're coming from the addToCartCollection.php: true
		//when calling this or if this is straight from the product page: false
		function getColorsForSize($fromColl, $productIdc, $imagePath){
		
			$("#contactArea").css({
			
				"width": "",
				"height": ""
			});
			
			var colorArray2 = Array();
			var colorswatchesArray2 = Array();
			
			//these are names of inputs/areas to be affected
			//that change depending on whether we're coming from
			//product2.php or an ajax call 
			var $sizeSectionName = "sizeSelection";
			var $colorSectionName = "colorSection";
			
			var $qtyName = "qty";
			var $swatchName = "swatch";
			var $colorName = "color";
			var $sizeName = "size";
			var $optionCounterName = "optionCounter";
			var $addToBagErrorName = "addToBagError";
			
			if($fromColl){
				colorArray2 = colorArrayColl;
				colorswatchesArray2 = colorswatchesArrayColl;
				$sizeSectionName = "sizeSelectionColl";
				$colorSectionName = "colorSectionColl";
				
				$qtyName = "qtyColl";
				$swatchName = "swatchColl";
				$colorName = "colorColl";
				$sizeName = "sizeColl";
				$optionCounterName = "optionCounterColl";
				$addToBagErrorName = "addToBagErrorColl";
				
				//this variable is used to call the addToBag()
				//function with the correct parameter so that we know
				//whether we're coming from addToCartCollection.php
				//or just product.php
				$addToBag = true;
				
			}
			else{
				
				colorArray2 = colorArray;
				colorswatchesArray2 = colorswatchesArray;
				
				$addToBag = false;
			}
			
			
			var tempArray = $("[name='"+$sizeSectionName+"']").val().split('*');
			var arrayOfIndices = tempArray[1].split(",");
			var $size = tempArray[0];
						
			arrayOfIndices.pop();
				
			$str= "";
			
			var $color = "";
			var $swatch = "";
			
			//we're going to use optionCounter to keep track of the number
			//of color options the user has for their size so that, when they add to cart
			//we'll be able to collect all info for the colors they chose along with their quantities
			//since they can only purchase one size at time, only one optionCounter value is required
			var $optionCounter = 0;
							
			$.each(arrayOfIndices, function($index, $elem){
					
					$color = colorArray2[$elem];
					$swatch = colorswatchesArray2[$elem];
					
					$str+="<div style='float:left;margin-bottom:5px;'>";
						$str+="<div style='float:left;'><img class='productctaswatch' src='"+$imagePath+"swatches/"+$swatch+"' /><input type='hidden' name='"+$swatchName+$optionCounter+"' value='"+$imagePath+"swatches/"+$swatch+"' /></div>";
						$str+="<div class='productctacolor'>"+$color+"</div><input type='hidden' name='"+$colorName+$optionCounter+"' value='"+$color+"' />";
					$str+="</div>";
					$str+="<div style='float:right;margin-bottom:5px;'>";
						$str+="<input type='text' class='productqtyinput' name='"+$qtyName+$optionCounter+"' />";
					$str+="</div>";
					$str+="<br style='clear:both;' />";
					
					$optionCounter = parseInt($optionCounter)+1;
			});
			
			$("#"+$colorSectionName).html($str+"<input type='image' src='images/addtobag2.jpg' onclick=\"addToBag("+$addToBag+", '"+$productIdc+"')\" />"+"<div id='"+$addToBagErrorName+"'></div>"+"<input type='hidden' name='"+$optionCounterName+"' value='"+$optionCounter+"' />"+"<input type='hidden' name='"+$sizeName+"' value='"+$size+"' />");
			
			
		}
		
		
		//this function is used to add the current product to bag (cart)
		//$fromColl tells us (true) if we came from addToCartCollection.php
		//or if we came from product.php (false) at which point some input names
		//change
		function addToBag($fromColl, $productIdc){
			
			var $qtyName = "qty";
			var $swatchName = "swatch";
			var $colorName = "color";
			var $sizeName = "size";
			var $optionCounterName = "optionCounter";
			var $addToBagErrorName = "addToBagError";
			
			if($fromColl){
			
				$qtyName = "qtyColl";
				$swatchName = "swatchColl";
				$colorName = "colorColl";
				$sizeName = "sizeColl";
				$optionCounterName = "optionCounterColl";
				$addToBagErrorName = "addToBagErrorColl";
			}
			
			
			var $optionCounter = $("[name='"+$optionCounterName+"']").val();
			var $size = $("[name='"+$sizeName+"']").val();
			
			var $color = "";
			var $qty = 0;
			
			//these will be some comma separated strings
			//we will be sending over the server to make one
			//single ajax call
			var $colors = "";
			var $swatches = "";
			var $qties = "";
			
			//this is to check if the person actually added anything to their bag or not
			var $successfullyAddedToBag = false;
			
			for($i=0; $i<$optionCounter; $i++){
				
				$qty = $("[name='"+$qtyName+$i+"']").val();
				$color = $("[name='"+$colorName+$i+"']").val();
				$swatch = $("[name='"+$swatchName+$i+"']").val();
				
				//the third part of this or clause
				//checks if it's NOT an integer with a positive value
				if($qty==0||$qty==""||!$qty.match('^(0|[1-9][0-9]*)$')){
					//do nothing, we don't want these values
				}
				else{
					
					$successfullyAddedToBag = true;
					//we know the user purchased this
					$colors += $color+",";
					$swatches += $swatch+",";
					$qties += $qty+",";
					
				}
			}//end for
			
			$("#"+$addToBagErrorName).html("");
			
			if(!$successfullyAddedToBag||$size=="Select Your Size"){
				
				if($size=="Select Your Size"){
					
					$("#"+$addToBagErrorName).html("<span style='color:red;font-style:italic;font-size:12px;'>You must specify a size before adding to your bag.</span>");
				}
				else{
					$("#"+$addToBagErrorName).html("<span style='color:red;font-style:italic;font-size:12px;'>You did not add anything to your bag.</span>");
				}
				
			}
			else{
				
				$("#contactArea").html("");
				
				$("#contactArea").css({
				
					"width": "400",
					"height": "500"
				});
				
				//now we do our ajax call
				
				$.post( "ajax/addToCart.php", 
						{	
							productId : $productIdc,
							swatches: $swatches,
							colors: $colors,
							qties: $qties,
							size: $size
							
						},  
						function(responseText){  
						
							$("#contactArea").append(responseText);
							refreshItemsInCart();							
						},  
						"html" 
					);
					
				centerPopup();
				loadPopup();	
				
				//now we set the qty values to empty for the user
				for($i=0; $i<$optionCounter; $i++){
					
					$qty = $("[name='"+$qtyName+$i+"']").val("");
					
				}
			}
			
			
		}
		
		//this function is used to add to bag an item from the collection
		//displayed in the carousel below
		//$image is the default image to display
		//and $imagePathc is the path to any image, so that we can easily display 
		//colorswatches without recalculating what the image path is
		function addToBagCollection($productIdc, $currency, $imagePathc, $image){
			
			$("#contactArea").css({
			
				"width": "",
				"height": ""
			});
			
			$.post( "ajax/addToCartCollection.php", 
				{	
					productId : $productIdc,
					currency: $currency,
					imagePath: $imagePathc,
					image: $image
				},  
				function(responseText){  
					
					$("#contactArea").html(responseText);  
					centerPopup();
					getColorsForSize(true, $productIdc, $imagePathc);
					loadPopup();
				},  
				"html" 
			);
		
		}
		
	</script>
	
	<?
		
		
		include("includes/connect.php");
		include("includes/functions/sitewideFunctions.php");
		
		//NOTES TO ERASE LATER
		//MAKE SURE $sqlColor 
		//CHECKS THAT THE ACTIVATION DATE != '0000-00-00'
		//ALSO FIGURE OUT WHAT TO DO IF A) ITEMNUMBER! EXIST
		//B) NO COLORS ARE ACTIVE
		//META DESC AND METAKEYS
		
		//here is the initial prep section
		//to allow us to get the specific product's information
		$itemNumber = mysql_real_escape_string($_GET["id"]);
		
		
		//the defaultColor is the parameter used in the event that
		//we have several different pictures for different colors
		//and we want to set up different pages for these different colors
		
		//the way defaultColor works is that, in the productColor table,
		//there is an imageId associated with every color.  If the imageId is -1,
		//then we know there isn't any special image associated with that color.
		//Otherwise, the imageId stored is the one we use as the default
		//for the page where defaultColor=that color
		$defaultColor = "";
		$imageIdAssociatedWithDefaultColor = "-1";
		
		if(isset($_GET["defaultColor"])){
			
			$defaultColor = mysql_real_escape_string($_GET["defaultColor"]);
		}
		
		//we need to know the currency we'll be working with
		$currency = "";
	
		if(isset($_SESSION["currency"]) ){
			
			$currency = $_SESSION["currency"];
		}
		
		if($currency==""){
		
			$currency = "USD";
		}
		
		$sql = "SELECT *
				FROM product
				WHERE itemNumber LIKE '$itemNumber'";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(empty($row)){
			
			//product does not exist
		}
		else{
			
			//general info
			$productId = $row["productId"];
			$itemType = $row["itemType"];
			$sizingFormat = $row["sizingFormat"];
			
			$title = stripslashes( $row["title"] );
			$productName = stripslashes( $row["productName"] );
		
			$year = $row["year"];
			$season = strtolower( $row["season"] );
			$brand = $row["brand"];
			$collection = $row["collection"];
			
			//pricing info
			$priceInfoArray = getPriceInfo($con, $row, $currency);
			
			//product details
			$description = stripslashes( $row["description"] );
			$frenchDescription = stripslashes( $row["frencDescription"] );
			$braDoctorHelp = stripslashes( $row["braDoctorHelp"] );
			
			//next we will set up the fabric array
			
			$fabricArray = array();
			
			$sqlFab = "SELECT *
					FROM productFabric
					WHERE productId = '$productId'";
					
			$resultFab = mysql_query($sqlFab, $con) or die(mysql_error());
			
			//the fabricArray will be of the form:
			//fabricArray[type1] = fabric1: percentage1, fabric2: percentage2
			//where type is fabric, lining, lace, mesh, etc
			while($rowFab = mysql_fetch_array($resultFab, MYSQL_ASSOC) ){
				
				$type = $rowFab["type"];
				$fabric = $rowFab["fabric"];
				$percentage = $rowFab["percentage"];
				
				if(array_key_exists($type, $fabricArray) ){
					
					$fabricArray[$type] .= ", ".$percentage."% ".$fabric;
				}
				else{
					
					$fabricArray[$type] = $percentage."% ".$fabric;
				}
				
			}
			
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
			
			//NOTE, THE REASON WE CAN ENSURE THAT THE SIZES
			//ARE IN THE RIGHT ORDER (eg: Small comes before Medium
			//even though alphabetically speaking Medium comes before Small)
			//is because of how the sizes were initially inserted into the database
			//I first make an array of sizes so that they are in this order
			//(they actually end up in reverse correct order, so when we loop through
			//the sizes array, we will go backwards).  The only exception to this rule
			//is if itemType=1 (bras), at which point we're interested in the alphabetical order
			//in DESC fashion because we will still be reading the sizesArray in reverse since we have to
			//do so for all other cases
		
			$orderBy = "";
			
			if($sizingFormat=="1"){
				
				//we do order desc because the sizesArray
				//will be accessed in reverse order
				$orderBy = "ORDER BY size DESC";
			}
			
			$sqlSizes = "SELECT size
						FROM productSizeColor
						WHERE size IN 
						(SELECT size
						 FROM sizeDefault
						 WHERE typeId = '$sizingFormat')
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
			
			//we're going to use this counter here to make sure 
			//we have at least one active color, and to keep track
			//of the indices
			$countColors = 0;
			
			while ( $rowColor = mysql_fetch_array($resultColor, MYSQL_ASSOC) ){
				
				$color = $rowColor["color"];
				$cid = $rowColor["cid"];
				
				if(strcasecmp($color, $defaultColor)==0){
					
					$imageIdAssociatedWithDefaultColor = $rowColor["imageId"];
				}
				
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
			echo 'colorArray = new Array("', join($colorArray ,'","'), '");';
			echo 'colorswatchesArray = new Array("', join($colorswatchesArray ,'","'), '");';
			echo "</script>";
			
			if($countColors==0){
				
				//then we know that no colors
				//are active, so technically this product
				//isn't active
			
			}
			
			//here we will set up the path to be used for images
			
			$imagePath = "../../en/images/";
			
			$brandPart = str_replace(" ", "-", $brand);
			
			if($year!=""&&$year!=0){
			
				$imagePath .= $year."/";
			}
			
			if($season!=""&&strcasecmp($season, "none")!=0){
			
				$imagePath .= $season."/";
			}
			
			$imagePath .= $brandPart."/";
			
			
			//We will store our image in an image array
			//where $imagesArray[0] is the default image
			
			$imageArray = array();
			
			$sqlImg = "SELECT image, type
						FROM images
						WHERE productId = '$productId'";
			$resultImg = mysql_query($sqlImg, $con) or die(mysql_error());
			
			//imageCounter will be used to add images to the imageArray
			//so that we always leave index at 0 clear for the default image
			$imageCounter = 1;
			
			while( $rowImg = mysql_fetch_array($resultImg, MYSQL_ASSOC) ){
			
				$image = $rowImg["image"];
				$type = $rowImg["type"];
				$imageId = $rowImg["imageId"];
				
				if(strcasecmp($imageIdAssociatedWithDefaultColor, $imageId)==0){
					
					$imageArray[0] = $image;
					
				}
				elseif(strcasecmp($type, "default")==0&& ($imageArray[0]==""||$imageArray[0]==null) ){
					
					$imageArray[0] = $image;
				}
				else{
					
					$imageArray[$imageCounter] = $image;
					$imageCounter++;
				}
			
			}
		}
		
		//we need to pass some vars over to the javascript
		echo "<script type='text/javascript'>
				iPath = '".$imagePath."';
				prodId = '".$productId."';
			 </script>";
				
		
	?>		
		
		<title><? echo $title?></title>
		
	</head>

	<body>
				 
	
		<div id="sitewrapper">
		
			<? include("includes/header.php"); ?>
			<? include("includes/menu.php"); ?>
			
			<div id="sitecontent">
			
				<div class="productleftcolwrapper">
				
					<div>
					
						<img class='productmainimage' id='mainImage' style='cursor: pointer;' onclick="enlargeImage('<?echo $imagePath.$image?>')" src="<? echo $imagePath.$image?>" />
						
						<?
							
							//this is the section where we display all colorswatches which are w: 90, h:20
							
							//the swatchCounter will be used to keep track
							//so that any middle element will have a class
							//called productcenterswatchimg
							$swatchCounter = 1;
							
							$colorSwatches = "";
							
							foreach($colorswatchesArray as $swatch){
								
								$class = "";
								$class1 = "productsideswatch";
								
								if($swatchCounter%3==2){
									
									$class="productcenterswatchimg";
									$class1 = "productcenterswatch";
								}
								
								//each row of colourwatches goes within a div
								if($swatchCounter%3==1){
									$colorSwatches.="<div>";
								}
								
								$colorSwatches.="<div class='".$class1."'><img class='".$class."' src='".$imagePath."swatches/".$swatch."' width='90px' height='20px' /></div>";
								
								
								if($swatchCounter%3==0){
									
									$colorSwatches.="</div><br style='clear:both;' />";
								}
								
								$swatchCounter ++;
							}
							
							//if we didn't end up with a number of colourswatches that is divisible by 3,
							//then we're going to need to close the div and do a br style clear both
							//because otherwise we'll have unfinished business
							if($swatchCounter%3!=1){
							
								$colorSwatches.="</div><br style='clear:both;' />";
							}
							echo $colorSwatches;
							
						
						//this is the section where we will display additional
						//product views
						//we will only have a max of 3
						
						if(count($imageArray)>1){
							
							$otherImages = "";
							
							for($i=0; $i<3; $i++){
								
								$class = "productsideswatchimg";
								$class1 = "productsideswatch";
								
								//note, if we have 3 elements, then
								//we want the center image to have a different class
								if($i==1){
									
									$class = "productcenterswatchimg";
									$class1 = "productcenterswatch";
								}
								
								if($imageArray[$i]==""||$imageArray[$i]==null){
									
									break;
								}
								
								$image = $imagePath.$imageArray[$i];
								
								$otherImages.="<div class='".$class1."'><img class='".$class."' src='$image' onclick=\"changeMainImage('$image')\" style='cursor: pointer;' /></div>";
							
							}
							
							echo $otherImages."<br style='clear:both;' />";
						}
						
						?>
					
						
					</div>
					
					
					
					<?
					
				if($braDoctorHelp!=""){
					
						echo "
					<div class='productleftcolheader'>
					<p class='sitesectionheadertext'>Bra Doctor's Advice</p>
					
					</div>
						
					<div class='productleftcolcontent'>
					
						<p class='productleftcoltext'>".
						
							$braDoctorHelp

						."</p>
					
					</div>";
						
						
				}
					
					?>
					
					<div class="productleftcolheader">
					
					<p class="sitesectionheadertext">Related Categories</p>
					
					</div>
					
					<div class="productleftcolcontent">
					
						<div class="productleftcollinkswrapper">
						
							<a href="#" class="productleftcollink">Push Up Bras</a><br />
							<a href="#" class="productleftcollink">Lacy Bras</a><br />
							<a href="#" class="productleftcollink">Demi Cup Bras</a><br />
							
						</div>
					
					</div>
					
				</div>
				
				<div class="productrightcolwrapper">
				
					<div class="productrightcoltitlewrapper">
					
						<p class="producttitle"><? echo $productName ?></p>
					
					</div>
					
					<div class="productsocialwrapper">
					
						<img src="images/socialwidgetplaceholder.jpg" />
					
					</div>
					
					<br style="clear:both;" />
					
					<div class="producttabberwrapper">
						<div class="tabber">
							
							<div class="tabbertab">
							
								<h2>Description</h2>
								
								<p class="productdescription"><? echo $description ?></p>
								
								<p class="productfabriccontent">
									<?
									$fabricString = "Fabric Content:  ";
									//first we start with fabric (we want that to come before lining
									//or mesh or whatever)
									if(array_key_exists("fabric", $fabricArray)){
										
										$fabricString.=$fabricArray["fabric"];
									}
									
									
									
									foreach($fabricArray as $type=>$fabStr){
										
										if(strcasecmp($type, "fabric")==0){
											continue;//we already dealt with this
										}
										
										$fabricString.="<br/>".ucfirst($type).":  $fabStr";
										
									}
									
									if(!empty($fabricArray)){
									
										echo $fabricString;
									}
									
									?>
								</p>
								
							</div>
							
							<div class="tabbertab">
							
								<h2>Features</h2>
								
								<ul class="productfeaturesul">
								
									<li class="productfeaturesli">Ninjas are cool</li>
									<li class="productfeaturesli">Pirates are also cool, but slightly less so</li>
									<li class="productfeaturesli">This product is also quite neat</li>
									<li class="productfeaturesli">P.S. Brenda likes Knights (but not crusaders) ... (though they have cool uniforms) ... (she thinks that covered it).</li>
									
								</ul>
								
							</div>
							
						</div>
					</div>
					
					<div style="float:left;">
					
						<div class="productpricewrapper">
							
							<?
								//this is where we format the price
								//using a special function in the sitewideFunctions.php file
								
								//we need to make an array of classes for the price
								//array[0] = regular price, array[1] = intermediate price (if applicable), array[3] = final discounted price (if applicable)
								
								$formatPriceArray = array("productreg", "productintermediateprice", "productfinalprice");
								
								
								echo formatPrice($currency, $priceInfoArray, $formatPriceArray, false);
							?>
							
							
						
						</div>
						
						<div class="productsizechartwrapper"> 
					
							<div style="margin-bottom:2px;"><img src="images/viewsizechart.png" /></div>
							<div><img src="images/contactbradoctor.png" /></div>
							
						</div>
						
					</div>
					
					<div class="productctawrapper">
					
							<?
								
							
							?>
							<select onchange="getColorsForSize(false, '<?echo $productId?>', '<? echo $imagePath ?>')" name='sizeSelection' class="productselectyoursize">
																
								<?
									$sizeSelection = "";
									
									//we go from the bottom up because the order is reverse
									foreach($sizesArray as $size=>$col){
										
										if($col!=""&&$col!=null&&$size!=""){
											//then we know at least one color exists for this size
											//we're also ensuring we don't have an empty size
											$sizeSelection="<option value='".$size."*".$col."'>".ucwords($size)."</option>".$sizeSelection;
										}
										
									}
									
									echo $sizeSelection;
								?>
								
							</select>
						
						<br style="clear:both;" />
						
						<div style="float:left;margin:0 0 4px 4px;">
						
							<p class="productcolorqtytitle">
							
								Available in...
							
							</p>
							
						</div>
						
						<div style="float:right;text-align:right;width:68px;margin:0 4px 4px 0;">
						
							<p class="productcolorqtytitle">
							
								Quantity
							
							</p>
							
						</div>
						
						<br style="clear:both;" />
						
						<div id='colorSection' style="margin:0 0 4px 0;">
						
						
						</div>
						
						
					</div>
					
					<br style="clear:both;" />
					
					
					<div class="productlowertabberwrapper">
						<div class="tabber">
							
							<?	
								
								//here's the part for other pieces in the collection
								//note that we don't want to have a Collection tab unless
								//we are sure that products exist
								
								
								
								$emptyCollection = true;
								
								$sql = "SELECT *
										FROM product
										WHERE collection LIKE '$collection'
										AND brand LIKE '$brand'
										AND productId != '$productId'";
										
								$result = mysql_query($sql, $con) or die(mysql_error);
								
								//we only search for products in the collection
								//other than the current one
								
								$str = "";
								
								while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
									
									//clearly if we enter this loop, there's at least
									//one other piece in this collection
									$emptyCollection = false;
									
									//NOTE: although we may have only one productId
									//for a given product, it is possible that we have
									//an image for the green version of the product,
									//one for the red version, etc.  So, we must here
									//present all versions to the user should they exist
									//with their very own default image
									//we will store images in an array to be used
									//$possibleDefaultColorsArray[color] = image
									//$possibleDefaultColorsArray["all"] = actual default image
									$possibleDefaultColorsArray = array();
									
									//first we get the productId, brand, and the product name
									$productIdc = $row["productId"];
									$brandc = $row["brand"];
									$productNamec = stripslashes( $row["productName"] );
									$seasonc = stripslashes( $row["season"] );
									$yearc = stripslashes( $row["year"] );
									$itemNumberc = $row["itemNumber"];
									
									//this portion has to do with formatting the price
									//we have a class for the regular price,
									//the potential intermediate price
									//and finally, the final discounted price, if applicable
									$formatPriceArray = array("productlowertablink1", "productlowertablink4", "productlowertablink3");
									
									//getPriceInfo will get our prices for us
									$priceString = formatPrice($currency, getPriceInfo($con, $row, $currency), $formatPriceArray, true);
									
									//before we begin, we know that they all have their price in common
									//so we store the price display in a string called priceString
									
									
									$imagePathc = "../../en/images/";
									
									$brandPartc = str_replace(" ", "-", $brandc);
			
									if($yearc!=""&&$yearc!=0){
									
										$imagePathc .= $yearc."/";
									}
									
									if($seasonc!=""&&strcasecmp($seasonc, "none")!=0){
									
										$imagePathc .= $seasonc."/";
									}
									
									$imagePathc .= $brandPartc."/";
									
									$imagePathc = strtolower($imagePathc);
									
									//now we set the brand to be ucwords so each
									//word is capitalized in the brand
									$brandc = ucwords($brandc);
									
									//here we store the default image for the key "all"
									$sqlImg = "SELECT * 	
											  FROM images
											  WHERE productId = '$productIdc'
											  AND type LIKE 'default'";
									$resultImg = mysql_query($sqlImg, $con) or die(mysql_error());
									
									$rowImg = mysql_fetch_array($resultImg, MYSQL_ASSOC);
									
									$image = $rowImg["image"];
									
									$possibleDefaultColorsArray["all"] = $imagePathc.$image;
									
									
									
									//we first find all colors for the product
									//that are currently active
									//and check if they have a default image specifically for this color
									$sqlColor = "SELECT *
												FROM productColor
												WHERE productId = '$productIdc'
												AND activationDate <= '$date'
												AND ( deactivationDate > '$date'
												OR deactivationDate LIKE '0000-00-00')";
												
									$resultColor = mysql_query($sqlColor, $con) or die(mysql_error());
									
									while ( $rowColor = mysql_fetch_array($resultColor, MYSQL_ASSOC) ){
										
										$color = $rowColor["color"];
										$cid = $rowColor["cid"];
										$imageId = $rowColor["imageId"];
										
										if($imageId!="-1"){
											
											//then this means that we have a specific image for this color
											$sqlImg = "SELECT * 
													   FROM images  
													   WHERE imageId = '$imageId'";
											$resultImg = mysql_query($sqlImg, $con) or die(mysql_error());
											
											$rowImg = mysql_fetch_array($resultImg, MYSQL_ASSOC);
											
											$image = $rowImg["image"];
											
											$possibleDefaultColorsArray[$color] = $imagePathc.$image;
										}
									}
									
									//now we loop through the possibleDefaultColorsArray
									foreach($possibleDefaultColorsArray as $color=>$image){
										
										$link = "product2.php?id=$itemNumberc";
										
										if(strcasecmp($color, "all")!=0){
											
											//then we know we're dealing with one of those
											//examples where we're not using the default image
											//but instead one specific to the current color
											
											$link.="&defaultColor=".$color;
											
												
										}
										
										$str.="
										<li>
											<a href=".$link." style = 'text-decoration: none;'>
											<div class='productlowertabimg'><img src='".$image."' style='width: 124px;height:174px;' /></div>
											</a>
											
											<div class='productlowertabtext'>
											
												<p class='productlowertablink1'>".$productNamec."</p>
												<p class='productlowertablink2'>".$brandc."</p>".
												$priceString."												
												<img style='cursor: pointer;' onclick=\"addToBagCollection('$productIdc', '$currency', '$imagePathc', '$image')\" src='images/addtobag3.jpg' />
											
											</div>
											
										</li>";
									
									}
								}
							
							//so now we display the collection part
							//as long as we don't have an empty collection
							if(!$emptyCollection){
							
							echo "
							<div class='tabbertab'>
							
								<h2>Collection</h2>
								
								<ul id='mycarousel' class='jcarousel-skin-tango'>
								
									".$str."
								
									
								</ul>
								
							</div>";
							
							}
							
							?>
							<div class="tabbertab">
							
								<h2>You may also like...</h2>
								
								<ul id="mycarousel2" class="jcarousel-skin-tango">
																		
									<li>
									
										<div class="productlowertabimg"><img src="images/placeholderforpackagedeals.jpg" /></div>
										<div class="productlowertabtext">
										
											<p class="productlowertablink1">Product Name</p>
											<p class="productlowertablink2">Brand</p>
											<p class="productlowertablink1">Reg: xx.xx USD</p>
											<p class="productlowertablink3">Sale: xx.xx USD</p>
											
											<img src="images/addtobag3.jpg" />
										
										</div>
										
									</li><li>
									
										<div class="productlowertabimg"><img src="images/placeholderforpackagedeals.jpg" /></div>
										<div class="productlowertabtext">
										
											<p class="productlowertablink1">Product Name</p>
											<p class="productlowertablink2">Brand</p>
											<p class="productlowertablink1">Reg: xx.xx USD</p>
											<p class="productlowertablink3">Sale: xx.xx USD</p>
											
											<img src="images/addtobag3.jpg" />
										
										</div>
										
									</li><li>
									
										<div class="productlowertabimg"><img src="images/placeholderforpackagedeals.jpg" /></div>
										<div class="productlowertabtext">
										
											<p class="productlowertablink1">Product Name</p>
											<p class="productlowertablink2">Brand</p>
											<p class="productlowertablink1">Reg: xx.xx USD</p>
											<p class="productlowertablink3">Sale: xx.xx USD</p>
											
											<img src="images/addtobag3.jpg" />
										
										</div>
										
									</li><li>
									
										<div class="productlowertabimg"><img src="images/placeholderforpackagedeals.jpg" /></div>
										<div class="productlowertabtext">
										
											<p class="productlowertablink1">Product Name</p>
											<p class="productlowertablink2">Brand</p>
											<p class="productlowertablink1">Reg: xx.xx USD</p>
											<p class="productlowertablink3">Sale: xx.xx USD</p>
											
											<img src="images/addtobag3.jpg" />
										
										</div>
										
									</li><li>
									
										<div class="productlowertabimg"><img src="images/placeholderforpackagedeals.jpg" /></div>
										<div class="productlowertabtext">
										
											<p class="productlowertablink1">Product Name</p>
											<p class="productlowertablink2">Brand</p>
											<p class="productlowertablink1">Reg: xx.xx USD</p>
											<p class="productlowertablink3">Sale: xx.xx USD</p>
											
											<img src="images/addtobag3.jpg" />
										
										</div>
										
									</li>
								</ul>
								
							</div>
							
						</div>
					</div>
						
				</div>
					
				
				
			
			</div>
			
			<br style="clear:both;" />
			
			<? include("includes/footer.php"); ?>
		
		</div>
		<!--this is the area where pop ups will happen
		the background is what becomes opaque and black;
		contactArea is what gets filled with what gets popped up
		the x is for the closing button
		-->
		
		<div id='popupContact'>
			<div id='popupContactClose'>x</div>
			<div id='contactArea'>
			
			
			</div>
			
			<div id='popupContactClose2'>CLOSE</div>
		</div>
		<div id="backgroundPopup"></div>

	</body>
</html>