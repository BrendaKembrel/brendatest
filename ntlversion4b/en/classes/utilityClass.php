<?
	session_start();
	
	class Utility{
	
		
		public static function getCon(){
			
			$con = mysql_connect("localhost","bradocto","2445LisaBrenda!!");
			if (!$con)
			{
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db("bradocto_ntlversion4", $con);
			
			return $con;
		}
		
		public static function getPromoArray(){
			
			$promoArray = array();
			
			if(isset($_SESSION["promoArray"])){
			
				$promoArray = $_SESSION["promoArray"];
			}
			
			return $promoArray;
		}
		
		public static function getCurrency(){
			
			$currency = "CAD";
			
			if(isset($_SESSION["currency"])){
			
				$currency = $_SESSION["currency"];
			}
			
			return $currency;	
		}
		
		
	}

?>