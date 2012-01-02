<?
	session_start();
	
	function getPromoArrayElement($promoArray){
		
		foreach($promoArray as $value){
			
			if($value!=""){
				
				return $value;
			}
		}
		
		return "";
	
	}
	
	include("../includes/connect.php");
	
	$promo = trim( mysql_real_escape_string($_POST[promo]) );
	
	$date = date("Y-m-d");
	
	
	$sql = "SELECT *	
			FROM promos
			WHERE promoCode LIKE '$promo'
			AND expiryDate>='$date'";
	
	$result =  mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(empty($row)){
		
		echo "<span style='color:red;font-size: 12px;'>Promo $promo doesn't exist.</span>";
	}
	else{
		
		//we know that the promo is mutually exclusive
		$mutex = $row["mutex"];
		
		
		if(isset($_SESSION['promoArray'])){
		
			if(in_array($promo, $_SESSION['promoArray'])){
				
				echo "<span style='color:red;font-size: 12px;'>Promo $promo already added!</span>";
				
			}
			else{
				
				if(!empty($_SESSION['promoArray'])){
					$elem = getPromoArrayElement($_SESSION['promoArray']);
				}
				else{
					
					$elem = "";
				}
				
				if($mutex && $elem!="" ){
					
					echo "<script type='text/javascript'>
								confirmPromoSwitch('".$elem."', '".$promo."')
							</script>";
					
				}
				else{
					
					array_push($_SESSION['promoArray'] , $promo);
					echo "<span style='color:green;font-size: 12px;'>Promo $promo added!</span>";
				
				}
				
			}
		}
		else{
			
			$_SESSION['promoArray'] = array();
			array_push($_SESSION['promoArray'] , $promo);
			echo "<span style='color:green;'>Promo $promo added!</span>";
		}
	}
	
	
?>