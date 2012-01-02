<?
	include("../include/connect.php");
	
	if(isset($_POST["submit"])){
		
		$counter = $_POST["counter"];
		
		for($i=0; $i<$counter; $i++){
			
			$categoryTitle = $_POST["categoryTitle".$i];
			$categoryName = $_POST["categoryName".$i];
			$description = $_POST["description".$i];
			$categoryUrl = $_POST["categoryUrl".$i];
			$shortenedCategory = $_POST["shortenedCategory".$i];
			$itemTypeId = $_POST["itemTypeId".$i];
			$subtypeId = $_POST["subtypeId".$i];
			$defaults = $_POST["defaults".$i];
			$synonyms = $_POST["synonyms".$i];
			
			if($shortenedCategory!=""){
				
				echo "$categoryTitle <br/>
					$categoryName <br/>
					$shortenedCategory<br/>
					$description <br/>
					$categoryUrl <br/>
					$itemTypeId <br/>
					$subtypeId <br/>
					$defaults <br/>
					$synonyms <br/><br/>";
					
				$sql = "SELECT max(categoryId) as max
						FROM category";
				
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				if(empty($row)){
					
					$categoryId = 1;
				}
				else{
				
					$categoryId = $row["max"]+1;
				
				}
				
				$sql = "INSERT INTO category	
						(categoryId, categoryName, categoryTitle, description, categoryUrl, itemTypeId, subtypeId, shortenedCategory) 
						VALUES
						('$categoryId', '$categoryName', '$categoryTitle', '$description', '$categoryUrl', '$itemTypeId', '$subtypeId', '$shortenedCategory')";
						
				$result = mysql_query($sql, $con) or die(mysql_error());

				//NOW WE DEAL WITH INSERTING FEATURES
				
				$defaultsArray = explode(",", $defaults);
				
				if(substr($defaults, -1)==","){
						
						array_pop($defaultsArray);
				}
				
				$synonymsArray = explode("*", $synonyms);
				
				if(substr($synonyms, -1)=="*"){
						
						array_pop($synonymsArray);
				}
				
				$count = 0;
				
				foreach($defaultsArray as $default){
					
					$sql = "SELECT max(fid) as max
							FROM features";
							
					$result  = mysql_query($sql, $con) or die(mysql_error());
					
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					
					if(empty($row)){
					
						$fid = 1;
					}
					else{
						
						$fid = $row["max"]+1;
					}
						
					if($default!=""){
					
						$sql = "INSERT INTO features
						(categoryId, fid, defaultFeat) 
						VALUES
						('$categoryId', '$fid', '$default')";
						
						$result  = mysql_query($sql, $con) or die(mysql_error());
						
						$syns = $synonymsArray[$count];
					
						$synsArray = explode(",", $syns);
					
						if(substr($syns, -1)==","){
								
							array_pop($synsArray);
						}
						
						foreach($synsArray as $syn){
							
							if($syn!=""){
								
								$sql = "INSERT INTO 
									featuresSynonyms (fid, synonym)
									VALUES ('$fid', '$syn')";
									
								$result  = mysql_query($sql, $con) or die(mysql_error());
							
							}
						
						}
					}
						
					
					
					$count++;
				}//end foreach
				
				
				
			}
			
		}
		
	}
	
	$itemTypesArray = array();
	
	$subtypesArray = array();
	
	$sql = "SELECT *
			FROM subtype";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC)){
		
		$subtypesArray[$row["subtypeId"]]=$row["subtype"];
	
	}
	
	$sql = "SELECT *
			FROM itemType";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC)){
		
		$itemTypesArray[$row["itemTypeId"]]=$row["itemType"];
	
	}
	
	//also deal with features and their synonyms here
	
	mysql_select_db("bradocto_bltcms", $con);
	
	$sql = "SELECT *
			FROM category";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$style = "width:150px;margin:2px;float:left;";
	
	$str = "<form name='catStuff' action='categoryStuff.php' method='post'>
			<div id='wrapper' style='width: 1400px'>
				<div>
					<div style='$style'>
						Cat Name
					</div>
					<div style='$style'>
						Cat Url
					</div>
					<div style='$style'>
						Cat Title
					</div>
					<div style='$style'>
						Short. Cat.
					</div>
					<div style='$style'>
						Item Type
					</div>
					<div style='$style'>
						Subtype
					</div>
					<div style='$style'>
						Defaults
					</div>
					<div style='$style'>
						Synonyms
					</div>
				</div><br style='clear:both'>
			";
	
	$counter = 0;
	
	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC)){
		
		$catNum = $row["catNum"];
		$catTit = trim(stripslashes($row["catTit"]));
		$description = trim(($row["description"]));
		$title = trim(stripslashes($row["title"]));
		
		mysql_select_db("bradocto_ntlversion4", $con);
		
		$sql2 = "SELECT categoryUrl 
				FROM category
				WHERE categoryUrl LIKE '$catNum'";
				
		$result2 = mysql_query($sql2, $con ) or die(mysql_error());
		
		$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		if(!empty($row2)){
			
			continue;
		}
		
		mysql_select_db("bradocto_bltcms", $con);
		
		$itemType = $row["itemType"];
		
		switch($itemType){
			
			case(1):{
				
				if(strcasecmp($brand, "elita lingerie")==0){
					
					//mini camisole
					$itemType = 5;
				}
				else{
					
					$itemType = 1;
				}
				
				
			}break;
			case(2):{
				
				$itemType = 3;
				
			}break;
			case(3):{
				
				$itemType = 2;
				
			}break;
			case(4):{
				
				$itemType = 4;
				
			}break;
			case(5):{
				
				$itemType = 8;
				
			}break;
			case(6):{
				
				$itemType = 7;
				
			}break;
			case(8):{
				
				$itemType = 1;
				
			}break;
			case(9):{
				
				$itemType = 13;
				
			}break;
			case(10):{
				
				$itemType = 9;
				
			}break;
			case(11):{
				
				$itemType = 18;
				
			}break;
		
		}
		//
		$str.="<div>
				
				<div style='$style'>
					<input style='width: 120px' type='text' name='categoryName$counter' value=\"$catTit\">
				</div>
				<div style='$style'>
					<input type='text' style='width: 120px' name='categoryUrl$counter' value='$catNum'>
				</div>
				<div style='$style'>
					<input type='text' style='width: 120px' name='categoryTitle$counter' value=\"$title\">
				</div>
				<div style='$style'>
					<input type='text' style='width: 120px' name='shortenedCategory$counter' value=''>
				</div>
				<div style='$style'>
					<select name='itemTypeId$counter'>";
					
					foreach($itemTypesArray as $itemTypeId=>$itemTypeP){
						
						$selected = "";
						
						if($itemType==$itemTypeId){
							
							$selected = "selected";
						}
						
						$str.="<option value='$itemTypeId' $selected>$itemTypeP</option>";
					
					}
					
			$str.="			
					</select>
				</div>
				<div style='$style'>
					<select name='subtypeId$counter'>";
					
					foreach($subtypesArray as $subtypeId=>$subtype){
						
						$str.="<option value='$subtypeId'>$subtype</option>";
					
					}
			
			$description=str_replace("\"", "'", $description);
			$str.="			
					</select>
				</div>
				<div style='$style'>
					<input type='text' name='defaults$counter'>
				</div>
				<div style='$style'>
					<input type='text' name='synonyms$counter'>
				</div>
				</div>
				<input type='hidden' name='description".$counter."' value=\"".stripslashes($description)."\">
				<br style='clear:both'>
				";
		
		$counter++;
	}
	
	
	$str.="<input type='hidden' name='counter' value='$counter'>
	<input type='submit' name='submit' value='submit!'>
	</div>
	</form>";
	
	echo $str;
	mysql_select_db("bradocto_ntlversion4", $con);
	
	
?>