<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
   
<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/ntladmincurrent.css" />
	
		<!--first the javascript/jquery stuff goes here-->
		<script type='text/javascript'>
			
			//this value will be used for adding properties to the
			//current category.  It will be set to -1 if the category
			//isn't yet in the database (ie: for a copy or insert)
			//and it will have a relevant value otherwise.  Note that, as soon 
			//as a property is added to a category that doesn't exist yet, 
			//a row will be created in the cateogry with name = temp
			//and a unique categoryId
			var categoryId = -1;
			
			$(document).ready(function(){
				
				$("[name='itemTypeSelector']option[value='1']").attr("selected", "selected");
				changeDisplayedSubtypes();
				populateCategory();
				
			});
			
			
			/*
			if (window.addEventListener) {
				window.addEventListener('unload', closedWindow, false);
			} else if (window.attachEvent) {
				// pre IE9 browser
				window.attachEvent('onunload', closedWindow);
			}
			
			window.onbeforeunload=function() {
			  alert('Handler for .unload() called.');
			}
			*/
			//before the user exits the browser, we must perform a check
			//if the user added properties to the category, but did not save
			//the category, then the category will exist, but the user won't have saved
			//any related info, so we warn them.  Also, if the categoryId is -1, we also warn them
			//because clearly they did not in that case save their changes
			
			
	/*
			var closedWindow = function() {
				//code goes here
			
			$theText = "";
				$.post("ajax/doNoSaveCheck.php",
			  {
				categoryId: categoryId
			  },
			  function(responseText){
				
				$("#catPropertiesSection").append(responseText);
				$theText = responseText;
			  },
			  "html");
			  
			  if($theText=="true"){
				
				if(confirm("Are you sure you want to exit without saving?")){
					
					doCleanup();
				}
				
			  }
			  else{
			  
				alert("baba black sheep");
			  }
			};

			if (window.addEventListener) {
				window.addEventListener('unload', closedWindow, false);
			} else if (window.attachEvent) {
				// pre IE9 browser
				window.attachEvent('onunload', closedWindow);
			}
						
			window.onbeforeunload = function (e) {

			  e = e || window.event;
				
			  $.post("ajax/doNoSaveCheck.php",
			  {
				categoryId: categoryId
			  },
			  function(responseText){
				
				$("#catPropertiesSection").append(responseText);
			  },
			  "html");
			  
			  if(responseText=="true"){
				
				if(confirm("Are you sure you want to exit without saving?")){
					
					doCleanup();
				}
				
			  }
			  else{
			  
				alert("baba black sheep");
			  }
			  
			  // For IE and Firefox prior to version 4
			  if (e) {
				e.returnValue = 'Any string';
			  }

			  // For Safari
			  return 'Any string';
			};
			*/
			//this function deletes the current category
			//including all references to it
			//(this will only happen if the category's name is set to temp
			//and all other info is blank other than the properties)
			function doCleanup(){
			
				alert("we do cleanup");
			}
			
			//this function does the following:
			//upon changing the itemType from the 
			//dropdown menu, it will display the 
			//appropriate subtypes for that itemType
			function changeDisplayedSubtypes(){
				
				$.post(  "ajax/getSubtypesGivenItemTypes.php", 
					{	
						
						itemTypeId: $("[name='itemTypeSelector']").val()
						
						
					},  
					function(responseText){  
						
						$("#subtypePropertiesDiv").html(responseText);
						
					},  
					"html" 
				);
			
			}
			
			//this function adds a property to the
			//subtype defined by $subtypeId
			function addNewProperty($subtypeId){
				
				$.post("ajax/addNewProperty.php",
				{
					subtypeId: $subtypeId,
					property: $("[name='newProperty"+$subtypeId+"']").val()
					
				},
				function(responseText){
					
					//we remove the old error msg
					$("#errorMsg").remove();
					$("#properties"+$subtypeId).append(responseText);
					$("[name='newProperty"+$subtypeId+"']").val("");
				},
				"html");
			}
			
			//this function removes a property from a subtype
			//thus also deleting it from the database, and any occurrence 
			//in the propertiesCategory table
			function removeProperty($propertyId, $property){
				
				if(confirm("Are you sure you want to delete "+$property+"?  This means that all categories referring to this property will no longer refer to it.") ){
					
					$.post("ajax/removeProperty.php", 
						{
							propertyId: $propertyId
						},
						function(responseText){
							
							//we need to do this step first because
							//the responseText has to go somewhere
							//note that the ajax file will remove all database
							//relations to this property
							$("#"+$propertyId).html(responseText);
							
							//we now remove the property from its subtype
							//as displayed to the user
							$("#"+$propertyId).remove();
							
							//we also need to remove the property from its category
							//as displayed to the user
							//we do this just in case
							if($("#propertyInCateg"+$propertyId)!=null){
								
								$("#propertyInCateg"+$propertyId).remove();
							}
						},
						"html"
					);
					
					
				}
				else{
					
					//do nothing
				}
			
			}
			
			//this function adds the property defined by 
			//propertyId to the current category.
			//It does this in 2 ways:
			//1) it creates a row in the propertiesCategory table
			//2) it displays this added property on the properties area of the page
			function addPropertyToCateg($propertyId, $property){
				
				$.post("ajax/addPropertyToCategory.php",
					{
						
						propertyId: $propertyId,
						property: $property,
						categoryId: categoryId
					},
					function(responseText){
					
						$("#catPropertiesSection").append(responseText);
					
					},
					"html"
					);
			
			}
			
			//the following function removes the property
			//defined by propertyId from the category
			//1) it removes the corresponding row from the propertiesCategory table
			//2) it removes the property from the catPropertiesSection div
			function removePropertyFromCateg($propertyId){
				
				$.post("ajax/removePropertyFromCateg.php",
					{
						
						propertyId: $propertyId,
						categoryId: categoryId
					},
					function(responseText){
					
						$("#catPropertiesSection").append(responseText);
					
					},
					"html"
					);
				
				$("#propertyInCateg"+$propertyId).remove();
			}
			
			//this function is used to add a new subtype
			function addNewSubtype(){
				
				//This function adds a new subtype with the name given
				//with the current selected itemType
				$.post("ajax/addNewSubtype.php",
					{
						
						itemTypeId: $("[name='itemTypeSelector']").val(),
						subtype: $("[name='newSubtype']").val()
						
					}, 
					function(responseText){
						
						//we remove the old error msg
						$("#errorMsg").remove();
						
						//we append the new subtype info to 
						//the subtypePropertiesDiv
						$("#subtypePropertiesDiv").append(responseText);
						
						//we also clear the subtype input text field for the user
						$("[name='newSubtype']").val("");
						
					},
					"html"
					);
			}
			
			//this function removes a subtype
			//BUT ONLY IF ALL ASSOCIATED PROPERTIES ARE ALREADY REMOVED
			function removeSubtype($subtypeId, $subtype){
				
				if(confirm("Are you sure you want to remove "+$subtype+"?  This will only be removed if there are no associated properties.")){
					
					$.post("ajax/removeSubtype.php",
					{
						subtypeId: $subtypeId,
						subtype: $subtype
					}, 
					function (responseText){
						
						$("#"+$subtype+$subtypeId).append(responseText);
						//then we know we can also remove the subtype
						//as it is displayed to the user
						if(responseText=="true"){
						
							$("#"+$subtype+$subtypeId).remove();
						}
					
					},
					"html");
					
				}
			
			}
			
			//this function is used when a category is being
			//modified
			//it populates the category with its current properties
			function populateCategory(){
			
				$.post("ajax/populateCategoryTags.php",
					 {
						categoryId: categoryId
					},
					function(responseText){
					
						$("#catPropertiesSection").html(responseText);
					},
					"html");
			
			}
			
			//this function will add or modify the category in the database
			function addModifyCategory(){
				
				//pass the categoryId over so that we know
				//whether we're going to have to insert or modify!
				//just use categoryId global js var
				//I created the file, just need to do this part
				//and then the closing of the file triggering deletes of 
				//temps then done
				
				$.post("ajax/addModifyCategory.php",
					{
						categoryId: categoryId,
						categoryUrl: $("[name=categoryUrl]").val(),
						redirectLink:  $("[name=redirectLink]").val(),
						categoryName:  $("[name=categoryName]").val(),
						categoryTitle:  $("[name=categoryTitle]").val(),
						description: $("[name=description]").val()
						
					},
					function (responseText){
						
						$("#addModCateg").html("");
						$("#addModCateg").html(responseText);
					},
					"html");
			
			}
		</script>
		
		<?
			include("include/connect.php");
			
			$action = "insert";
			$category = "";
			
			if(isset($_GET["action"]) ){
			
				$action =  mysql_escape_string($_GET["action"]);
				
				if(isset($_GET["category"]) ){
					
					$category =  mysql_escape_string($_GET["category"]);
				}
				
			}
			
			//now, if we know what category it is, then
			//we can get info for the category from the db
			$categoryName = "";
			$categoryUrl = "";
			$categoryTitle = "";
			$redirectLink = "";
			$categoryDescription = "";
			$categoryId = -1;
			
			if($category!=""){
				
				$sql = "SELECT *
						FROM category
						WHERE categoryId = '$category'";
				
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				$categoryName = stripslashes( $row["categoryName"] );
				$categoryUrl = $row["categoryUrl"];
				$redirectLink = $row["redirectLink"];
				$categoryTitle = stripslashes( $row["categoryTitle"] );
				$description = stripslashes( $row["description"] );
				
				if(strcasecmp($action, "modify")==0){
					
					$categoryId = $category;
				}
				
			}
			
			
			//this is the itemTypeArray used for the dropdown
			$itemTypeArray = array();
			
			$sql = "SELECT *
					FROM itemType
					ORDER BY itemType ASC";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
			
				$itemTypeArray[$row["itemTypeId"]] = $row["itemType"];
			
			}
			
		?>
		
	</head>
	
	<body>
		<!--before anything, we set our js categoryId var
		to the current value.  It will have value -1 if we're dealing with a copy
		or insert, but a relevant value otherwise-->
		
		<script type="text/javascript">
			
			var categoryId = <? echo $categoryId ?>;
			
		</script>
		
		<div id='wrapper'>
		
			<div id='subtypePropertiesCategorySubtypeDiv'>
				<p class='headerBox'>Subtype and Properties</p>
				<div>
					Item Type:  
					<select name='itemTypeSelector' onchange="changeDisplayedSubtypes()">
					<?	
						$str = "";
						foreach($itemTypeArray as $itemTypeId => $itemType){
							
							$selected = "";
							
							if(strcasecmp($itemType, "bras")==0){
								
								$selected = "selected";
							}
							
							$str.="<option value='".$itemTypeId."' $selected>".
									$itemType
								."</option>";
						}
						
						echo $str;
					?>
					</select>
				</div>
				
				<div id="subtypePropertiesDiv">
				
				</div>
				
				<p id="newSubtypeDiv">
					<input type="text" name="newSubtype" /><button type="button" onclick="addNewSubtype()">Add New Subtype</button>
				</p>
			</div>
			<div id='subtypePropertiesCategoryCategoryDiv'>
				<p class='headerBox'>Category</p>
				<p class='subtypePropertiesCategoryCategoryp'>
					Category Url (eg: full-figure-bras): 
					<input type='text' style='width: 300px;' name='categoryUrl' value='<?echo $categoryUrl?>' />
				</p>
				<p class='subtypePropertiesCategoryCategoryp'>
					Redirect Link (the categoryUrl of the category you want this to redirect to): <br/>
					For example, if this is old and has categoryUrl = a001 and you want that to now redirect instead 
					to the new category with categoryUrl all-bras, then you'd type all-bras here
					<input type='text' style='width: 300px;' name='redirectLink' value='<?echo $redirectLink?>' />
				</p>
				<p class='subtypePropertiesCategoryCategoryp'>
					Name (eg: Full Figure Bras): 
					<input type='text' style='width: 300px;' name='categoryName' value='<?echo $categoryName?>' />
				</p>
				<p class='subtypePropertiesCategoryCategoryp'>
					Category Title (eg: Full Figure Camisoles | Queen Size Camisoles) : 
					<input type='text' style='width: 300px;' name='categoryTitle' value='<?echo $categoryTitle?>' />
				</p>
				<p class='subtypePropertiesCategoryCategoryp'>
					Description: <textarea name='description' style='width: 450px; height: 200px;'><?echo $description ?></textarea>
				</p>
				
				<div id='catPropertiesSection'>
				
				</div>
				
				<br style="clear:both;"  />
				<div id='addModCateg'>
				</div>
				<!--if $category=="", we'll know where doing an insert; else we know it's a modify -->
				<p><button type='button' onclick="addModifyCategory()">Add/Modify Category</button>
			</div>
			
		
		</div>
	</body>
	
</html>
