<?
	include("../include/connect.php");
	
	$categoryId = mysql_real_escape_string( $_POST["categoryId"] );
	$categoryUrl = trim( mysql_real_escape_string( $_POST["categoryUrl"] ) ); 
	$redirectLink = trim( mysql_real_escape_string( $_POST["redirectLink"] ) );
	$categoryName = trim( mysql_real_escape_string( $_POST["categoryName"] ) ); 
	$categoryTitle = trim( mysql_real_escape_string( $_POST["categoryTitle"] ) ); 	
	$description = trim( mysql_real_escape_string( $_POST["description"] ) ); 
	
	if(strcmp($categoryId, "-1")==0){
		
		//if no categoryId is set, then we need to set a new one
		
		$sql = "SELECT max(categoryId) as max
				FROM category";
		
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$categoryId = $row["max"]+1;
		
		$sql = "INSERT INTO
				category (categoryId) VALUE ('$categoryId')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		//now, we need to set our js variable you'll find in ../subtypePropertiesCategory.php
		//to the new updated value.  Since this js variable will be used for things such as adding
		//a property to category
		echo "<script type='text/javascript'>
				categoryId = $categoryId;
			  </script>";
	}
	
	//now we can set our category values 
	$sql = "UPDATE category
			SET categoryUrl = '$categoryUrl',
			redirectLink = '$redirectLink',
			categoryName = '$categoryName',
			categoryTitle = '$categoryTitle',
			description = '$description'
			WHERE categoryId = '$categoryId'";
	
	$result = mysql_query($sql, $con) or die(mysql_error());

	echo "<span style='color:red'>Successfully added/modified category</span>";
?>