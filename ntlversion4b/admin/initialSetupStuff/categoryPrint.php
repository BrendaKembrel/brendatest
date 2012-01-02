
<div id='wrapper' style='width:960px'>
<?
	
	include("../include/connect.php");
	
	$str="
		  <div style='margin:2px;float:left;width:250px'>
			Title
		  </div>
		  <div style='margin:2px;float:left;width:440px'>
			Features
		  </div>
		  <br style='clear:both'>
		  ";
		  
	mysql_select_db("bradocto_bltcms", $con);
	
	$sql = "SELECT *
			FROM category";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$counter=0;
	
	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC)){
		
		$catNum = $row["catNum"];
		$catTit = trim(stripslashes($row["catTit"]));
		
		if(stripos($catTit, "arianne")!==false||stripos($catTit, "elita")!==false||stripos($catTit, "change")!==false
		||stripos($catTit, "naturana")!==false||stripos($catTit, "eva ")!==false||stripos($catTit, "calvin")!==false
		||stripos($catTit, "olga")!==false||stripos($catTit, "triumph")!==false||stripos($catTit, "valisere")!==false
		||stripos($catTit, "grenier")!==false||stripos($catTit, "papillon")!==false||stripos($catTit, "blush")!==false
		||stripos($catTit, "bra doctor")!==false||stripos($catTit, "clearance")!==false||stripos($catTit, "sale")!==false){
		
			continue;
		}
		
		if($catTit==""){
			
			continue;
		}
		if(is_numeric(substr($catTit, 0, 1))){
			continue;
		}
		
		if($counter%2==0){
		
			$bg = "background-color: white;";
		}
		else{
			
			$bg = "background-color: EEEEEE;";
		}
		
		$str .="
		  <div style='".$bg."margin:2px;float:left;width:250px'>
			$catTit
		  </div>
		  <div style='".$bg."margin:2px;float:left;width:300px'>
			<input type='text' style='width:440px'>
		  </div>
		  
		  <br style='clear:both'>";
		  
		  $counter++;
	
	}
	
	echo $str;

?>
</div>