<?
	session_start();
	include("../includes/connect.php");
	
	if(isset($_SESSION['promoArray'] ) ){
		
		$str = "";
		
		foreach($_SESSION['promoArray'] as $promo){
			
			if($promo==""){
				
				continue;
			}
			
			//we don't want sitewide with no promo
			//promos to show up in the shopping cart
			//so if one of the promos stored in the promo array
			//is part of the shopping cart, we ignore it
			
			$sql = "SELECT *
					FROM promos
					WHERE promoCode LIKE '$promo'
					AND type LIKE 'sitewide with no promo'";
	
			$result =  mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
			if(empty($row)){
				
				//then that means that this specific promo is not a sitewide no promo
				//so we display it
			
				$str.= "	
					<div id='promo".$promo."' >
						<div style='float:left' >
							<span style='font-size: 10px;'> $promo   </span>
						</div>
						<div style='float: left; cursor: pointer;' onclick=\"removePromo('$promo')\" >
							<span style='font-size: 10px;color: red;' >   Remove</span>
						</div>
					</div>
					<br style='clear: both'>
					";
			}
		}
		
		echo $str;
	}	
	else{
	
		echo "";
	}

?>