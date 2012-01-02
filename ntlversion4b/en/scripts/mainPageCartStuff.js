
		//here are our global vars
	
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
		var defaultCol = "";
		var productStatuses = "";
		
		
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
			//centerPopup();
			//load popup
			loadPopup();
			
		}
		
		//this function will display to the user
		//the relevant colorswatches given
		//the size that they selected
		//$fromColl is a bool telling us whether
		//or not we're coming from the addToCartCollection.php: true
		//when calling this or if this is straight from the product page: false
		function getColorsForSize($fromColl, $productIdc, $defaultColorc, $imagePath){
			
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
			
			$("#"+$colorSectionName).html($str+"<input type='image' src='images/addtobag2.jpg' onclick=\"addToBag("+$addToBag+", '"+$productIdc+"', '"+$defaultColorc+"')\" />"+"<div id='"+$addToBagErrorName+"'></div>"+"<input type='hidden' name='"+$optionCounterName+"' value='"+$optionCounter+"' />"+"<input type='hidden' name='"+$sizeName+"' value='"+$size+"' />");
			
		}
		
		
		//this function is used to add the current product to bag (cart)
		//$fromColl tells us (true) if we came from addToCartCollection.php
		//or if we came from product.php (false) at which point some input names
		//change
		function addToBag($fromColl, $productIdc, $defaultColorc){
			
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
				
				$("#popupContact").css({
					"background": "white",
					"width": "24%",
					"height": "40%",
					"top": "30%",
					"left": "38%"
				});
				
				//now we do our ajax call
				
				$.post( "ajax/addToCart.php", 
						{	
							productId : $productIdc,
							defaultColor: $defaultColorc,
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
					
				//centerPopup();
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
		function addToBagCollection($productIdc, $defaultColorc, $currency, $imagePathc, $image){
			
			$("#popupContact").css({
				"background": "white",
				"width": "60%",
				"height": "60%",
				"top": "20%",
				"left": "20%"
			});
			
			$.post( "ajax/addToCartCollection.php", 
				{	
					productId : $productIdc,
					productStatuses: productStatuses,
					defaultColor: $defaultColorc,
					currency: $currency,
					imagePath: $imagePathc,
					image: $image
				},  
				function(responseText){  
					
					$("#contactArea").html(responseText);  
					//centerPopup();
					getColorsForSize(true, $productIdc, $defaultColorc, $imagePathc);
					loadPopup();
					
				},  
				"html" 
			);
		
		}