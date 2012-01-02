<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
   
<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/ntladmincurrent.css" />
		
		<?
			//includes
			include("include/connect.php");
			
			
		?>
		
	</head>
	
	<body>	
		
		<div id='wrapper'>
			
			
		<?
			$str = "";
			
			$sql = "SELECT *
					FROM product
					ORDER BY brand ASC, collection ASC, modelNumber ASC
					";
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				$str .= 
				"<div style='width: 900px;'>";
					
				$itemNumber = $row["itemNumber"];
				$productId = $row["productId"];
				$modelNumber = $row["modelNumber"];
				$collection = $row["collection"];
				$brand = $row["brand"];
				
				$str.="<div style='float: left;width: 50px; margin: 2px; border: 1px solid black; padding: 2px;'>
							<a href='productStep0.php?productId=$productId&action=modify' target='_blank'>$productId</a>
					   </div>
					   <div style='float: left;width: 200px; margin: 2px; border: 1px solid black; padding: 2px;'>
							<a href='productStep0.php?productId=$productId&action=modify' target='_blank'>$brand</a>
					   </div>
					   <div style='float: left;width: 70px; margin: 2px; border: 1px solid black; padding: 2px;'>
							<a href='productStep0.php?productId=$productId&action=modify' target='_blank'>$modelNumber</a>
					   </div>
					   <div style='float: left;width: 200px; margin: 2px; border: 1px solid black; padding: 2px;'>
							<a href='productStep0.php?productId=$productId&action=modify' target='_blank'>$collection</a>
					   </div>
					    <div style='float: left;width: 200px; margin: 2px; border: 1px solid black; padding: 2px;'>
							<a href='productStep0.php?productId=$productId&action=modify' target='_blank'>$itemNumber</a>
					   </div>
					   <br style='clear: both' />";
				
				$str.="</div>";
			
			}
			
			echo $str;
		?>
		
		</div>
	</body>
	
</html>