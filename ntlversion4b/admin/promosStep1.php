<?
	include("include/connect.php");
	
	$promoCode = mysql_real_escape_string($_POST["promoCode"] );
	$action =  mysql_real_escape_string($_POST["action"] );
	
	$numberOfEffects = mysql_real_escape_string($_POST["numberOfEffects"] );
	$numberOfAddedEffects = mysql_real_escape_string($_POST["numberOfAddedEffects"] );
	
	$expiryYear = trim( mysql_real_escape_string($_POST["expiryYear"] ) );
	$expiryMonth = trim( mysql_real_escape_string($_POST["expiryMonth"] ) );
	$expiryDay = trim( mysql_real_escape_string($_POST["expiryDay"] ) );
	
	$releaseYear = trim( mysql_real_escape_string($_POST["releaseYear"] ) );
	$releaseMonth = trim( mysql_real_escape_string($_POST["releaseMonth"] ) );
	$releaseDay = trim( mysql_real_escape_string($_POST["releaseDay"] ) );
	
	if(strcasecmp($expiryYear,"YYYY")==0 || $expiryYear == "" || strcasecmp($expiryMonth,"MM")==0 || $expiryMonth == "" || strcasecmp($expiryDay, "DD")==0 || $expiryDay == ""){
		$expiryDate = "0000-00-00";
	}
	else{
		$expiryDate = $expiryYear."-".$expiryMonth."-".$expiryDay;
	}
	
	if(strcasecmp($releaseYear,"YYYY")==0 || $releaseYear == "" || strcasecmp($releaseMonth,"MM")==0 || $releaseMonth == "" || strcasecmp($releaseDay, "DD")==0 || $releaseDay == ""){
		$releaseDate = "0000-00-00";
	}
	else{
		$releaseDate = $releaseYear."-".$releaseMonth."-".$releaseDay;
	}
	
	$type = $_POST["type"];
	
	$mutex = $_POST["mutex"];
	$valueThatGetsReduced = $_POST["valueThatGetsReduced"];
	
	//if we're modifying promos,
	//we want to delete all old rows associated
	//with the promo so we can insert the new ones
	//and replace them (this way, we won't have to worry 
	//about doing excess work to determine whether something was
	//deleted by the user)
	if(strcasecmp($action, "modify")==0){
	
		$sql = "SELECT id
				FROM promos
				WHERE promoCode LIKE '$promoCode'";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
		
			$id = $row["id"];
			
			$sqlDel = "	DELETE
						FROM promoSpecifics
						WHERE id = '$id'";
						
			$resultDel = mysql_query($sqlDel, $con) or die(mysql_error());
		}
		
		$sql = "DELETE 
				FROM promos
				WHERE promoCode LIKE '$promoCode'";
				
		$result = mysql_query($sql, $con) or die(mysql_error());	
	}
	
	//so first, for the effects that were already there
	//we perform an insert, ensuring that each effect has a unique id
	for($i=0; $i<$numberOfEffects; $i++){
		
		$denomination = $_POST["denomination".$i];
		$rate = trim( mysql_real_escape_string( $_POST["rate".$i] ) );
		
		if($rate==""||$rate == 0){
			//we ignore this effect
			//because the user hasn't inputted anything
			continue;
		}
		
		//we want a unique id
		$sql = "SELECT max(id) as max
				FROM promos";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$id = $row["max"]+1;
		
		//now we're ready to insert a row for our effect in the promos table
		$sql = "INSERT INTO 
				promos (promoCode, rate, denomination, valueThatGetsReduced, type, id, mutex, expiryDate, releaseDate)
				VALUES
				('$promoCode', '$rate', '$denomination', '$valueThatGetsReduced', '$type', '$id', '$mutex', '$expiryDate', '$releaseDate')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		echo "<font color='blue'>$sql <br/><br/></font>";
		//now we deal with all operators associated with an effect
		$numberOfOperators = $_POST["numberOfOperators".$i];
		
		echo "there are $numberOfOperators for effect $i<br/>";
		for($j = 0; $j< $numberOfOperators; $j++){
			
			//each effect has multiple operators
			//we also must ensure that these operators have a unique id
			$operator = $_POST["operator".$i.$j];
			$thing = $_POST["thing".$i.$j];
			$value = trim( mysql_real_escape_string( $_POST["value".$i.$j] ) );
			
			if($value==""){
				
				//the user didn't input any info,
				//so we skip this operator
				continue;
			}
			
			//we want a unique id for this row
			$sql = "SELECT max(uniqueId) as max
					FROM promoSpecifics";
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$uniqueId = $row["max"]+1;
			
			//now we're ready to insert this operator into the table
			$sql = "INSERT INTO
					promoSpecifics (uniqueId, id, thing, operator, value)
					VALUES
					('$uniqueId', '$id', '$thing', '$operator', '$value')";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			echo "<font color='pink'>$sql <br/><br/></font>";
		}
	}
	
	//now we do the inserts for all added effects
	//the process is identical
	for($i=0; $i<$numberOfAddedEffects; $i++){
		
		$denomination = $_POST["addeddenomination".$i];
		
		$rate = trim( mysql_real_escape_string( $_POST["addedrate".$i] ) );
		
		if($rate==""||$rate == 0){
			//we ignore this effect
			//because the user hasn't inputted anything
			continue;
		}
		
		//we want a unique id
		$sql = "SELECT max(id) as max
				FROM promos";
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$id = $row["max"]+1;
		
		//now we're ready to insert a row for our effect in the promos table
		$sql = "INSERT INTO 
				promos (promoCode, rate, denomination, valueThatGetsReduced, type, id, mutex, expiryDate, releaseDate)
				VALUES
				('$promoCode', '$rate', '$denomination', '$valueThatGetsReduced', '$type', '$id', '$mutex', '$expiryDate', '$releaseDate')";
				
		$result = mysql_query($sql, $con) or die(mysql_error());
		
		echo "<font color='red'>$sql <br/><br/></font>";
		
		//now we deal with all operators associated with an effect
		$numberOfOperators = $_POST["numberOfAddedOperators".$i];
		
		for($j = 0; $j< $numberOfOperators; $j++){
			
			//each effect has multiple operators
			//we also must ensure that these operators have a unique id
			$operator = $_POST["addedoperator".$i.$j];
			$thing = $_POST["addedthing".$i.$j];
			$value = trim( mysql_real_escape_string( $_POST["addedvalue".$i.$j] ) );
			
			if($value==""){
				
				//the user didn't input any info,
				//so we skip this operator
				continue;
			}
			
			//we want a unique id for this row
			$sql = "SELECT max(uniqueId) as max
					FROM promoSpecifics";
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$uniqueId = $row["max"]+1;
			
			//now we're ready to insert this operator into the table
			$sql = "INSERT INTO
					promoSpecifics (uniqueId, id, thing, operator, value)
					VALUES
					('$uniqueId', '$id', '$thing', '$operator', '$value')";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			echo "<font color='purple'>$sql <br/><br/></font>";
		}
	}

?>