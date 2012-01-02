<?	
	include("../include/connect.php");
	
	$sql = "DELETE FROM subtype";
	
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	
	//for a bra
	$subtypeArray = array("strap features", "cup", "style", "other", "padding", "support", "wire", "closure");
	
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			echo "we get here with $subtype<br/>";
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	
	
	//for a camisole
	$subtypeArray = array("cut", "style", "other", "padding", "straps");
	
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	
	//for panties
	//cut could be high, low, medium
	//function = invisible, shapewear, etc
	//shape = boyleg, thong
	$subtypeArray = array("cut", "style", "other", "function", "shape");
	
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	
	//for chemises
	$subtypeArray = array("cut", "style", "other", "function", "length");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	
	//for a mini camisole
	$subtypeArray = array("strap features", "cup", "style", "other", "support", "closure", "back");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for dresses
	$subtypeArray = array("cut", "style", "other", "function", "length", "sleeves", "neckline", "back");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for tops
	$subtypeArray = array("cut", "style", "other", "function", "length", "sleeves", "neckline", "back");
foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for pants
	$subtypeArray = array("cut", "style", "other", "function", "shape", "length");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for teddies
	$subtypeArray = array("cut", "style", "cup", "strap", "other", "function", "shape");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for shorts
	$subtypeArray = array("cut", "style", "other", "function", "shape", "length");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for garters
	$subtypeArray = array("cut", "style", "other");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for bodysuits
	$subtypeArray = array("cut", "style", "cup", "strap", "other", "function", "shape");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for an accessory
	$subtypeArray = array("style", "other", "function");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for slips
	$subtypeArray = array("cut", "style", "cup", "strap", "other", "function", "shape", "length");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for robes
	$subtypeArray = array("cut", "style", "other", "function", "shape", "length");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for a corset
	$subtypeArray = array("cut", "style", "other", "padding", "straps", "cup");
	foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	//for leggings
	$subtypeArray = array("cut", "style", "other", "function", "shape", "length");
foreach($subtypeArray as $subtype){
		
		$sql = "SELECT subtype
				FROM subtype 
				WHERE subtype LIKE '$subtype'";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		if(!empty($row)){
			continue;
		}
		
		$sql = "SELECT max(subtypeId) as max
			FROM subtype";
	
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row =  mysql_fetch_array($result, MYSQL_ASSOC);
		
		$subtypeId = $row["max"]+1;
	
		$sql = "INSERT INTO
				subtype (subtypeId, subtype)
				VALUES ('$subtypeId', '$subtype')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
	}
	?>