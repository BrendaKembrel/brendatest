<?
	include("../include/connect.php");
	
	$categoryId = mysql_real_escape_string($_POST["categoryId"]);
	
	//our goal here is to double check the user's action
	//if the user tries to leave the browser without having 
	//inserted the category, but with having added properties to it,
	//we warn the user.  
	if($categoryId==-1){
		
		echo "true";
	}
	else{
		
		//we know that if the category meets all these conditions,
		//the user inserted properties to the category, but did not
		//actually save the category, so we warn them
		$sql = "SELECT *
				FROM category
				WHERE categoryId = '$categoryId'
				AND categoryName LIKE 'temp'
				AND categoryUrl LIKE ''
				AND categoryTitle LIKE ''
				AND description LIKE ''";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(empty($row)){
			
			//we're okay, return false
			return "false";
		}
		else{
			
			//we'll warn the user
			return "true";
		
		}
	
	}
	
?>