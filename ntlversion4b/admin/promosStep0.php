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
			//this allows us to know what effect we are adding
			//so that we can keep track of how many new effects were added
			var $effectCounter = 0;
			
			//this is the part that will allow us to know how many operators
			//were added for each effect
			//array[i] = j where i = added effect's id (effectCounter value)
			//and j = the number of operators
			var $addedEffectNumberOfOperatorsArray = new Array();
			
			//these are javascript arrays to match our php arrays
			//to give the user drop down lists of selections
			var denominationArray = new Array();
			var thingArray = new Array();
			var operatorArray = new Array();
			
			var brandArray = new Array();
			var brandBehindArray = new Array();
			var categoryBehindArray =  new Array();
			var categoryArray = new Array();
			var priceTypeBehindArray = new Array();
			var priceTypeArray = new Array();
			var currencyArray = new Array();
			var collectionArray = new Array();
			var itemTypeBehindArray = new Array();
			var itemTypeArray = new Array();
			
			<!--function time-->
			$(document).ready(function(){
			
			});
			
			
			//a warning will pop up if the user selects sitewide with no promo
			//because sitewide with  no promo combines with all promos, even mutex ones
			function typeWarning(){
				
				$("#warningDiv").html("");
				$("#warningDiv").css("background-color", "white");
				
				if( $("[name='type']").val() == "sitewide with no promo"){
					
					$("#warningDiv").css("background-color", "pink");
					$("#warningDiv").html("Please note that all sitewide promos combine with any promotion, whether that promotion is \"combinable\" or not");
				}
				
			}
			
			//when the user selects the thing of their op (eg: brand, collection, currency, itemType, etc)
			//then the appropriate op and value selections will appear
			//$added means whether we're coming from an old effect (false)
			//or a new one (true)
			//$effectId and $opId are the ids of the effect and the operator
			//$opSel is the currently selected operator (if we're modifying/copying)
			//and $valSel is the currently selected value (if we're modifying/copying)
			function getAppropriateOpValue($added, $effectId, $opId, $opSel, $valSel){
				
				var $thingId = "thing"+$effectId+$opId;
				var $operatorId = "operator"+$effectId+$opId;
				var $valueId = "value"+$effectId+$opId;
				
				//if we're coming from modified products,
				//we want the correct options to be selected automatically
				//so we are going to use opSel and brandSel to help us determine
				//what should be selected
				var $selected = "";
				var $str = "";
				
				//the following arrays include the operator options we will make available
				//given the thing : LIKE, NOT LIKE, etc
				var $likeNotLikeArray = new Array("LIKE", "NOT LIKE");
				var $equalsNotEqualsArray = new Array("=", "!=");
				var $greaterThanLessThanArray = new Array("=", "!=", ">=", ">", "<=", "<");
				
				if($added){
					
					$thingId = "added"+$thingId;
					$operatorId = "added"+$operatorId;
					$valueId = "added"+$valueId;
				}
				
				//first we want to know which thing the user chose
				//so we can react accordingly
				$thing = $("[name='"+$thingId+"']").val();
				
				
				if($thing=="brand"){
					
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($likeNotLikeArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
					
					$str = "<select name='"+$valueId+"'>";
					
					$.each(brandArray, function($index, $elem){
							
							$selected = "";
							
							if(brandBehindArray[$index]==$valSel){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+brandBehindArray[$index]+"' "+$selected+">"+$elem+"</option>";
						});
					
					$str += "</select>";
					
					$("#"+$valueId).html($str);
				}
				else if($thing=="category"){
				
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($likeNotLikeArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
					
					$str = "<select name='"+$valueId+"'>";
					
					$.each(categoryArray, function($index, $elem){
							
							$selected = "";
							
							if(categoryBehindArray[$index]==$valSel){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+categoryBehindArray[$index]+"' "+$selected+">"+$elem+"</option>";
						});
					
					$str += "</select>";
					
					$("#"+$valueId).html($str);
				}
				else if($thing=="priceType"){
				
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($equalsNotEqualsArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
					
					$str = "<select name='"+$valueId+"'>";
					
					$.each(priceTypeArray, function($index, $elem){
							
							$selected = "";
							
							if(priceTypeBehindArray[$index]==$valSel){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+priceTypeBehindArray[$index]+"' "+$selected+">"+$elem+"</option>";
						});
					
					$str += "</select>";
					
					$("#"+$valueId).html($str);
				}
				else if($thing=="currency"){
					
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($likeNotLikeArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
					
					$str = "<select name='"+$valueId+"'>";
					
					$.each(currencyArray, function($index, $elem){
							
							$selected = "";
							
							if($elem==$valSel){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
					
					$str += "</select>";
					
					$("#"+$valueId).html($str);
				}
				else if($thing=="collection"){
					
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($likeNotLikeArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
					
					$str = "<select name='"+$valueId+"'>";
					
					$.each(collectionArray, function($index, $elem){
							
							$selected = "";
							
							if($elem==$valSel){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
					
					$str += "</select>";
					
					$("#"+$valueId).html($str);
				}
				else if($thing=="itemType"){
				
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($likeNotLikeArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
					
					$str = "<select name='"+$valueId+"'>";
					
					$.each(itemTypeArray, function($index, $elem){
							
							$selected = "";
							
							if(itemTypeBehindArray[$index]==$valSel){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+itemTypeBehindArray[$index]+"' "+$selected+">"+$elem+"</option>";
						});
					
					$str += "</select>";
					
					$("#"+$valueId).html($str);
				}
				else if($thing=="cart total"||$thing=="price"){
				
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($greaterThanLessThanArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
				}
				else if($thing=="isNew"){
				
					$str = "<select name='"+$operatorId+"'>";
					
						$.each($likeNotLikeArray, function($index, $elem){
							
							$selected = "";
							
							if($opSel==$elem){
								
								$selected = "selected";
							}
							
							$str+="<option value='"+$elem+"' "+$selected+">"+$elem+"</option>";
						});
						
					$str += "</select>";
					
					$("#"+$operatorId).html($str);
				}
			
			}
			
			//this function allows us to add an effect to the promoCode
			function addEffect(){
			
				$addedEffectNumberOfOperatorsArray[$effectCounter] = 0;
				
				$i = $effectCounter;
			
				var $str = "";
				
				$str+="<div id='addedEffect"+$i+"' style='width: 950px; margin: 4px; border: 1px solid black;'>";
						$str+="<p class='promosStep0p'>";
						$str+="Rate: <input type='text' name='addedrate"+$i+"' />";
							$str+="<select name='addeddenomination"+$i+"'>";
								
								$.each(denominationArray, function($index, $denom){
									
									$str+="<option value='"+$denom+"'>"+$denom+"</option>";
								});
								
							$str+="</select>";
						$str+="</p>";
						$str+="<p class='promosStep0'>";
							$str+="Add Operator <img src='images/plus.jpg' style='cursor: pointer;' onclick=\"addOperator(true, '"+$i+"')\">";
						$str+="</p>";
						$str+="<div id='addedOperatorSection"+$i+"'>";
						$str+="</div>";
				
				$("#addedEffectSection").append($str);
				
				$effectCounter=parseInt($effectCounter)+1;
			}
			
			//this function lets us add an operator for one of the older effects
			//defined by $effectId
			//$added: true (we're coming from an added effect;
			//false: we're coming from an original effect
			function addOperator($added, $effectId){
				
				var $opCounter = 0;
				
				if(!$added){
					$opCounter = parseInt( $("[name=numberOfOperators"+$effectId+"]").val() );
				}
				
				var $opId = "op"+$effectId+$opCounter;
				var $operatorId = "operator"+$effectId+$opCounter;
				var $thingId = "thing"+$effectId+$opCounter;
				var $valueId = "value"+$effectId+$opCounter;
				var $brId = "br"+$effectId+$opCounter;
				
				if($added){
					
					$opCounter = $addedEffectNumberOfOperatorsArray[$effectId];
					
					$opId = "addedop"+$effectId+$opCounter;
					$operatorId = "addedoperator"+$effectId+$opCounter;
					$thingId = "addedthing"+$effectId+$opCounter;
					$valueId = "addedvalue"+$effectId+$opCounter;
					$brId = "addedbr"+$effectId+$opCounter;
					
					//we increment the opCounter for the next operator to be added
					$addedEffectNumberOfOperatorsArray[$effectId]+=1;
					
				}
				else{
					//if we're coming from original effects, we need to do adjust the following since
					//the value of the number of operators is one greater than the old value
					$("[name=numberOfOperators"+$effectId+"]").val($opCounter+1);
				
				}
				
				var $str = "";
				
				$str+="<div id='"+$opId+"' style='margin: 2px; width: 940px;'>";
					$str+="<div style='width: 185px; float: left;margin: 5px;'>";
					$str+=	"<select name='"+$thingId+"' onchange=\"getAppropriateOpValue("+$added+", '"+$effectId+"', '"+$opCounter+"', '', '')\">";				
									
							$.each(thingArray, function($index, $th){
								
								$str+="<option value='"+$th+"'>"+$th+"</option>";
							});
							
					$str+=	"</select>";
					$str+="</div>";					
					
					$str+="<div id='"+$operatorId+"' style='width: 185px; float: left;margin: 5px;'>";
					$str+=	"<select name='"+$operatorId+"'>";				
									
							$.each(operatorArray, function($index, $th){
								
								$str+="<option value='"+$th+"'>"+$th+"</option>";
							});
							
					$str+=	"</select>";
					$str+="</div>";	
					
					$str+="<div id='"+$valueId+"' style='width: 200px; float: left;margin: 5px;'>";	
					$str+=	"<input type='text' name='"+$valueId+"' />";
					$str+="</div>";			
					
					$str+="<div style='width: 185px; float: left;margin: 5px;'>";	
						$str+="<span style='cursor: pointer' onclick=\"deleteOp('"+$effectId+"','"+$opCounter+"', "+$added+")\">Delete?</span>";
					$str+="</div>";
					
				$str+="<br id='"+$brId+"' style='clear:both'></div>";
				if($added){
					
					$("#addedOperatorSection"+$effectId).append($str);
				}
				else{
					
					$("#operatorSection"+$effectId).append($str);
				}
				
			}
			
			
			//this function is used to delete an operator
			function deleteOp($effectId, $opId, $added){
				
				if($added){
					$("#addedop"+$effectId+$opId).remove();
					$("#addedbr"+$effectId+$opId).remove();
				}
				else{				
					$("#op"+$effectId+$opId).remove();
					$("#br"+$effectId+$opId).remove();				
				}
			
			}
			
			//this function allows us to put the finishing touches
			//upon submit (such as pass hidden variables, etc)
			function validate(){
				
				var $returnValue = true;
				
				$("#wrapper").append("<input type='hidden' name='numberOfAddedEffects' value='"+$effectCounter+"' />");
				
				$.each($addedEffectNumberOfOperatorsArray, function($index, $elem){
							
							$countNumberInitialOps = 0;
							
							$("#wrapper").append("<input type='hidden' name='numberOfAddedOperators"+$index+"' value='"+$elem+"' />");
						});
				
				return true;
			}
		
		</script>
		
		<?
			
			include("include/connect.php");
			
			$action =  "";
			$promoCode = "";
			$oldPromoCode = "";
			
			//first we determine whether we're inserting, modifying or copying
			if(isset($_GET["action"])){
			
				$action = mysql_real_escape_string($_GET["action"]);
			}
			
			
			if($action==""){
				
				$action = "insert";
				
			}
			
			if(strcasecmp($action, "insert")==0&&(!isset($_GET["valueThatGetsReduced"])||$_GET["valueThatGetsReduced"]=="")){
			
				echo '<meta http-equiv="refresh" content="0;url=promosStep0Prep.php" />';
			}
			
			//if we're modifying, we want to get the promoCode's value
			if(isset($_GET["promoCode"])){
			
				if(strcasecmp($action, "copy")==0){
					
					//this is the value of the promoCode we're copying
					$oldPromoCode = mysql_real_escape_string($_GET["promoCode"]);
					$promoCode = $oldPromoCode;
				}
				else{
					
					$promoCode = mysql_real_escape_string($_GET["promoCode"]);
				
				}
			}
			
			if(strcasecmp($action, "insert")==0){
				
				//this will have been set by promosStep0Prep.php
				$valueThatGetsReduced = mysql_real_escape_string($_GET["valueThatGetsReduced"]);
			}
			
			//this will be stored as such:
			//arrayOfIds[$i] = id
			//such that arrayOfCondsOps[$i] = array(cond1, cond2, etc)
			//where cond1 = array(thing, operator, value)
			//in other words, all of these conditions together will have the
			//effect defined by id  
			$arrayOfIds = array(); 
			$arrayOfCondsOps = array();
			
			$arrayOfRates = array();
			$arrayOfDenominations = array();
			
			$expiryYear = "YYYY";
			$expiryMonth = "MM";
			$expiryDay = "DD";
			
			$releaseDate = date("Y-m-d");
			
			$releaseDateArray =  explode("-", $releaseDate);
			$releaseYear = $releaseDateArray[0];
			$releaseMonth = $releaseDateArray[1];
			$releaseDay = $releaseDateArray[2];
			
			$mutex = 1;
			$type = "";
			
			//here we want the arrays of possible selections
			$typeSelectionArray = array("requires promo", "sitewide with no promo", "single use with promo");
			$denominationArray = array("percentage", "fixed");
			$thingArray = array("price", "brand", "category", "collection", "priceType", "itemType", "currency", "isNew");
			$operatorArray = array("!=", "=", "<=", "<", ">=", ">");
			
			//these will be the selections for the values
			//of the logical operators
			$brandBehindArray = array("");
			$brandArray = array("");
			$categoryBehindArray = array("");
			$categoryArray = array("");
			$priceTypeBehindArray = array("","1", "2", "3", "4");
			$priceTypeArray = array("","Regular", "Our Price", "Sale", "Clearance");
			$currencyArray = array("","CAD", "USD");
			$collectionArray = array("");
			$itemTypeBehindArray = array("");
			$itemTypeArray = array("");
			
			//for the brand selection
			$sql = "SELECT brand, shortenedBrand
					FROM brand
					ORDER BY brand ASC";
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				array_push($brandBehindArray, $row["brand"]);
				array_push($brandArray, $row["shortenedBrand"]);
			}
			

			//for the category selection
			$sql = "SELECT categoryName, categoryId
					FROM category
					ORDER BY categoryName ASC";
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				array_push($categoryBehindArray, $row["categoryId"]);
				array_push($categoryArray, $row["categoryName"]);
			}
			
			//for the collection selection
			$sql = "SELECT collection
					FROM collection
					ORDER BY collection ASC";
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				array_push($collectionArray, $row["collection"]);
			}
			
			//for the itemType selection
			$sql = "SELECT itemType, itemTypeId
					FROM itemType
					ORDER BY itemType ASC";
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				array_push($itemTypeBehindArray, $row["itemTypeId"]);
				array_push($itemTypeArray, $row["itemType"]);
			}
			
			if(strcasecmp($valueThatGetsReduced, "cart total")==0){
				
				$thingArray = array("cart total", "currency");
			}
			else{
				$thingArray = array("price", "brand", "category", "collection", "priceType", "itemType", "currency", "isNew");
			}
				
			//now we prepare our equivalent javascript arrays
			echo "<script type='text/javascript'>";
			echo 'denominationArray = new Array("', join($denominationArray ,'","'), '");';
			echo 'thingArray = new Array("', join($thingArray ,'","'), '");';
			echo 'operatorArray = new Array("', join($operatorArray ,'","'), '");';
			echo 'brandBehindArray = new Array("', join($brandBehindArray ,'","'), '");';
			echo 'brandArray = new Array("', join($brandArray ,'","'), '");';
			echo 'categoryBehindArray = new Array("', join($categoryBehindArray ,'","'), '");';
			echo 'categoryArray = new Array("', join($categoryArray ,'","'), '");';
			echo 'priceTypeBehindArray = new Array("', join($priceTypeBehindArray ,'","'), '");';
			echo 'priceTypeArray = new Array("', join($priceTypeArray ,'","'), '");';
			echo 'currencyArray = new Array("', join($currencyArray ,'","'), '");';
			echo 'collectionArray = new Array("', join($collectionArray ,'","'), '");';
			echo 'itemTypeBehindArray = new Array("', join($itemTypeBehindArray ,'","'), '");';
			echo 'itemTypeArray = new Array("', join($itemTypeArray ,'","'), '");';
			echo "</script>";
			
			//now, if we're copying or modifying, we need to get all the values stored
			//for the promoCode with value $promoCode
			if(strcasecmp($action, "insert")!=0){
				
				//there will be multiple rows (possibly)
				//stored for this promoCode because this promoCode
				//may have multiple effects depending on the amount purchased, etc.
				//however, all rows with the same promoCode must have the same
				//expiryDate, releaseDate, mutex, type
				$sql = "SELECT *
						FROM promos
						WHERE promoCode LIKE '$promoCode'
						GROUP BY id";
						
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$counter = 0;
				while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
					
					$expiryDate = $row["expiryDate"];
					$releaseDate = $row["releaseDate"];
					$valueThatGetsReduced = $row["valueThatGetsReduced"];
					$mutex = $row["mutex"];
					$type = $row["type"];
					$id = $row["id"];
					
					array_push($arrayOfIds, $id);
					array_push($arrayOfRates, $row["rate"]);
					array_push($arrayOfDenominations, $row["denomination"]);//percentage or fixed
					
					$arrayOfCondsOps[$counter] = array();
					
					//now, once you have all of the ids,
					//an id for each possible effects,
					//you want to know which conditions go with each effect
					$sqlOps = "SELECT *
							   FROM promoSpecifics
							   WHERE id='$id'";
							   
					$resultOps = mysql_query($sqlOps, $con) or die(mysql_error());
					
					while($rowOps = mysql_fetch_array($resultOps, MYSQL_ASSOC) ){
						
						array_push($arrayOfCondsOps[$counter], array($rowOps["thing"], $rowOps["operator"], $rowOps["value"]) );

					}
					
					$counter++;
				}
				
				//we only want certain things to show for different valuesThatGetReduced
				//for example, if the valueThatGetsReduced is the cart total, then you want
				//the cart total and currency to be options; if it's product price, then you want
				//different options
				if(strcasecmp($valueThatGetsReduced, "cart total")==0){
					
					$thingArray = array("cart total", "currency");
				}
				else{
					$thingArray = array("price", "brand", "category", "collection", "priceType", "itemType", "currency", "isNew");
				}
				
				echo "<script type='text/javascript'>";
				echo 'thingArray = new Array("', join($thingArray ,'","'), '");';
				echo "</script>";
				
				if(strcasecmp($expiryDate,"0000-00-00")==0){
				
					$expiryDate = "YYYY-MM-DD";
				}
				
				if(strcasecmp($releaseDate,"0000-00-00")==0){
					
					$releaseDate = date("Y-m-d");
			
				}
				
				$expiryDateArray = explode("-", $expiryDate);
				$expiryYear = $expiryDateArray[0];
				$expiryMonth = $expiryDateArray[1];
				$expiryDay = $expiryDateArray[2];
				
				$releaseDateArray =  explode("-", $releaseDate);
				$releaseYear = $releaseDateArray[0];
				$releaseMonth = $releaseDateArray[1];
				$releaseDay = $releaseDateArray[2];
									
				//at this point we have all information for each row stored with the promoCode defined by $promoCode
				
			}
			//now, for inserts and copies, we need to generate a promoCode
			//10 random digits/uppercase letters
			if(strcasecmp($action, "modify")!=0){
				
				$foundUniqueId = false;
				
				while(!$foundUniqueId){
				
					//choose a 10 digit id that is comprised of capital letters and numbers
					$promoCode = "";
					
					for($i=0; $i<10; $i++){
						
						$char = rand(55, 90);
						
						if($char<65){
							
							$char = $char-55;
													
						}
						else{
							
							$char = chr($char);
													
						}
						
						$promoCode=$promoCode.$char;
					}
					
					$sql = "SELECT promoCode
							FROM promos
							WHERE promoCode LIKE '$promoCode'";
					$result = mysql_query($sql, $con) or die(mysql_error());
					
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					
					if(empty($row)){
						
						$foundUniqueId = true;
					}
				}
			
			}//end if
			
		?>
		
	</head>
	
	<body>
		<form name='promoForm' method='post' action='promosStep1.php' onsubmit='return validate();'>
		<div id='wrapper'>
			<!--we want the pass the number of original (those that were there already) effects to the processing part-->
			<input type='hidden' name='numberOfEffects' value='<? echo count($arrayOfIds); ?>' />
			<input type='hidden' name='action' value='<? echo $action ?>' />
			<div class='promosStep0innerWrapperDiv'>
			
				<p class='promosStep0p'>Promo Code: <input type='text' name='promoCode' value='<?echo $promoCode?>' /></p>
				
				<p class='promosStep0p'>Release Date (YYYY-MM-DD): <input type='text' name='releaseYear' value='<? echo $releaseYear ?>'>-
								<input type='text' name='releaseMonth' value='<? echo $releaseMonth ?>'>-
								<input type='text' name='releaseDay' value='<? echo $releaseDay ?>'>
				</p>
				
				<p class='promosStep0p'>Expiry Date (YYYY-MM-DD): <input type='text' name='expiryYear' value='<? echo $expiryYear ?>'>-
								<input type='text' name='expiryMonth' value='<? echo $expiryMonth ?>'>-
								<input type='text' name='expiryDay' value='<? echo $expiryDay ?>'>
				</p>
				
				<p class='promosStep0p'>Promo Type:
					<select name='type' onchange='typeWarning()'>
					<?
						foreach($typeSelectionArray as $typ){
									
							$selected = "";
							
							if(strcasecmp($type, $typ)==0){
								
								$selected = "selected";
							}
							$str.="<option value='".$typ."' $selected>".$typ."</option>";
						}
						echo $str;
					?>
					</select>
					<div id='warningDiv'>
					</div>
				</p>
				<p class='promosStep0p'>Can be combined with other "combinable" promos? 
					<?
						if($mutex==0){
							echo "<input type='radio' name='mutex' value='0' checked>Yes
								  <input type='radio' name='mutex' value='1'>No";
						}
						else{
							echo "<input type='radio' name='mutex' value='0'>Yes
								  <input type='radio' name='mutex' value='1' checked>No";
						}
					
					?>
				</p>
				<p class='promosStep0p' style='line-height: 135%; color: purple;'>Promo affects which kind of price? 
					<?
						echo $valueThatGetsReduced."<input type='hidden' name='valueThatGetsReduced' value='$valueThatGetsReduced' />";
					
					?>
				</p>
			</div>
			<?
				$str = "";
				//first we want to present all already stored effects/values for the promoCode
				//(of course this will only happen for modify/copy)
				for($i=0; $i<count($arrayOfRates); $i++){
					
					$rate = $arrayOfRates[$i];
					$denomination = $arrayOfDenominations[$i];
					
					$str.="<div id='effect".$i."' style='width: 950px; margin: 4px; border: 1px solid black;'>";
						$str.="<p class='promosStep0p'>
								Rate: <input type='text' name='rate".$i."' value='".$rate."' />
								<select name='denomination".$i."'>";
								
								foreach($denominationArray as $denom){
									
									$selected = "";
									
									if(strcasecmp($denomination, $denom)==0){
										
										$selected = "selected";
									}
									$str.="<option value='".$denom."' $selected>".$denom."</option>";
								}
								
						
						$str.="</select>
								</p>
							   <p class='promosStep0'>
									Add Operator <img src='images/plus.jpg' style='cursor: pointer;' onclick=\"addOperator(false, '".$i."')\">
									
							   </p>
							   <div id='operatorSection".$i."' style=''>";
								
								$opCounter = 0;
								
								foreach($arrayOfCondsOps[$i] as $condArray){
									
									$str.="<div id='op".$i.$opCounter."' style='margin: 2px; width: 940px;'>";
									
									$thing = $condArray[0];
									$operator = $condArray[1];
									$value = $condArray[2];
									
									$str.="<div style='width: 185px; float: left;margin: 5px;'>
											<select name='thing".$i.$opCounter."' onchange=\"getAppropriateOpValue(false, '$i', '$opCounter', '', '')\">";
									
									foreach($thingArray as $th){
								
										$selected = "";
										
										if(strcasecmp($thing, $th)==0){
											
											$selected = "selected";
										}
										$str.="<option value='".$th."' $selected>".$th."</option>";
									}
									
									$str.="		</select>
											</div>
											<div style='width: 185px; float: left;margin: 5px;' id='operator".$i.$opCounter."'>
											<select name='operator".$i.$opCounter."'>";
											
									foreach($operatorArray as $op){
								
										$selected = "";
										
										if(strcasecmp($operator, $op)==0){
											
											$selected = "selected";
										}
										$str.="<option value='".$op."' $selected>".$op."</option>";
									}
									
									$str.="		</select>
											</div>
											<div style='width: 200px; float: left;margin: 5px;' id='value".$i.$opCounter."'>
												<input type='text' name='value".$i.$opCounter."' value='$value' />
											</div>
											
											<div style='width: 185px; float: left;margin: 5px;'>
											<span style='cursor: pointer' onclick=\"deleteOp('$i', '$opCounter', false)\">Delete?</span>
											</div>";
								
									$str.="<br id='br".$i.$opCounter."' style='clear:both'></div>";//closing op$opCounter
									
									$str.="<script type='text/javascript'>
												getAppropriateOpValue(false, '$i', '$opCounter', '$operator', '$value');
										   </script>";
									$opCounter++;
								}
								
						$str.="</div> "; //closing operator section
						$str.="<input type='hidden' name='numberOfOperators".$i."' value='".$opCounter."'>	
							</div>";//closing effect
				}	
				
				echo $str;
			?>
			<div id='addedEffectSection'>
			
			</div>
			<div class='promosStep0innerWrapperDiv'>
				<button type='button' onclick='addEffect()'>Add Effect</button>
			</div>
		</div>
		<input type='submit' name='submit' value='Submit!' />
		</form>
	</body>
	
</html>
