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
			
			<!--function time-->
			$(document).ready(function(){
				
			
				$("#itemType").change(function(){
					
					$(this).css("background-color","#FFBBDD");
					
					
					$.post(  "ajax/getSizingFormatFromItemType.php", 
							{	
								
								itemType: $("#itemType").val()
								
								
							},  
							function(responseText){  
								
								$("#sizingFormat").val(responseText);  
								$("#sizingFormat").css("background-color","#FFCC00"); 
							},  
							"html" 
						);
					
				});
				
			});
			
			//this was taken from the net
			function isNumber($n) {
			  return !isNaN(parseFloat($n)) && isFinite($n);
			}

			//renders the first letter uppercase.
			//found online
			function convert() {
				  return arguments[0].toUpperCase();
			}
			
			function generateItemNumber(){
				
				var $modelNumber = $("#modelNumber").val();
				var $collection = $("#collection").val();
				var $descWord = $("#descWord").val();
				var $brand = $("#brand").val();
				
				var $itemNumber = $modelNumber;
				
				if($collection!=""&&$collection!=null){
					
					$itemNumber+="-"+$collection;
				}
				if($descWord!=""&&$descWord!=null){
					
					$itemNumber+="-"+$descWord;
				}
				
				$itemNumber+="-"+$brand;
				
				var $itemNumber = $itemNumber.replace(/ /g,'-');
				
				$("#itemNumber").val($itemNumber.toLowerCase());
				$("#itemNumber").css("background-color", "#CCAAEE");
			}
			
			function generateProductName(){
				
				var $modelNumber = $("#modelNumber").val().toLowerCase().replace(/\b[a-z]/g, convert);
				var $collection = $("#collection").val().toLowerCase().replace(/\b[a-z]/g, convert);
				var $descWord = $("#descWord").val().toLowerCase().replace(/\b[a-z]/g, convert);
				var $brand = $("#brand").val().toLowerCase().replace(/\b[a-z]/g, convert);
				
				var $productName = $modelNumber;
				
				if($collection!=""&&$collection!=null){
					
					$productName+=" "+$collection;
				}
				if($descWord!=""&&$descWord!=null){
					
					$productName+=" "+$descWord;
				}
				
				$productName+=" by "+$brand;
				
				
				$("#productName").val($productName);
				$("#productName").css("background-color", "#CCAAEE");
			}
			
			function addColor($colorId){
				
				
				var $colorName = "color"+$colorId;
				var $imageId = "imagecolor"+$colorId;
				var $newColorId = parseInt($colorId) +1;
				
				
				$("#addColor").html("Add Color <img id='"+$imageId+"' src='images/plus.jpg' onclick=\"addColor('"+$newColorId+"')\" style='vertical-align:bottom'><input type='hidden' name='numberOfColors' value='"+$colorId+"'>");
				
				var $str = "<div class='productStep0colorDivRow'>";
				
				$str+="<div class='productStep0colorDiv'><input type = 'text' name='"+$colorName+"' id='"+$colorName+"'></div>";
				$str+= "<div class='productStep0colorDiv'>";
						$str+="<input type='radio' name='activation"+$colorName+"' value='update'>On Update/Insertion<br/>";
						$str+="<input type='radio' name='activation"+$colorName+"' value='date'>Date: <input type='text' id='"+$colorName+"acmonth' name='"+$colorName+"acmonth' style='width:25px;' value='MM'><input type='text' id='"+$colorName+"acday' name='"+$colorName+"acday' style='width:25px;' value='DD'><input type='text' id='"+$colorName+"acyear' name='"+$colorName+"acyear' style='width:40px;' value='YYYY'><br/>";
						$str+="<input type='radio' name='activation"+$colorName+"' value='no activation' checked='checked'>Do not activate<br/>";
				$str+="</div>";
				$str+= "<div class='productStep0colorDiv'>";
						$str+="<input type='text' id='"+$colorName+"deacmonth' name='"+$colorName+"deacmonth' style='width:25px;' value='MM'><input type='text' id='"+$colorName+"deacday' name='"+$colorName+"deacday' style='width:25px;' value='DD'><input type='text' id='"+$colorName+"deacyear' name='"+$colorName+"deacyear' style='width:40px;' value='YYYY'>";
				$str+="</div>";
				$str+= "<div class='productStep0colorDiv'>";
						$str+="<input type='radio' name='"+$colorName+"isnew' value='yes'>Yes  <input type='radio' name='"+$colorName+"isnew' value='no' checked='checked'>No <br/>";
						$str+="For <input type='text' style='width: 20px' id='"+$colorName+"isnewdays' name='"+$colorName+"isnewdays' value='0'> days";
				$str+="</div>";
				
				$str+="<span style='color:red; font-weight:bold;'>Match values to first color?<br/><img style='margin-left:60px' src='images/yes.jpg' onclick=\"matchValues('"+$colorName+"')\"></span>";
				$str+="</div><br style='clear:both'>";
				
				
				
				$("#colors").append($str);
				
			}
			
			function matchValues($colorName){
				
				$("#"+$colorName+"acmonth").val($("#color1acmonth").val());
				$("#"+$colorName+"acday").val($("#color1acday").val());
				$("#"+$colorName+"acyear").val($("#color1acyear").val());
				
				$("#"+$colorName+"deacmonth").val($("#color1deacmonth").val());
				$("#"+$colorName+"deacday").val($("#color1deacday").val());
				$("#"+$colorName+"deacyear").val($("#color1deacyear").val());
								
				$("#"+$colorName+"isnewdays").val($("#color1isnewdays").val());
				
				//now we deal with checking the appropriate radio boxes
				var $value = $('input[name=color1isnew]:checked').val();
				
				$("[name='"+$colorName+"isnew'][value='"+$value+"']").attr("checked", true);
				
				var $value = $('input[name=activationcolor1]:checked').val();
				
				$("[name='activation"+$colorName+"'][value='"+$value+"']").attr("checked", true);
				
				
				$("#span"+$colorName).html($("#spancolor1").html());
				
			}
			
			
			function validate(){
				
				if($("#modelNumber").val()==""){
					
					$("#modelNumberComplaint").html("You must input a modelNumber.");
					return false;
				}
				else{
				
					return true;
				}
				
			}
			
			
		</script>
		
		<?
	
			include("include/connect.php");
			include("functions.php");

			//the newProductId value is the productId
			//value that will be given to brand new products
			//(ie: action=insert or action=copy); else this value = -1
			$newProductId = -1;
			
			//first, we get our productId value, if it's set
			//because it is required for both the copy/modify actions
			
			if( isset($_GET["productId"]) && isset($_GET["action"]) ){
				
				//note, for a a product currently being copied,
				//the productId corresponds to the copied product, 
				//not the new product
				$productId = mysql_real_escape_string(trim($_GET["productId"]));
				$action = mysql_real_escape_string(trim($_GET["action"]));
				
				if($action==""||$productId==""){
					$action = "insert";
				}
				
				$titleString = $action."ing product ".$productId.": Step 0";
				
			}
			else{
			
				$action = "insert";
				$titleString = "Preliminary Product Input";
				
			}
			
			if(strcasecmp($action, "modify")!=0){
				
				$sql = "SELECT max(productId) as max
						FROM product";
				
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				$newProductId = $row["max"]+1;
				
			}				
				
			echo "<title>$titleString</title>";
			
			//in order to set up our select lists
			//we need to prepare some arrays of values
			
			//first the brands
			$brandArray = array();
			
			$sql = "SELECT * 
					FROM brand";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
			
				
				$brandArray[$row[ "brand" ]] = ucwords($row["shortenedBrand"]);
			
			}
			
			//then the season			
			$seasonArray = array("fall", "winter", "spring", "summer", "none");
			
			//then the year
			$yearArray = array();
			
			for( $year = 2010; $year < 2025; $year++){
			
				array_push($yearArray, $year);
			
			}
			
			//then the itemType
			$itemTypeArray = array();
			
			$sql = "SELECT * 
					FROM itemType";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				array_push($itemTypeArray, $row["itemType"]);
			}
			
			//next, the possible sizingFormat
			$sizingFormatArray = array();
			
			$sql = "SELECT distinct type
				    FROM sizeDefault";
			
			$result = mysql_query($sql, $con) or die(mysql_error);
			
			while($row= mysql_fetch_array($result, MYSQL_ASSOC)){
				
				array_push($sizingFormatArray, $row["type"]);
			}
			
			
			//now, we need to prepare our variables
			//this is because, if an item already exists
			//then we want to make sure all fields are set to its values
			
			$modelNumber = "";
			$collection = "";
			$descWord = "";
			$brand = "";
			$itemType = "";
			$season = "";
			$year = "2011";
			$sizingFormat = "";
			$itemNumber = "";
			$productName = "";
			$colorsArray = array();
			
			if(strcasecmp($action, "modify")==0||strcasecmp($action, "copy")==0){
				
				$sql = "SELECT *
						FROM product
						WHERE productId = '$productId'";
						
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				$modelNumber = $row["modelNumber"];
				$collection = $row["collection"];
				$descWord = $row["descWord"];
				$brand = $row["brand"];
				$itemType = $row["itemType"];
				
				$sql2 = "SELECT itemType
						FROM itemType
						WHERE itemTypeId = '$itemType'";
						
				$result2 = mysql_query($sql2, $con) or die(mysql_error());
				
				$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
				
				$itemTypeString = $row2["itemType"];
				
				$season = $row["season"];
				$year = $row["year"];
				$sizingFormat = $row["sizingFormat"];
				
				$itemNumber = $row["itemNumber"];
				$productName = $row["productName"];
				
				//now we can fill our colorsArray
				$sql = "SELECT *
						FROM productColor
						WHERE productId = '$productId'";
						
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
					
					$color = $row["color"];
					$deactivationDate = $row["deactivationDate"];
					$activationDate = $row["activationDate"];
					$isNewUntil = $row["isNewUntil"];
					
					$colorsArray[$color] = array($activationDate, $deactivationDate, $isNewUntil);
				
					
				}
				
			}
			
			if($sizingFormat!=""){
				
				//then we want to find the equivalent
				//word representing the typeId stored 
				//as the sizingFormat in the product table
				$sql = "SELECT type
						FROM sizeDefault
						WHERE typeId = '$sizingFormat'";
						
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				$sizingFormat = $row["type"];
			}
			
			

		?>
		
	</head>

	<body>
		<form id='productStep0' name='productStep0' action='productStep1.php' method='post'  onsubmit='return validate()'>
		<input type='hidden' name='productId' value='<?echo $productId;?>' >
		<input type='hidden' name='newProductId' value='<?echo $newProductId;?>' >
		<input type='hidden' name='action' value='<?echo $action;?>' >
		
		<div id='wrapper'>
			<div class='productStep0moduleDiv'>
				<p class='headerBox'>Details</p>
				Product Id: <? if(strcasecmp($action, "modify")==0){
								
									echo $productId;
								}
								else{
								
									echo $newProductId;
									
								}
							?> <br/>
				Model Number*: <input type='text' id='modelNumber' name='modelNumber' value='<?echo $modelNumber?>'> <span style='font-style: italic;font-size:11px;color:red;' id='modelNumberComplaint'></span><br/>
				Collection: <input type='text' id='collection' name='collection' value='<?echo $collection?>'><br/>
				Descriptive Words: (eg: sleeveless dress) <input type='text' id='descWord' name='descWord' value='<?echo $descWord?>'><br/>
				Brand: <select name='brand' id='brand'>	
							<?
								$str="";
								foreach($brandArray as $key=>$value){
									
									$selected = "";
										
									if(strcasecmp($brand, $key)==0){
									
										$selected = "selected";
									}
									
									$str.="<option value='$key' $selected>$value</option>";
								
								}
								echo $str;
							?>
						</select>
								
			</div>
			<div class='productStep0moduleDiv'>
				<p class='headerBox'>Helpful Info</p>
				Item Type: <select name='itemType' id='itemType'>	
							<?
								$str="";
								foreach($itemTypeArray as $value){
								
									$selected = "";
										
									if(strcasecmp($itemTypeString, $value)==0){
									
										$selected = "selected";
									}
									
								
									$str.="<option value='$value' $selected>$value</option>";
								
								}
								echo $str;
							?>
							</select><br/>
					Season: <select name='season' id='season'>	
							<?
								$str="";
								foreach($seasonArray as $value){
								
									$selected = "";
										
									if(strcasecmp($season, $value)==0){
									
										$selected = "selected";
									}
									
									$str.="<option value='$value' $selected>$value</option>";
								
								}
								echo $str;
							?>
							</select><br/>
							
					Year: <select name='year' id='year'>	
							<?
								$str="";
								foreach($yearArray as $value){
									
									$selected = "";
										
									if(strcasecmp($year, $value)==0){
									
										$selected = "selected";
									}
									
								
									$str.="<option value='$value' $selected>$value</option>";
								
								}
								echo $str;
							?>
							</select><br/>
							
					Sizing Format: <select name='sizingFormat' id='sizingFormat'>	
							<?
								$str="";
								foreach($sizingFormatArray as $value){
									
									$selected = "";
										
									if(strcasecmp($sizingFormat, $value)==0){
									
										$selected = "selected";
									}
									
								
									$str.="<option id='$value' value='$value' $selected>$value</option>";
								
								}
								echo $str;
							
							?>
							</select>
			
			</div>
			
			<br style='clear: both'>
			
			<div class='productStep0moduleDiv'>
				<p class='headerBox'>Item Number and Product Name</p>
				<img src='images/generate.jpg' onclick='generateItemNumber();generateProductName()' style='vertical-align: bottom;margin-left: 130px;'><br/>
				Item Number: <input type='text' id='itemNumber' name='itemNumber' value='<?echo $itemNumber?>' style='width:350px;'><br/>
				Product Name: <input type='text' id='productName' name='productName' value='<?echo $productName?>' style='width:350px;'>
			</div>
			
			<br style='clear: both'>
			<div class='productStep0largemoduleDiv'>
				<p class='headerBox'>Colors</p>
				<div class='productStep0colorDivRow'>
				
					<div class='productStep0colorDiv'>
						Name
					</div>
					<div class='productStep0colorDiv'>
						Activation
					</div>
					<div class='productStep0colorDiv'>
						Deactivation<br/>
						(specify if known)
					</div>
					<div class='productStep0colorDiv'>
						New?
					</div>
					
				</div>
				<br style='clear: both'>
				
				<?
					if(!empty($colorsArray)){
						
						$colorCounter = 1;
						
						foreach($colorsArray as $color=>$acArray){
							
							$activation = $acArray[0];
							$deactivation = $acArray[1];
							$isNewUntil = $acArray[2];
							$colorName = "color".$colorCounter;
							
							$str = "<div class='productStep0colorDivRow'>				
										<div class='productStep0colorDiv'>
											<input type='text' name='color$colorCounter' id='color$colorCounter' value='$color'>
										</div>
										<div class='productStep0colorDiv'>";
										
										if(strcmp("0000-00-00", $activation)==0 || $activation=="" ){
											
											//then no date was set, so the do not activate is checked
											$str.="<div class='productStep0colorDiv'>
														<input type='radio' name='activation$colorName' value='update'>On Update/Insertion<br/>
														<input type='radio' name='activation$colorName' value='date'>Date: <input type='text' id='".$colorName."acmonth' name='".$colorName."acmonth' style='width:25px;' value='MM'><input type='text' id='".$colorName."acday' name='".$colorName."acday' style='width:25px;' value='DD'><input type='text' id='".$colorName."acyear' name='".$colorName."acyear' style='width:40px;' value='YYYY'><br/>
														<input type='radio' name='activation$colorName' value='no activation' checked='checked'>Do not activate<br/>
													</div>";
										}
										else{
											
											//a date was set, so date is checked
											//and a date will be displayed
											$dateArray = explode("-", $activation);
											
											$str.="<div class='productStep0colorDiv'>
														<input type='radio' name='activation$colorName' value='update'>On Update/Insertion<br/>
														<input type='radio' name='activation$colorName' value='date' checked='checked'>Date: <input type='text' id='".$colorName."acmonth' name='".$colorName."acmonth' style='width:25px;' value='$dateArray[1]'><input type='text' id='".$colorName."acday' name='".$colorName."acday' style='width:25px;' value='$dateArray[2]'><input type='text' id='".$colorName."acyear' name='".$colorName."acyear' style='width:40px;' value='$dateArray[0]'><br/>
														<input type='radio' name='activation$colorName' value='no activation'>Do not activate<br/>
													</div>";
										
										}
										
										
								$str .= "</div>
										<div class='productStep0colorDiv'>";
										if(strcmp("0000-00-00", $deactivation)==0 || $deactivation=="" ){
											
											$str.="<input type='text' id='".$colorName."deacmonth' name='".$colorName."deacmonth' style='width:25px;' value='MM'><input type='text' id='".$colorName."deacday' name='".$colorName."deacday' style='width:25px;' value='DD'><input type='text' id='".$colorName."deacyear' name='".$colorName."deacyear' style='width:40px;' value='YYYY'>";

										}
										else{
										
											$dateArray = explode("-", $deactivation);
											$str.="<input type='text' id='".$colorName."deacmonth' name='".$colorName."deacmonth' style='width:25px;' value='$dateArray[1]'><input type='text' id='".$colorName."deacday' name='".$colorName."deacday' style='width:25px;' value='$dateArray[2]'><input type='text' id='".$colorName."deacyear' name='".$colorName."deacyear' style='width:40px;' value='$dateArray[0]'>";

										}
							   $str .= "</div>
										<div class='productStep0colorDiv'>";
										if($isNewUntil==0||$isNewUntil==""){
										
										$str.="
											<input type='radio' name='".$colorName."isnew' value='yes'>Yes  <input type='radio' name='".$colorName."isnew' value='no' checked='checked'>No <br/>
											For <input type='text' style='width: 20px' id='".$colorName."isnewdays' name='".$colorName."isnewdays' value='0'> days<br/>
											<span id='span$colorName' style='color:green'></span>
											";
										}
										else{
											
											//if we're here then the 
											$str.="
											<input type='radio' name='".$colorName."isnew' value='yes' checked='checked'>Yes  <input type='radio' name='".$colorName."isnew' value='no'>No <br/>
											For <input type='text' style='width: 20px' id='".$colorName."isnewdays' name='".$colorName."isnewdays' value='$isNewUntil'> days";
											
											if(strcmp("0000-00-00", $activation)!=0 && $activation!="" ){
											
												//then we want to display the number of days remaining
												//until the item won't be new anymore
												
												$daysSinceActivation = count_days( date("Y-m-d"), $activation);
												
												$remDays = $isNewUntil-$daysSinceActivation;
												
												if($remDays<0){
													
													$str.="<br/>
													<span id='span$colorName' style='color:green'>0 days remaining</span>";
																																	
												}
												elseif($remDays==0){
													
													$str.="<br/>
													<span id='span$colorName' style='color:green'>last day remaining</span>";
												}
												elseif($remDays>$isNewUntil){
													$str.="<br/>
													<span id='span$colorName' style='color:green'>$isNewUntil days remaining upon activation</span>";
												}
												else{
													
													$str.="<br/>
													<span id='span$colorName' style='color:green'>$remDays days remaining</span>";
												
												}
											}
											else{
												
												$str.=" <br/>
														<span id='span$colorName' style='color:green'></span>";
											}
										}
										
							$str.="		</div>";
							
							if($colorCounter>1){
								
								$str.="<div>
										<span style='color:red; font-weight:bold;'>Match values to first color?<br/><img style='margin-left:60px' src='images/yes.jpg' onclick=\"matchValues('".$colorName."')\"></span>
										</div>";
								
							}
							
							$str.="
									</div>";
							
							echo $str;
							
							$colorCounter++;

						}
						
						$numberOfColors = count($colorsArray);
						$counter = $numberOfColors + 1;
						
						
					 
					}
					else{
					
						echo "
					<div class='productStep0colorDivRow'>				
						<div class='productStep0colorDiv'>
							<input type='text' name='color1' id='color1'>
						</div>
						<div class='productStep0colorDiv'>
							<input type='radio' name='activationcolor1' value='update'>On Update/Insertion<br/>
							<input type='radio' name='activationcolor1' value='date'>Date: <input type='text' id='color1acmonth' name='color1acmonth' style='width:25px;' value='MM'><input type='text' id='color1acday' name='color1acday' style='width:25px;' value='DD'><input type='text' id='color1acyear' name='color1acyear' style='width:40px;' value='YYYY'><br/>
							<input type='radio' name='activationcolor1' value='no activation' checked='checked'>Do not activate<br/>
						</div>
						<div class='productStep0colorDiv'>
							<input type='text' id='color1deacmonth' name='color1deacmonth' style='width:25px;' value='MM'><input type='text' id='color1deacday' name='color1deacday' style='width:25px;' value='DD'><input type='text' id='color1deacyear' name='color1deacyear' style='width:40px;' value='YYYY'>
						</div>
						<div class='productStep0colorDiv'>
							<input type='radio' name='color1isnew' value='yes'>Yes  <input type='radio' name='color1isnew' value='no' checked='checked'>No <br/>
							For <input type='text' style='width: 20px' id='color1isnewdays' name='color1isnewdays' value='0'> days						
						</div>
					</div>";
						
						$numberOfColors = 1;
						$counter = 2;
					}
				?>
					<br style='clear:both'>
					<div id='colors'>
					</div>
					<br style='clear:both'>
					<div id='addColor' style='padding: 5px;'>
						Add Color <img id='imagecolor1' src='images/plus.jpg' onclick="addColor('<?echo $counter?>')">
						<input type='hidden' name='numberOfColors' value='<?echo $numberOfColors?>'>
					</div>
			     
			
			</div>
			
			<br style='clear: both'/>
			<div>
				 <input id='submit' type="submit" value="Go!" />
			</div>
		</div>
		
		</form>
	</body>	
	
</html>