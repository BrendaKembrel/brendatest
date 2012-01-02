<?
	session_start();
	
	include("../includes/functions/shoppingCartFunctions.php");
	include("../includes/connect.php");
	include("../classes/productClass.php");
	
	$productId = $_POST["productId"];
	$currency = $_POST["currency"];
	
	$promoArray = array();
	
	if(isset($_SESSION["promoArray"])){
	
		$promoArray = $_SESSION["promoArray"];
	}
	
	//image here is the default product image
	//(so if the user clicked on the green version of
	//a product, for example, $image= the green pic)
	$image = $_POST["image"];
	
	//imagePath is the path to any image of interest,
	//such as colourswatches, so we don't have to recalculate it
	$imagePath = $_POST["imagePath"];
	
	$product = new Product($con, $productId, "", $currency, $promoArray);
	
	$priceString = "";
	
	//PLEASE NOTE: THIS PART IS EXPLAINED IN details.php
	//and in the product class
	
	//this is an array of strings definining the colors for a rpoduct
	$colorArray = array();
	
	//the next array is where we will store colorswatches
	$colorswatchesArray = array();
	
	//the next array is where we will store all sizes
	//associated with this product
	$sizesArray = array();
	
	//these arrays are filled in thanks to a nifty function
	//sizesArray will have the form sizesArray[size]  = index of matching color1 in colornamesArray and colorswatchesArray,index of matching color2, etc
	//where the colors have the statuses defined by arrayOfActivationStatuses
	$arrayOfActivationStatuses = array("released");
	$arrayOfDesiredArrays = $product->getDesiredSizeColorArrays($arrayOfActivationStatuses);
	
	$sizesArray = $arrayOfDesiredArrays[0];
	$colorswatchesArray = $arrayOfDesiredArrays[1];
	$colorArray = $arrayOfDesiredArrays[2];
	
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
										$sizeSelection.="<option value='".$size."*".$col."'>".ucwords($size)."</option>";
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