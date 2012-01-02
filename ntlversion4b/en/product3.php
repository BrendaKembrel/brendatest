<?
	include("includes/headercad.php");
	//UNFINISHED: 
	//BRA DOCTOR HELP: PUT CONTACT US THINGY
	//RELATED CATEGORIES
	//PRICE
	//size chart
	//enlarge image, especially for colors
	//RELATED PRODUCTS
	//colors, collection etc should maybe depend on what the activation status of the product is
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
   
<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/ntlcurrent.css" />
		
		<meta name="description" content="DATABASE Brand Meta Description" />
		<meta name="keywords" content="DATABASE Brand Tags?" />
		
		<script type="text/javascript" src="scripts/mainPageCartStuff.js"></script>
		<script type="text/javascript" src="scripts/tabber.js"></script>
		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="scripts/popup.js"></script>
		<script type="text/javascript" src="scripts/jquery.jcarousel.min.js"></script>
		
	<!--
	  jCarousel skin stylesheet
	-->
	<link rel="stylesheet" type="text/css" href="../skins/tango/skin.css" />
	
	<script type='text/javascript'>
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
			
			getColorsForSize(false, prodId, defaultCol, iPath);
			
		});
	</script>
	
	<?
		include("includes/connect.php");
		
		//classes includes
		include("classes/utilityClass.php");
		include("classes/promoClass.php");
		include("classes/priceClass.php");
		include("classes/colorClass.php");
		include("classes/fabricClass.php");
		include("classes/collectionClass.php");
		include("classes/productClass.php");
		
		//here is the initial prep section
		//to allow us to get the specific product's information
		$itemNumber = mysql_real_escape_string($_GET["id"]);
		
		$defaultColor = "";
		
		if(isset($_GET["defaultColor"])){
			
			$defaultColor = mysql_real_escape_string($_GET["defaultColor"]);
		}
		
		//$productStatuses is the current status 
		//of our desired products.  It's a comma-separated list
		//with released,retired,unreleased
		$productStatuses = "released";
		
		$arrayOfActivationStatuses = array($productStatuses);
		
		//this is the currency we're purchasing in currently
		$currency = "CAD";
		
		if(isset($_SESSION["currency"])){
		
			$currency = $_SESSION["currency"];
		}
		
		//now, we create a product based on the itemNumber/defaultColor combo the user gave
		$product = new Product($itemNumber, $defaultColor);
		
		if( $product->getProductId()==-1 ){
			
			//product does not exist
		}
		else{
			
			//general info
			$productId = $product->getProductId();
			
			$title = $product->getTitle();
			$productName = $product->getProductName();
		
			$year = $product->getYear();
			$season = $product->getSeason();
			$brand = $product->getBrand();
			$collection = $product->getCollection();
			
			
			//product details
			$description = $product->getDescription();
			$frenchDescription = $product->getFrenchDescription();
			$braDoctorHelp = $product->getBraDoctorHelp();
			
			//next we will set up the fabric array
			//which works as follows:
			//$fabricArray[fabric] = array(fabricobj1, fabricobj2)
			//$fabricArray[lining] = array(fabricobj3) 
			//the fabric objects contain all info related to a fabric
			$fabricArray = array();
			
			$fabricArray = $product->getOrganizedFabrics();
			
			//now we want to get all colors in
			//which the product is currently available
			$colorsArray = $product->getColorsByActivation($arrayOfActivationStatuses);
			
			//the following is the path to every image
			$imagePath = "http://nowthatslingerie.com/en/images/";
			
			if($year!=""){
				
				$imagePath.=$year."/";
			}
			
			if($season!=""&&$season!="none"){
			
				$imagePath.=$season."/";
			}
			
			$imagePath.= str_replace(" ", "-", $brand)."/";
			
			$image = $imagePath.$product->getImage();
			
			//here we will set up the array of strings
			//representing the color names
			$colornamesArray = array();
			
			//the next array is where we will store colorswatches
			$colorswatchesArray = array();
			
			//the next array is where we will store all sizes
			//associated with this product
			//note we only want the sizes associated with the
			//current selected itemType of the product
			$sizesArray = array();
			
			//sizesArray will have the form sizesArray[size]  = index of matching color1 in colornamesArray and colorswatchesArray,index of matching color2, etc
			//where the colors have the statuses defined by arrayOfActivationStatuses
			$arrayOfDesiredArrays = $product->getDesiredSizeColorArrays($arrayOfActivationStatuses);
			
			$sizesArray = $arrayOfDesiredArrays[0];
			$colorswatchesArray = $arrayOfDesiredArrays[1];
			$colornamesArray = $arrayOfDesiredArrays[2];
			
		}
		
		//now we prepare our equivalent javascript arrays
		echo "<script type='text/javascript'>";
		echo 'colorArray = new Array("', join($colornamesArray ,'","'), '");';
		echo 'colorswatchesArray = new Array("', join($colorswatchesArray ,'","'), '");';
		echo "</script>";
		
		//we need to pass some vars over to the javascript
		echo "<script type='text/javascript'>
				iPath = '".$imagePath."';
				prodId = '".$productId."';
				defaultCol = '".$defaultColor."';
				productStatuses= '".$productStatuses."';
			 </script>";
		
	?>
	
		<title><? echo $product->getTitle() ?></title>
	</head>

	<body>
				 
	
		<div id="sitewrapper">
		
			<? include("includes/header.php"); ?>
			<? include("includes/menu.php"); ?>
			
			<div id="sitecontent">
			
				<div class="productleftcolwrapper">
				
					<div><!--*-->
					
						<img class='productmainimage' id='mainImage' style='cursor: pointer;' onclick="enlargeImage('<?echo $image?>')" src="<? echo $image ?>"/>
						
						<?
							
							//this is the section where we display all colorswatches which are w: 90, h:20
							
							//the swatchCounter will be used to keep track
							//so that any middle element will have a class
							//called productcenterswatchimg
							$swatchCounter = 1;
							
							$colorSwatches = "";
							
							//we loop through our color objects
							foreach($colorsArray as $color){
									
								$swatch = $color->getImage();
								$colorName = $color->getColor();
								
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
								
								$colorSwatches.="<div class='".$class1."'><img class='".$class."' style='cursor:pointer;' alt='".$colorName."' src='".$imagePath."swatches/".$swatch."' width='90px' height='20px' onclick=\"enlargeImage('".$imagePath."swatches/".$swatch."')\" /></div>";
								
								
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
							//we will only have a max of 2
							//in other words, 3 images (including the current main image)
							//will be displayed

							//this includes the main default image
							$imageArray = $product->getOtherProductViews($defaultColor);
							
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
						
					</div> <!--closes *-->
					
					<div class="productleftcolheader">
					
					<p class="sitesectionheadertext">Bra Doctor's Advice</p>
					
					</div>
					
					<div class="productleftcolcontent">
					
						<p class="productleftcoltext">
							
							
							<? 
								if($product->getBraDoctorHelp()!=""){	
									echo $product -> getBraDoctorHelp();								
								}
								else{
									//put contact us thingy								
								}
							?>

						</p>
					
					</div>
					
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
					
						<p class="producttitle"><? echo $product -> getProductName() ?> </p>
					
					</div>
					
					<div class="productsocialwrapper">
					
						<img src="images/socialwidgetplaceholder.jpg" />
					
					</div>
					
					<br style="clear:both;" />
					
					<div class="producttabberwrapper">
						<div class="tabber">
							
							<div class="tabbertab">
							
								<h2>Description</h2>
								
								<p class="productdescription">
									<? echo $product -> getDescription() ?>
								</p>
								
								<?
									if( !empty($fabricArray) ){
										
										$str = "<p class='productfabriccontent'>Fabric Content: ";
										
										//we want to ensure that the fabrics with type=fabric
										//are displayed first
										
										$firstStr = "";
										$remStr = "";
										
										foreach($fabricArray as $type=>$fabricObjArray){
											
											if(strcasecmp($type, "fabric")!=0){
												
												$remStr.="<br />".ucwords($type).": ";
											}
											
											//we need this to ensure that the first fabric
											//doesn't have a comma in front of it
											$fabCounter = 0;
											
											foreach($fabricObjArray as $fabric){
												
												if(strcasecmp($type, "fabric")==0){
													
													if($fabCounter>0){
														$firstStr.= ", ".$fabric->getPercentage()."% ".$fabric->getFabric();
													}
													else{
														$firstStr.= $fabric->getPercentage()."% ".$fabric->getFabric();
													}
													
												}
												else{
												
													if($fabCounter>0){
														$remStr.= ", ".$fabric->getPercentage()."% ".$fabric->getFabric();
													}
													else{
														$remStr.= $fabric->getPercentage()."% ".$fabric->getFabric();
													}
												}
												
												$fabCounter++;
											}
											
										}//end foreach
										
										$str.=$firstStr.$remStr."</p>";
										
										echo $str;
									
									}
								
								?>
								
								
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
								
								echo $product->getPrice()->getFormattedPrice(
																array("class='productreg'", "class='productintermediateprice'", "class='productfinalprice' style='margin-left:20px;'", "class='productyousave'"), 
																"",
																true, 
																false, 
																true);
							
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
							<select onchange="getColorsForSize(false, '<?echo $productId?>', '<? echo $defaultColor ?>', '<? echo $imagePath ?>')" name='sizeSelection' class="productselectyoursize">
																
								<?
									$sizeSelection = "";
									
									foreach($sizesArray as $size=>$col){
										
										if($col!=""&&$col!=null&&$size!=""){
											//then we know at least one color exists for this size
											//we're also ensuring we don't have an empty size
											$sizeSelection.="<option value='".$size."*".$col."'>".ucwords($size)."</option>";
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
								
								//now we create a collectionClass object
								//using the collectionClass class
								$collectionClass = new CollectionClass($product->getCollectionId(),$arrayOfActivationStatuses);
								
								//we now get an array of product objects representing products currently
								//in the collection
								$productsInCollection = $collectionClass->getProductsincollection();
								
								
								
								$str = "";
								
								foreach($productsInCollection as $prod){
									
									//we only care for products in the collection
									//other than the current one
									if($prod->getProductId()==$product->getProductId()){
										
										continue;
									}
									//clearly if we enter this loop, there's at least
									//one other piece in this collection
									$emptyCollection = false;
									
									
									//first we get the productId, brand, and the product name
									$productIdc = $prod->getProductId();
									$brandc = $prod->getBrand();
									$productNamec = $prod->getProductName();
									
									$itemNumberc = $prod->getItemNumber();
									
									$link = "product3.php?id=".$itemNumberc;
									
									$priceString = $prod->getPrice()->getFormattedPrice(
																		array("class='productlowertablink1'", "class='productlowertablink4'", "class='productlowertablink3'", "class='productlowertablink4'"),
																		$link,
																		true, 
																		false,
																		true);
									
									//before we begin, we know that they all have their price in common
									//so we store the price display in a string called priceString
									
									$imagePathc = $prod->getImagePath();
									$image = $imagePathc.$prod -> getImage();
									
									$productNamecDisplay = substr($productNamec, 0, 58)."...";
									
									$str.="
									<li>
										<a href=".$link." style = 'text-decoration: none;'>
										<div class='productlowertabimg'><img src='".$image."' style='width: 124px;height:174px;' /></div>
										</a>
										
										<div class='productlowertabtext'>
										
											<p><a class='productlowertablink1' style='text-decoration: none;' href='".$link."'>".$productNamecDisplay."</a></p>
											<p><a class='productlowertablink2' style='text-decoration: none;' href='".$link."'>".ucwords($brandc)."</a></p>".
											$priceString."
										
											<img style='cursor: pointer;' onclick=\"addToBagCollection('$productIdc', '$defaultColorc', '$currency', '$imagePathc', '$image')\" src='images/addtobag3.jpg' />
										
										</div>
										
									</li>";
									
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
			<br style='clear: both' />
			<div id='contactArea'>
			
			
			</div>
			
			<div id='popupContactClose2'>CLOSE</div>
		</div>
		<div id="backgroundPopup"></div>
		
	</body>
</html>