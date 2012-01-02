<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
   
<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/ntladmincurrent.css" />
	
		<!--first the jquery stuff goes here-->
		<script type='text/javascript'>
			
			<!--some global variables-->
			var $addedFabrics = 0;
			//this is the number of added sizes for itemType=1
			//basically addedSizes[1] = the number of addedSizes
			//for the color defined by color1
			var $addedSizes = new Array(); 
			
			//this array is used because when a user adds
			//a new fabric input (by clicking the + button)
			//then we want to display a select list of possible fabrics
			var possibleFabricsArray = new Array();
			
			//the following 2 arrays are used so that when a user adds
			//a new size input for sizingFormat = 1 (bra)
			//then we display possible cupSizes
			var cupSizesArray  = new Array();
			
			<!--function time-->
			$(document).ready(function(){
			
			});
			
			function injectText(elemento,valor){
			
			 var elemento_dom=document.getElementsByName(elemento)[0];
			 if(document.selection){
			  elemento_dom.focus();
			  sel=document.selection.createRange();
			  sel.text=valor;
			  return;
			 }if(elemento_dom.selectionStart||elemento_dom.selectionStart=="0"){
			  var t_start=elemento_dom.selectionStart;
			  var t_end=elemento_dom.selectionEnd;
			  var val_start=elemento_dom.value.substring(0,t_start);
			  var val_end=elemento_dom.value.substring(t_end,elemento_dom.value.length);
			  elemento_dom.value=val_start+valor+val_end;
			 }else{
			  elemento_dom.value+=valor;
			 }
			}
			
			function addFabric(){
			
				$addedFabrics++;
				
				var $str = "<span style='font-size:10px'>Type(fabric, lining, mesh): </span><br /><input type='text' name='addedFabricType"+$addedFabrics+"' style='width:170px' /><br />";
				
				$str +="<input type='text' style='width:30px;' name='addedPercentage"+$addedFabrics+"' />%  ";
				$str +="<select name='addedFabric"+$addedFabrics+"'>";
				
					$.each(possibleFabricsArray, function($index, $value){
												
						$str+="<option value='"+$value+"' >"+$value+"</option>";
					
					});
				
				$str +="</select><br />";
				
				
				$("#fabrics").append($str);
							
			}
			
			//this function is used so that, when a user clicks on a button
			//the Canadian prices are set to match the US prices
			function matchCadPricesToUS(){
				
				$("#canPrice").val($("#price").val());
				$("#canFinalPrice").val($("#finalPrice").val());
				$("#canPriceType").val($("#priceType").val());
			
			}
			
			//this function is used so that, when
			//we add a size to the color defined by
			//$index, we increment the number stored in the array
			//so that, at the end, we'll have a tally of how many
			//sizes were added for this specific color
			function addSizesByColor($index){
				
				if($addedSizes[$index]==null){
					$addedSizes[$index]=0;
				}
				$addedSizes[$index] = parseInt($addedSizes[$index])+1;
				
				$colorCounter = $index;
				$sizeCounter = $addedSizes[$index];
				
				$str = "";
				
				$str+="<div id='addedsize"+$colorCounter+$sizeCounter+"'>";
				$str+="		<div style='float: left;width: 150px;margin:2px;'>";
				$str+="			<select name='addedcup"+$colorCounter+$sizeCounter+"'>";
										
										$.each(cupSizesArray, function($i, $cupa){
											$str+="<option value='"+$cupa+"'>"+$cupa+"</option>";
										});
										
				$str+="			</select>";	
				$str+="		</div>";
				$str+="		<div style='float: left;width: 350px;margin:2px;'>";
				$str+="			<input type='text' name='addedrange1"+$colorCounter+$sizeCounter+"' style='width: 120px;' />-<input type='text' name='addedrange2"+$colorCounter+$sizeCounter+"' style='width: 120px;' />";
				$str+="		</div>";
				$str+="		<div style='float: left;width: 350px;margin:2px;'>";
				$str+="			<input type='text' name='addedexclude"+$colorCounter+$sizeCounter+"' style='width: 300px;' />";
				$str+="		</div>";
				$str+="		<div style='float: left;width: 50px;margin:2px;'>";
				$str+="			<span style='cursor: pointer; color: red;' onclick=\"deleteSize('"+$sizeCounter+"', '"+$colorCounter+"', true)\">Delete</span>";
				$str+="		</div>";
				$str+="	</div>";
				
				$("#sizeSelection"+$colorCounter).append($str);
			}
			
			//this function allows you to delete
			//sizing for a color
			//$added is used so that we know if this is for an added
			//color or just an old color (the name differs)
			function deleteSize($sizeCounter, $colorCounter, $added){
				
				if(!$added){
					
					$("#size"+$colorCounter+$sizeCounter).remove();
				}
				else{
					
					$("#addedsize"+$colorCounter+$sizeCounter).remove();
				}
				
			}
			
			//the goal here is to match the color defined by colorCounter's
			//size checkboxes with the chosen color's settings
			function matchColor($colorCounter, $sizingFormat){
				
				//now, first we want to know what the person chose
				var $colorToUse = $("[name='matchColor"+$colorCounter+"']").val();
				
				if($colorToUse == "none"){
					
					//don't do anything
				}
				else{
				
					if($sizingFormat=="1"){
						
						
							
						//then we're dealing with bras
						//so the way we match is more complicated
						
						//the number of added sizes for the selected color's index is:
						$numAddedSizes = $addedSizes[$colorToUse];
						
						//now we want to set our current color's addedSizes value to the same as 
						//the number of added sizes for the color we're copying
						//(we're also going to set our numberOfSizes (that aren't added))
						//to have the correct value soon as an input=hidden
						$addedSizes[$colorCounter]  = $addedSizes[$colorToUse];
						
						if($numAddedSizes == null){
						
							$numAddedSizes = 0;
						}
						
						//the number of original sizes for the first index is
						$numOrigSizes = $("#numberOfSizes"+$colorToUse).val();
						
						//so now, we get all of their values and create the appropriate 
						//string that will correspond to the current color we're trying to match
						//up with the first color
						
						$str = "<div style='float: left;width: 150px;margin:2px;'>Cup</div>";
						$str+="<div style='float: left;width: 350px;margin:2px;'>Range</div>";		
						$str+="<div style='float: left;width: 350px;margin:2px;'>Exclude (comma separated list)</div>";		
						$str+="<div style='float: left;width: 50px;margin:2px;'>Delete</div><br style='clear:both' />";
						$str+="<img src='images/plus.jpg' alt='addSizeColor"+$colorCounter+"' onclick=\"addSizesByColor('"+$colorCounter+"')\" /><br style='clear:both' />";
						
						
						for($sizeCounter=1; $sizeCounter<$numOrigSizes; $sizeCounter++){
						
							$range1 = $("[name='range1"+$colorToUse+$sizeCounter+"']").val();
							$range2 = $("[name='range2"+$colorToUse+$sizeCounter+"']").val();
							$exclude = $("[name='exclude"+$colorToUse+$sizeCounter+"']").val();
							$cup = $("[name='cup"+$colorToUse+$sizeCounter+"']").val();
							
							if($range1==null){
								
								continue;
							}
							
							$str+="<div id='size"+$colorCounter+$sizeCounter+"'>";
							$str+="		<div style='float: left;width: 150px;margin:2px;'>";
							$str+="			<select name='cup"+$colorCounter+$sizeCounter+"'>";
													
													$.each(cupSizesArray, function($index, $cupa){
													
														if($cupa==$cup){
															$str+="<option value='"+$cupa+"' selected>"+$cupa+"</option>";
														}
														else{
															$str+="<option value='"+$cupa+"'>"+$cupa+"</option>";
														}
													});
													
							$str+="			</select>";	
							$str+="		</div>";
							$str+="		<div style='float: left;width: 350px;margin:2px;'>";
							$str+="			<input type='text' name='range1"+$colorCounter+$sizeCounter+"' style='width: 120px;' value='"+$range1+"' />-<input type='text' name='range2"+$colorCounter+$sizeCounter+"' style='width: 120px;' value='"+$range2+"' />";
							$str+="		</div>";
							$str+="		<div style='float: left;width: 350px;margin:2px;'>";
							$str+="			<input type='text' name='exclude"+$colorCounter+$sizeCounter+"' style='width: 300px;' value='"+$exclude+"' />";
							$str+="		</div>";
							$str+="		<div style='float: left;width: 50px;margin:2px;'>";
							$str+="			<span style='cursor: pointer; color: red;' onclick=\"deleteSize('"+$sizeCounter+"', '"+$colorCounter+"', false)\">Delete</span>";
							$str+="		</div>";
							$str+="	</div>";
							
						}
						
						//now we loop through the added ones
						for($sizeCounter=1; $sizeCounter<=$numAddedSizes; $sizeCounter++){
						
							$range1 = $("[name='addedrange1"+$colorToUse+$sizeCounter+"']").val();
							$range2 = $("[name='addedrange2"+$colorToUse+$sizeCounter+"']").val();
							$exclude = $("[name='addedexclude"+$colorToUse+$sizeCounter+"']").val();
							$cup = $("[name='addedcup"+$colorToUse+$sizeCounter+"']").val();
							
							
							if($range1==null){
								
								continue;
							}
							
							$str+="<div id='addedsize"+$colorCounter+$sizeCounter+"'>";
							$str+="		<div style='float: left;width: 150px;margin:2px;'>";
							$str+="			<select name='addedcup"+$colorCounter+$sizeCounter+"'>";
													
													$.each(cupSizesArray, function($index, $cupa){
													
														if($cupa==$cup){
															$str+="<option value='"+$cupa+"' selected>"+$cupa+"</option>";
														}
														else{
															$str+="<option value='"+$cupa+"'>"+$cupa+"</option>";
														}
													});
													
							$str+="			</select>";	
							$str+="		</div>";
							$str+="		<div style='float: left;width: 350px;margin:2px;'>";
							$str+="			<input type='text' name='addedrange1"+$colorCounter+$sizeCounter+"' style='width: 120px;' value='"+$range1+"' />-<input type='text' name='addedrange2"+$colorCounter+$sizeCounter+"' style='width: 120px;' value='"+$range2+"' />";
							$str+="		</div>";
							$str+="		<div style='float: left;width: 350px;margin:2px;'>";
							$str+="			<input type='text' name='addedexclude"+$colorCounter+$sizeCounter+"' style='width: 300px;' value='"+$exclude+"' />";
							$str+="		</div>";
							$str+="		<div style='float: left;width: 50px;margin:2px;'>";
							$str+="			<span style='cursor: pointer; color: red;' onclick=\"deleteSize('"+$sizeCounter+"', '"+$colorCounter+"', true)\">Delete</span>";
							$str+="		</div>";
							$str+="	</div>";
							
						}
						
						//don't forget to send over the number of original sizes
						//which is equivalent here to sizeCounter
						$str+="<input type='hidden' id='numberOfSizes"+$colorCounter+"' name='numberOfSizes"+$colorCounter+"' value='"+$numOrigSizes+"' />";
						
						$("#sizeSelection"+$colorCounter).html($str);
					}
					else{
						var $name = "sizeSelection"+$colorCounter;
						
						 $("#"+$name+" :checked").each(function(){
							
							$(this).attr("checked", false);
						
						});
						
						 $("#sizeSelection"+$colorToUse+" :checked").each(function(){
							
							$("input[name='"+$name+"[]'][value='"+$(this).val()+"']").attr("checked", true);

						});
					}
					
				}//end else the selected color wasn't none
			}
			
			//this function allows the user to see
			//the actual appearance of the French description
			//with the accents and everything
			function viewAppearance(){
				
				$.post(  "ajax/getActualDesc.php", 
							{	
								
								desc: $("#frenchDescription").val()
								
							},  
							function(responseText){  
								
								alert(responseText);
								
							},  
							"html" 
						);
				
			
			}
			
			function validate(){
				
				$returnValue = false;
				
				$("#fabrics").append("<input type='hidden' name='addedFabrics' value='"+$addedFabrics+"' />");
				
				
				$.each( $addedSizes,
						function($index, $value){
							
							if($value==null){
								
								$value = 0;
							}
							
							$("#sizeSelection"+$index).append("<input type='hidden' name='addedSizes"+$index+"' value='"+$value+"' />");
						});
						
				
				var $numberOfImages = $("[name='numberOfImages']").val();
				
				$countDefaultImages = 0;
				
				for($i=1; $i<$numberOfImages; $i++){
				
					$imageType = $("[name='imageType"+$i+"']").val();
					$generate = $("[name='generate"+$i+"']").val();
					
					if($imageType=="default"){
						
						$countDefaultImages++;
						
						//we can't have a default image
						//also be a generate image
						if($generate!="generate"){
							
							//then we know that the user also intended
							//to make a generate page for this image
							alert("You can't make a generate page for a default image");
							return false;
						}
					}
					
				}
				
				if($countDefaultImages<1){
					alert("One image must be of type default and you have chosen none");
				}
				else if($countDefaultImages==1){
					return true;
				}	
				else{
					alert("Only one image can be of type default");
				
				}
				
				return $returnValue;
			
			}
			
		</script>
		
		<?
		
		include("include/connect.php");
		
		//here's where we get all of our variables
		//that were passed over from step 0
		
		//first panel
		$productId = mysql_real_escape_string($_POST["productId"]);
		$newProductId = mysql_real_escape_string($_POST["newProductId"]);
		$action = mysql_real_escape_string($_POST["action"]);
		
		$modelNumber = trim( stripslashes ( mysql_real_escape_string($_POST["modelNumber"]) ) );
		$collection = strtolower( trim( stripslashes (mysql_real_escape_string($_POST["collection"]) ) ) );
		$descWord = trim( stripslashes (mysql_real_escape_string($_POST["descWord"]) ) );
		$brand = trim( stripslashes (mysql_real_escape_string($_POST["brand"]) ) );
		
		//second panel
		$itemType = trim( stripslashes ( mysql_real_escape_string($_POST["itemType"]) ) );
		
		$season = trim( stripslashes (mysql_real_escape_string($_POST["season"]) ) );
		$year = trim( stripslashes (mysql_real_escape_string($_POST["year"]) ) );
		$sizingFormat = trim( stripslashes (mysql_real_escape_string($_POST["sizingFormat"]) ) );
		
		//here we have the sizingFormat as words (type)
		//we want the associated typeId instead
		$sql = "SELECT typeId 
				FROM sizeDefault
				WHERE type LIKE '$sizingFormat'";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$sizingFormat = $row["typeId"];
		
		//generate panel
		$itemNumber = trim( stripslashes ( mysql_real_escape_string($_POST["itemNumber"]) ) );
		$productName = trim( stripslashes ( mysql_real_escape_string($_POST["productName"]) ) );
		
		
		//color panel
		$numberOfColors = mysql_real_escape_string($_POST["numberOfColors"]);
		
		//we're also going to place all of the colors in a color array
		//for future use
		$colorArray = array();
		
		//Because we want to pass all this information off to the next level,
		//we're going to have to pass as hidden variables
		$hiddenInput ="	<input type='hidden' name='productId' value='$productId' />
						<input type='hidden' name='newProductId' value='$newProductId' />
						<input type='hidden' name='action' value='$action' />
						<input type='hidden' name='modelNumber' value='$modelNumber' />
						<input type='hidden' name='collection' value='$collection' />
						<input type='hidden' name='descWord' value='$descWord' />
						<input type='hidden' name='brand' value='$brand' />
						<input type='hidden' name='itemType' value='$itemType' />
						<input type='hidden' name='season' value='$season' />
						<input type='hidden' name='year' value='$year' />
						<input type='hidden' name='sizingFormat' value='$sizingFormat' />
						<input type='hidden' name='itemNumber' value='$itemNumber' />
						<input type='hidden' name='productName' value='$productName' />
						<input type='hidden' name='numberOfColors' value='$numberOfColors' />";
		
		//we need to get all the color information
		for($i=1; $i<=$numberOfColors; $i++){
			
			$colorName = "color".$i;
			
			$color = strtolower( trim( stripslashes ( mysql_real_escape_string($_POST[$colorName]) ) ) );
			
			if($color==""){
				//no one inputted a color name, so we just
				//continue to the next iteration
				continue;
			
			}
			
			$activationColor = trim( stripslashes ( mysql_real_escape_string($_POST["activation".$colorName]) ) );
			
			if(strcasecmp($activationColor, "update")==0){
				
				$activationDate = date("Y-m-d");
			}
			elseif(strcasecmp($activationColor, "no activation")==0){
				
				$activationDate = "0000-00-00";
			}
			else{
				
				$coloracmonth = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."acmonth"]) ) );
				$coloracday = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."acday"]) ) );
				$coloracyear = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."acyear"]) ) );
				
				$activationDate = $coloracyear."-".$coloracmonth."-".$coloracday;
			}
			
			
			
			$colordeacmonth = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."deacmonth"]) ) );
			$colordeacday = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."deacday"]) ) );
			$colordeacyear = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."deacyear"]) ) );
			
			if(strcasecmp($colordeacyear, "YYYY")==0||$colordeacyear==""){
				
				$deactivationDate = "0000-00-00";
			}
			else{
			
				$deactivationDate = $colordeacyear."-".$colordeacmonth."-".$colordeacday;
			}
			
			$colorisnew = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."isnew"]) ) );			
			$colorisnewdays = trim( stripslashes ( mysql_real_escape_string($_POST[$colorName."isnewdays"]) ) );
			
			if(strcasecmp($colorIsNew, "no")==0||!is_numeric($colorisnewdays)){
				
				$colorisnewdays = 0;
			}
			
			$hiddenInput.="<input type='hidden' name='".$colorName."' value='".$color."' />
						   <input type='hidden' name='".$colorName."activationDate' value='".$activationDate."' />
						   <input type='hidden' name='".$colorName."deactivationDate' value='".$deactivationDate."' />	
						   <input type='hidden' name='".$colorName."isNewUntil' value='".$colorisnewdays."' />
							";
							
			array_push($colorArray, $color);
		}
		
		$description = "";
		$frenchDescription = "";
		$braDoctorHelp = "";
		
		$cost = 0;
		$price = 0;
		$finalPrice = 0;
		$canPrice = 0;
		$canFinalPrice = 0;
		
		$priceType = 0;
		$canPriceType = 0;
		
		$fabricArray = array();
		
		
		//now, if we're modifying or copying, we need to get the info that's already stored in the
		//database
		if(strcasecmp($action, "modify")==0||strcasecmp($action, "copy")==0){
			
			$sql = "SELECT *
					FROM product
					WHERE productId = '$productId'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$description = stripslashes( $row["description"] );
			$frenchDescription = stripslashes( $row["frencDescription"] );
			$braDoctorHelp = stripslashes( $row["braDoctorHelp"] );
			
			$cost = number_format( $row["cost"], 2);
			$price = number_format( $row["price"], 2);
			$finalPrice = number_format( $row["finalPrice"], 2);
			$canPrice = number_format( $row["canPrice"], 2);
			$canFinalPrice = number_format( $row["canFinalPrice"], 2);
			$priceType = $row["priceType"];
			$canPriceType = $row["canPriceType"];
			
			//next we will set up the fabric array
			$sql = "SELECT *
					FROM productFabric
					WHERE productId = '$productId'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			//the fabricArray will be of the form:
			//fabricArray[type1] = fabric1=>percentage1, fabric2=>percentage2
			//where type is fabric, lining, lace, mesh, etc
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				$type = $row["type"];
				$fabric = $row["fabric"];
				$percentage = $row["percentage"];
				
				if(array_key_exists($type, $fabricArray) ){
					
					$fabricArray[$type][$fabric] = $percentage;
				}
				else{
					
					$fabricArray[$type] = array($fabric=>$percentage);
				}
				
			}			
			
		}
		
		//to use for finding images, we want the imagePath
		
		$imagePath = "../../en/images/";
		
		$brandPart = str_replace(" ", "-", strtolower($brand));
		
		if($year!=""&&$year!=0){
		
			$imagePath .= $year."/";
		}
		
		if($season!=""&&strcasecmp($season, "none")!=0){
		
			$imagePath .= $season."/";
		}
		
		$imagePath .= $brandPart."/";
		
		$imagePath = strtolower($imagePath);
		
		//here's a little preparation for the sizing section
		
		//now we want to know what the possible sizes are
		$possibleSizesArray = array();
		
		$orderBy = "";
		if($sizingFormat==1){
			$orderBy = "ORDER BY size ASC";
		}
		$sql = "SELECT size
				FROM sizeDefault
				WHERE typeId='$sizingFormat'
				$orderBy";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
			
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			
			array_push($possibleSizesArray, $row["size"]);
		
		}	
		
		if($sizingFormat==1){
		
			$arrayOfBandSizes = array();
			$arrayOfCupSizes = array("none");
			
			//first we want to rev up lists of
			//the possible sizes
			foreach($possibleSizesArray as $size){
				
				$band = substr($size, 0, 2);
				$cup = substr($size, 2);
				
				if(!in_array($band, $arrayOfBandSizes)){
				
					array_push($arrayOfBandSizes, $band);
					
				}
				
				if(!in_array($cup, $arrayOfCupSizes)){
					
					array_push($arrayOfCupSizes, $cup);
				}
			}
			
			//now we want to pass over onto the javascript side
			//the possible cup sizes so that when we add a new size
			//we'll still have a list of possibilities to choose from
			echo "<script type='text/javascript'>";
			echo 'cupSizesArray = new Array("', join($arrayOfCupSizes ,'","'), '");';
			echo "</script>";
		
		}
		
		//we want an array of all images currently used for this product
		//of course, we're really only interested in this for modified prods
		$imagesArray = array();
		
		$sql = "SELECT *
				FROM images
				WHERE productId = '$productId'";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		while ( $row = mysql_fetch_array($result, MYSQL_ASSOC)){
			
			$image = $row["image"];
			$type = $row["type"];
			$imageId = $row["imageId"];
			
			$imagesArray[$image] = array($imageId, $type);
			
		}
		
		?>
	</head>
	
	<body>	
		<form name='productStep1form' action='productStep2.php' method='post' onsubmit='return validate();'>
		<div id='wrapper'>
			
			<div class='productStep1modulefulldiv'>
				<? //we echo all the hiddenInput from the head
					echo $hiddenInput;
				?>	
				<p class='headerBox'>Descriptions</p>
					
					<div class='productStep1descdivs'>
						Description:<br />
						<textarea style='width:308px;height: 200px;' name='description'><?echo $description?></textarea>
					</div>
					<div class='productStep1descdivs'>
						French Description:<br />
						<textarea style='width:308px;height: 200px;' name='frenchDescription' id='frenchDescription'><? echo $frenchDescription?></textarea>
					</div>
					<div class='productStep1descdivs'>
						Accents:<br />
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&eacute;");?>')">&eacute;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&egrave;");?>')">&egrave;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&ecirc;");?>')">&ecirc;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&euml;");?>')">&euml;</button><br />
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&agrave;");?>')">&agrave;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&acirc;");?>')">&acirc;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&icirc;");?>')">&icirc;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&iuml;");?>')">&iuml;</button><br />
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&ocirc;");?>')">&ocirc; </button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&oelig;");?>')">&oelig;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&ucirc;");?>')">&ucirc; </button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&ugrave;");?>')">&ugrave; </button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&ccedil;");?>')">&ccedil;</button><br />
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&Eacute;");?>')">&Eacute;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&Egrave;");?>')">&Egrave;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&Ccedil;");?>')">&Ccedil;</button>
						<button type='button' style='width: 25px; font-size:14px;' onclick="injectText('frenchDescription','<? echo htmlspecialchars("&Agrave;");?>')">&Agrave;</button><br />
						<br /><br />
						<span style='color:red;cursor:pointer;' onclick='viewAppearance()'>View Actual Appearance</span>
					</div>
				
			</div>
			<br style='clear:both' />
			<div class='productStep1modulefulldiv'>
			
				<p class='headerBox'>Bra Doctor Help, Fabric, Prices</p>
				<div class='productStep1descdivs'>
						Bra Doctor Help:<br />
						<textarea style='width:308px;height: 200px;' name='braDoctorHelp'><? echo $braDoctorHelp?></textarea>
				</div>
				<div class='productStep1descdivs'>
						Fabrics:<br />
						<?
							
						$str = "";
						
						//first, we want a list of all possible fabrics
						
						$possibleFabricsArray = array();
						
						$sql = "SELECT fabric
								FROM fabric";
								
						$result = mysql_query($sql, $con) or die(mysql_error());
								
						while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
						
							array_push($possibleFabricsArray, $row["fabric"]);
						}
						//now we need a javacript version of this array
						
						echo "<script type='text/javascript'>";
						echo 'possibleFabricsArray = new Array("', join($possibleFabricsArray ,'","'), '");';
						echo "</script>";
				  
						//now first we display all fabrics that were already
						//inputted in the database
						
						$fabricCounter = 1;
						
						if(!empty($fabricArray)){
							
							
							foreach($fabricArray["fabric"] as $fabric=>$percentage){
								
								$str.="
									<input type='hidden' name='fabricType".$fabricCounter."' value='fabric' />
									<input type='text' style='width:30px;' name='percentage".$fabricCounter."' value='".$percentage."' />% 
									<select name='fabric".$fabricCounter."'>";
								
								foreach($possibleFabricsArray as $poss){
									
									$selected = "";
									
									if(strcasecmp($fabric, $poss)==0){
										
										$selected = "selected";
									
									}
									
									$str.="<option value='$poss' $selected>$poss</option>";
								}
								
								$str.="</select><br />";
								
								$fabricCounter++;
								
							}
							
							//now we deal with things that aren't part of the fabric,
							//like the mesh, or the lace
							foreach($fabricArray as $type=>$fArray){
								
								if(strcasecmp($type, "fabric")==0){
									
									//we already dealt with fabric
									continue;
								
								}
								
								$str.="<br />".$type.":<br />";
								
								foreach($fArray as $fabric=>$percentage){
									$str.="
										<input type='hidden' name='fabricType".$fabricCounter."' value='".$type."' />
										<input type='text' style='width:30px;' name='percentage".$fabricCounter."' value='".$percentage."' />% 
										<select name='fabric".$fabricCounter."'>";
									
									foreach($possibleFabricsArray as $poss){
										
										$selected = "";
										
										if(strcasecmp($fabric, $poss)==0){
											
											$selected = "selected";
										
										}
										
										$str.="<option value='$poss' $selected>$poss</option>";
									}
									
									$str.="</select><br />";
									
									$fabricCounter++;
								}	
							}
							
						}
						else{
							
							for($i=1; $i<=2; $i++){
								
								$str.="<input type='hidden' name='fabricType".$fabricCounter."' value='fabric' />
										<input type='text' style='width:30px;' name='percentage".$fabricCounter."' />%  ";
								
								$str.="<select name='fabric".$fabricCounter."'>";
								
								foreach($possibleFabricsArray as $poss){
																		
									$str.="<option value='$poss'>$poss</option>";
								}
								
								$str.="</select><br />";
								
								$fabricCounter++;
							}
							
							
							
						}
						
						
						echo $str."<br />";
						?>
					<div  id='fabrics'>
						<img alt='addFabric' src='images/plus.jpg' onclick='addFabric()' /><br />
					</div>
						
				</div>
				<div class='productStep1descdivs'>
						Prices:<br />
						Our Cost: <input type='text' style='width: 100px;'  name='cost' value='<?echo $cost?>' /><br />
						US<br />
						Regular Price: $USD<input type='text' style='width: 100px;' id='price' name='price' value='<?echo $price?>' /><br />
						Selling Price: $USD   <input type='text' style='width: 100px;' id='finalPrice' name='finalPrice' value='<?echo $finalPrice?>' /><br />
						Price Type: <select name='priceType' id='priceType'>
						<?
						$arrayOfPriceTypes = array("0"=>"Select Price Type", "1"=>"Regular", "2"=>"Our Price", "3"=>"Sale", "4"=>"Clearance");
						
						$str = "";
						foreach($arrayOfPriceTypes as $num=>$type){
							
							$selected = "";
							
							if(strcasecmp($num, $priceType)==0){
								$selected = "selected";
							
							}
							
							$str.="<option value='$num' $selected>$type</option>";
						}
							echo $str;
						?>
								
									</select><br /><br />
						CAD (<span style='color: red;cursor:pointer;' onclick='matchCadPricesToUS()'>Click to Match USD</span>)<br />
						Regular Price: $CAD<input type='text' style='width: 100px;' id='canPrice' name='canPrice' value='<?echo $canPrice?>' /><br />
						Selling Price: $CAD   <input type='text' style='width: 100px;' id='canFinalPrice' name='canFinalPrice' value='<?echo $canFinalPrice?>' /><br />
						Price Type: <select name='canPriceType' id='canPriceType'>
						<?
						
						$str = "";
						foreach($arrayOfPriceTypes as $num=>$type){
							
							$selected = "";
							
							if(strcasecmp($num, $canPriceType)==0){
								$selected = "selected";
							
							}
							
							$str.="<option value='$num' $selected>$type</option>";
						}
							echo $str;
						?>
						</select><br />
				</div>
			</div>
			<br style='clear:both' />
			<div class='productStep1modulefulldiv' id='colorsSizes'>
				<p class='headerBox'>Colors and Sizes</p>
				<?	
					$str="";
					
					//the colorCounter is used because
					//the first color's values will be stored under the name
					//color1, the second's under color2, etc
					
					$colorCounter = 1;
						
					//the first thing we want to do is cover possible
					//names for swatches.  They either have the form collectioncolor.jpg, 
					//color.jpg or even modelNumbercolor.jpg 
					foreach($colorArray as $color){
						
						$colorOrig = $color;
						$color = str_replace("/", "", $color);
						$color = str_replace(" ", "", $color);
						
						$poss1 = "";
						$poss2 = "";
						$poss3 = "";
						
						if($collection!=""){
							
							$poss1 = str_replace(" ", "", $collection).$color;
							
						}	
						
						$poss2 = $color;
						$poss3 = $modelNumber.$color;
						
						$potentialColorImagesArray = array();
						
						$pathToUse = $imagePath."swatches";
						
						//we want to rev up the default swatch so that at least that will always be used
						//in the event of no other option
						$sql = "SELECT defaultSwatch
									FROM brand
									WHERE brand LIKE '$brand'";
									
						$result = mysql_query($sql, $con) or die(mysql_error());
						
						$row = mysql_fetch_array($result, MYSQL_ASSOC);
						
						$defaultSwatch = $row["defaultSwatch"];
						
						if($defaultSwatch==0){
							
							$def = $poss2.".jpg";
						}
						else{
							
							$def = $poss1.".jpg";
						}
						
						//we ensure that this is a directory
						if(!is_dir($pathToUse)){
							
							echo $pathToUse." is not a feasible directory to get to your colors.<br />";
							
							//we also won't get any swatch options so we're going to use the brand's
							//default 
							
							
							$str.="<input type='hidden' name='swatch".$colorCounter." value='".$def."' />";
							
							$colorCounter++;
							
							continue;
						}
						
						//Open images directory
						$dir = opendir($pathToUse);
						
						//List files in the selected directory
						while (($file = readdir($dir)) !== false){
							
							if(stripos($file, ".png")===false){
							
								//then the file is not a png
								
								//we explore different possibilities
								//of the file name
								
								//first checking collectioncolor
								if($poss1!=""){
								
									if(stripos($file, $poss1)!==false){
										
										//so we add it as a potential image
										array_push($potentialColorImagesArray, $file);	

									}
								}
								
								//next checking modelNumbercolor
								if(stripos($file, $poss3)!==false){
										
									//so we add it as a potential image
									array_push($potentialColorImagesArray, $file);	
									
								}
								
								//now we're just checking if there's the color alone
								if(strcasecmp($file, $poss2.".jpg")==0){
									
									//so we add it as a potential image
									array_push($potentialColorImagesArray, $file);	
								}
								
							}
						}
						closedir($dir);
						
						//we want find the corresponding cid and image for the color
						//so that, for example, the image that we already associate with the color
						//is automatically checked, and stuff like that
						$sql = "SELECT cid, image
								FROM productColor
								WHERE color LIKE '$colorOrig'
								AND productId = '$productId'";
						
						$result = mysql_query($sql, $con) or die(mysql_error());
						
						$row = mysql_fetch_array($result, MYSQL_ASSOC);
						
						$image = "";
						$cid = -1;
						
						if(!empty($row)){
							
							//then we know the color already existed
							$cid = $row["cid"];
							$image = $row["image"];
						}
						
						
						$str.="<div class='productStep1modulefulldiv' id='colorsize".$colorCounter."' >
								<div style='float:left;width: 150px;text-align: center;'>$colorOrig";
						
						$str.="<br />Match Sizes With Color:
									<select name='matchColor$colorCounter' onchange=\"matchColor('$colorCounter', '$sizingFormat')\">
									<option value='none'>None</option>
									";
									
									for($j=1; $j<=count($colorArray); $j++){
										
										$str.="<option value='$j'>".$colorArray[($j-1)]."</option>";
										
									}
									
							$str.="							
									</select>";
									
						$str.="	</div>";
						//now we display the swatch options
						foreach($potentialColorImagesArray as $colImg){
							
							$checked = "";
							
							//if we know the corresponding image, it might as well be checked
							//if there's only 1 possible option, it might as well be checked
							//if we don't know, then the default image might as well be checked
							if(strcasecmp($colImg, $image)==0||count($potentialColorImagesArray)==1|| strcasecmp($colImg, $def)==0){
							
								$checked = "checked";
							}
							
							$image = $imagePath."swatches/".$colImg;
							
							$str.="	<div style='float:left'>
									<img src='$image' alt='swatch$image' width='90px' height='20px' />  <input type='radio' name='swatch".$colorCounter."' value='$colImg' $checked />
									</div>";
						}
						
						$str.="<br style='clear:both' />";
						
						//now we deal with the sizes based on our sizingFormat
						//first we check if any sizes were stored for this particular sizingFormat
						//and the given color
						//of course this can only be possible if the color was already stored
						$sizesArray = array();
						
						if($cid!=-1){
							
							$orderBy = "";
							
							if($sizingFormat==1){
							
								$orderBy = "ORDER BY size ASC";
							}
							
							//the sizes do exist for this color
							//so we select the sizes for it
							//as long as these sizes are of the same
							//sizeDefault type as the one specified 
							$sql = "SELECT psc.size
									FROM   productSizeColor psc
									WHERE psc.cid = '$cid'
									AND psc.size IN
									(
										SELECT size
										FROM sizeDefault
										WHERE typeId='$sizingFormat'
									)
									$orderBy
									";
							
							$result = mysql_query($sql, $con) or die(mysql_error());
							
							while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
								
								if($row["size"]!=""){
								
									array_push($sizesArray, $row["size"]);
								}
							
							}
						}
						
						
						//now we display according to sizingFormat
						if($sizingFormat=="1"){
						
							//we're dealing with bras
							//which have a different input style
							$str.="<div id='sizeSelection".$colorCounter."'>";
							
							//now, if we're modifying or copying then we want to properly
							//display the current selected sizing scheme
							
							$arrayOfCupSizeBandComboByColor = array();
							
							//the way this will be stored is:
							//arrayOfCupSizeBandComboByColor["c"] = 34,38,40 means that the user
							//inputted 34c,38c,40c
							
							if(!empty($sizesArray)){
								
								$sizeCounter = 1;
								
								foreach($sizesArray as $size){
								
									
									$band = substr($size, 0, 2);
									$cup = substr($size, 2);
									
									if(array_key_exists($cup, $arrayOfCupSizeBandComboByColor)){
										
										$arrayOfCupSizeBandComboByColor[$cup].= $band.",";
									
									}
									else{
										
										$arrayOfCupSizeBandComboByColor[$cup] = $band.",";
									
									}
									
								}
							}
							
							if(!empty($arrayOfCupSizeBandComboByColor)){
								
								//sizeCounter is one greater than the actual number of sizes
								//and so you'll eventually loop from blah=1 to blah<sizeCounter
								$sizeCounter = count($arrayOfCupSizeBandComboByColor)+1;
								
								$j = 1;
								
								$str.="		<div style='float: left;width: 150px;margin:2px;'>
												Cup
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												Range
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												Exclude (comma separated list)
											</div>
											<div style='float: left;width: 50px;margin:2px;'>
												Delete
											</div>
											<br style='clear:both' />
											<img src='images/plus.jpg' alt='addSize$colorCounter' onclick=\"addSizesByColor('$colorCounter')\" />
											<br style='clear:both' />
											";
											
								foreach($arrayOfCupSizeBandComboByColor as $cup=>$bandString){
									
									$bandSizesArray = explode(",", $bandString);
									array_pop($bandSizesArray);
									
									$range1 = $bandSizesArray[0];
									$range2 = $bandSizesArray[count($bandSizesArray)-1];
									
									$exclude = "";
									
									$previousBand = $range1;
									foreach($bandSizesArray as $band){
									
										if($band-$previousBand>2){
											
											//then we know that all bands between
											//$previousBand and $band are excluded
											for($k=$previousBand; $k<$band; $k+=2){
												
												if($previousBand==$k){
													
													continue;
												}
												else{
													

													$exclude.=$k.",";
												
												}
											}											
											
										}
										
										$previousBand = $band;
									}
									
									$str.="<div id='size".$colorCounter.$j."'>
											<div style='float: left;width: 150px;margin:2px;'>
												<select name='cup".$colorCounter.$j."'>";
												
												foreach($arrayOfCupSizes as $cup2){
													
													$selected = "";
													
													if(strcasecmp($cup, $cup2)==0){
														
														$selected = "selected";
													}	
												
													$str.="<option value='$cup2' $selected>$cup2</option>";
												}
									$str.="				
												</select>
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												<input type='text' name='range1".$colorCounter.$j."' value='".$range1."' style='width: 120px;' />-<input type='text' name='range2".$colorCounter.$j."' value='".$range2."' style='width: 120px;' />
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												<input type='text' name='exclude".$colorCounter.$j."' value='".$exclude."' style='width: 300px;' />
											</div>
											<div style='float: left;width: 50px;margin:2px;'>
												<span style='cursor: pointer; color: red;' onclick=\"deleteSize('".$j."', '".$colorCounter."', false)\">Delete</span>
											</div>
										</div>";
										
										$j++;
										
								}//end foreach $arrayOfCupSizeBandComboByColor
								 
							
							}
							else{
								
								$sizeCounter = 1;
								
								$str.="		<div style='float: left;width: 150px;margin:2px;'>
												Cup
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												Range
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												Exclude (comma separated list)
											</div>
											<div style='float: left;width: 50px;margin:2px;'>
												Delete
											</div>
											<br style='clear:both' />
											<img src='images/plus.jpg' alt='addSizeColor$colorCounter' onclick=\"addSizesByColor('$colorCounter')\" />
											<br style='clear:both' />
											";
								
								for($j=1; $j<=2; $j++){
								
								//note that here I'm using sizeCounter as a counter
								//(instead of $j) but also because in the end,
								//I want to know how many sizes were used (in this case, 2)
								$str.="<div id='size".$colorCounter.$j."'>
											<div style='float: left;width: 150px;margin:2px;'>
												<select name='cup".$colorCounter.$j."'>";
												
												foreach($arrayOfCupSizes as $cup){
													
													$str.="<option value='$cup'>$cup</option>";
												}
								$str.="				
												</select>
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												<input type='text' name='range1".$colorCounter.$j."' style='width: 120px;' />-<input type='text' name='range2".$colorCounter.$j."' style='width: 120px;' />
											</div>
											<div style='float: left;width: 350px;margin:2px;'>
												<input type='text' name='exclude".$colorCounter.$j."' style='width: 300px;' />
											</div>
											<div style='float: left;width: 50px;margin:2px;'>
												<span style='cursor: pointer; color: red;' onclick=\"deleteSize('".$j."', '".$colorCounter."', false)\">Delete</span>
											</div>
										</div>";
										
									$sizeCounter++;
								}//end for $j=
								
								
							}
							
							
							//don't forget to send over how many sizes were stored for the current color
							$str.=" <input type='hidden' id='numberOfSizes".$colorCounter."' name='numberOfSizes".$colorCounter."' value='".$sizeCounter."' />
									</div>";
						
						}
						else{
							//we're dealing with something else
							//which will simply use checkboxes
							
							$str.="<div id='sizeSelection".$colorCounter."'>";
							
							foreach($possibleSizesArray as $size){
								
								$checked = "";
								
								if(!empty($sizesArray)&&in_array($size, $sizesArray)){
									
									$checked = "checked";
								}
								
								$str.="<div style='float:left;width:150px;'>
										<input type='checkbox' name='sizeSelection".$colorCounter."[]' ".$checked." value='$size' /> $size
										</div>";
								
							
							}
							
							$str.="</div>";
							
						}				
						
						$str.="
							</div><br style='clear:both' />";
						
						$colorCounter++;
						
					}//end foreach colorArray
					
					echo $str;
				?>
			</div>
			<br style='clear: both'>
			
			<div  class='productStep1modulefulldiv'>
				<p class='headerBox'>Images and Product Generation</p>
				
				<?
					//so now we're going to be dealing with images
					//we already have our imagePath (the path to the directory
					//that contains the images we want)
					//and we have an array of images already used for this product
					//in the case of a modification
					
					//note, we created imagePath with an extra / at the end,
					//as in, C:/, and we don't search for directories with this
					//trailing slash, so we must remove it
					
					$pathToUse = substr($imagePath, 0, -1);
					$potentialImagesArray = array();
					
					$imageCounter = 1;
					//we ensure that this is a directory
					if(!is_dir($pathToUse)){
						
						echo $pathToUse." is not a feasible directory to get to your images.<br />";
						
						//we also won't get any image options, so we'll just use the default modelNumber
						//as the image
						
						$str.="<input type='hidden' name='image".$imageCounter." value='".$modelNumber."p.jpg"."' />
							<input type='hidden' name='imageType".$imageCounter." value='default' />
							<input type='hidden' name='generate".$imageCounter." value='generate' />";
						
						$imageCounter++;
					}
					else{
						//Open images directory
						$dir = opendir($pathToUse);
						
						//List files in the selected directory
						while (($file = readdir($dir)) !== false){
							
							if(stripos($file, ".png")===false){
							
								//then the file is not a png
								//we don't want to include pngs
								//because they take up a lot of space
								//and so we're only interested in jpgs
								
								//if the file name contains the modelNumber
								//of the product, then we're interested in
								//using it as a possibility
								
								if(stripos($file, $modelNumber)!==false){
										
									//so we add it as a potential image
									array_push($potentialImagesArray, $file);	

								}
																
							}
							
						}//end while
						
						closedir($dir);
						
						//so now we display the images one by one
						$str="";
						
						$arrayOfImageTypes = array("none", "default", "product view");
						$arrayOfGenerate = array("generate");
						
						foreach($colorArray as $color){
							
							array_push($arrayOfGenerate, $color);
						
						}
						
						foreach($potentialImagesArray as $img){
							
							$src = $pathToUse."/".$img;
							
							$str.="<div class='productStep1imagedivs'>
									<img alt='$src' src='$src' width='180px' />
									<input type='hidden' name='image".$imageCounter."' value='".$img."' />
									$img<br />
									<select name='imageType".$imageCounter."'>";
									
									//so now we go through the select list, but
									//we want to make sure that the currently selected type is selected
									foreach($arrayOfImageTypes as $type){
										
										$selected = "";
										
										if(!empty($imagesArray)&&array_key_exists($img, $imagesArray)){
											
											if(strcasecmp($imagesArray[$img][1], $type)==0){
												$selected = "selected";
											}
										}
										$str.="<option value='$type' $selected>$type</option>";
									
									}
							
							$str.="							
									</select><br />
									<select name='generate".$imageCounter."'>";
									
									//we do the same kind of thing here
									//basically, if a specific image is used
									//to represent the product in a certain color
									//then its imageId will be stored in
									//the productColor table alongside the corresponding color
									//so here we get the imageId (assuming this image was already created)
									//and set it to selected if its imageId corresponds to one stored in productColor
									foreach($arrayOfGenerate as $gen){
										
										$selected = "";
										
										if(!empty($imagesArray)&&array_key_exists($img, $imagesArray)){
											
											$imageId = $imagesArray[$img][0];
											
											
											//now we check if there's a color with that
											//specific imageId
											$sql = "SELECT color
													FROM productColor
													WHERE imageId='$imageId'";		
											
											$result = mysql_query($sql, $con) or die(mysql_error());
											
											$row = mysql_fetch_array($result, MYSQL_ASSOC);
											
											if(!empty($row)){
												
												if(strcasecmp($row["color"], $gen)==0){
													$selected = "selected";
												}
											}
											
											
										}
										$str.="<option value='$gen' $selected>$gen</option>";
									
									}
							
							$str.="
									</select>
									";
									
							
							
							$str.="</div>";
							
							$imageCounter++;
						}
						
						
					}//end else
					
					$str.="<input type='hidden' name='numberOfImages' value='$imageCounter' />";
					
					echo $str;
				?>
				
			</div>
			<br style='clear:both' />
			
			<div class='productStep1modulefulldiv'>
				<p class='headerBox'>Categories and Features</p>
			
			</div>
			<br style='clear:both' />
		</div><!--wrapper div-->
		
		<input type='hidden' name='fabricCounter' value='<? echo $fabricCounter; ?>' />
		<input type='submit' name='submit' />
		</form>
	</body>
	
	
</html>