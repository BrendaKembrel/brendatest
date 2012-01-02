<?
session_start();

if(!isset($_SESSION['firstView'])||$_SESSION['firstView']!="no"){

	$ip = $_SERVER["REMOTE_ADDR"];

	include('includes/connect.php');
	include("includes/geoip.inc");
	
	$gi = geoip_open("includes/GeoIP.dat",GEOIP_STANDARD);
	
	//now we calculate the equivalent long
	//this ip would be stored as
	//we need to separate the ip into its 4 components
	$ipArray = explode(".", $ip);
	
	$equivLong = $ipArray[3] + ( $ipArray[2] * 256) + ($ipArray[1] * 256 * 256) + ($ipArray[0] * 256 * 256 * 256) ;
	
	//now we're only concerned with knowing if the user is Canadian or not
	//we basically check if equivLong is within one of the given ranges in the CadIp table
	//if so, we know they're Canadian and react accordingly.  Otherwise they're not and so 
	//we just display currencies in USD.
	
	$sql = "SELECT *
			FROM CadIp
			WHERE $equivLong >= startRange AND $equivLong <= endRange
			";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
	
	
	if (!empty($row)) {
	  
		$_SESSION['currency'] = "CAD";
		$_SESSION['firstView'] = "no";
	 
	}
	else{

		if(strcasecmp( geoip_country_name_by_addr($gi, $ip), "United States") == 0){
			
			$_SESSION['currency'] = "USD";
			$_SESSION['firstView'] = "no";
		}
		else{
			$_SESSION['currency'] = "CAD";
			$_SESSION['firstView'] = "no";
		}
	
	}
	geoip_close($gi);

}

//now we want to set the promoArray
if(!isset($_SESSION["promoArray"]) ){

	include('includes/connect.php');
	
	$_SESSION['promoArray'] = array();
	
	
	$date = date("Y-m-d");
	
	$sql = "SELECT promoCode
			FROM promos
			WHERE type LIKE 'sitewide with no promo'
			AND releaseDate<='$date'
			AND expiryDate > '$date'";
			
	$result = mysql_query($sql, $con) or die(mysql_error());
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC) ){
		
		array_push($_SESSION['promoArray'], $row["promoCode"]);
	}
	
}
?>