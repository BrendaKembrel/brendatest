<?

$con = mysql_connect("localhost","bradocto","2445LisaBrenda!!");
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("bradocto_ntlversion4", $con);
	
?>