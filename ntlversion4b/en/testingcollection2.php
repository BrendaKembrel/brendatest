<? 	session_start();

?>

		
		<script type="text/javascript" src="scripts/mainPageCartStuff.js"></script>
		<script type="text/javascript" src="scripts/tabber.js"></script>
		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="scripts/popup.js"></script>
		<script type="text/javascript" src="scripts/jquery.jcarousel.min.js"></script>
<?

	include("classes/fabricClass.php");
	include("classes/collectionClass.php");
	include("classes/productClass.php");
	include("classes/utilityClass.php");
	include("classes/priceClass.php");
	include("classes/promoClass.php");
	include("classes/colorClass.php");
	
	$promoArray = array();
	
	if(isset($_SESSION["promoArray"])){
		$promoArray = $_SESSION["promoArray"];
	}
	
	$currency = "CAD";
	
	if(isset($_SESSION["currency"])){
		$currency = $_SESSION["currency"];
	}
	
	$collectioninfo = new CollectionClass("26",array("released","unreleased","retired"));
	
	echo "<br /><b><u>Collection ID</u></b>:<br />";
	echo $collectioninfo -> getCollectionID() . "<br />";
	
	echo "<br /><b><u>Collection Name</u></b>:<br />";
	echo $collectioninfo -> getCollection() . "<br />";
	
	echo "<br /><b><u>Brand</u></b>:<br />";
	echo $collectioninfo -> getBrand() . "<br />";
	
	echo "<br /><b><u>Activation Status</u></b>:<br />";
	
	foreach($collectioninfo -> getActivationstatus() as $output)
	{
		echo $output . "<br />";
	}
	
	echo "<br /><b><u>Description</u></b>:<br />";
	echo $collectioninfo -> getDescription() . "<br />";
	
	echo "<br /><b><u>Products in Collection</u></b><br />";
	
	foreach($collectioninfo ->getProductsincollection() as $output)
	{
		echo $output -> getProductName() . "<br />";
	}
	
	$othercollections = CollectionClass::otherCollections("5",$collectioninfo -> getBrand(),$collectioninfo->getActivationstatus(),$collectioninfo -> getCollection());
	
	echo "<br /><b><u>Related Collections:</u></b><br />";
	
	for($a = 0; $a < count($othercollections); $a++)
	{
		echo $othercollections[$a]->getCollection() . "<br />";
	}
	
	echo "<br /><b><u>Collection has new items:</u></b><br />";
	
	if($collectioninfo -> hasNew($collectioninfo))
	{
		echo "true";
	}
	else
	{
		echo "false";
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/ntlcurrent.css" />
		
		<title><? echo ucwords($collectioninfo ->getCollection()) . " Collection by " .  ucwords($collectioninfo ->getBrand()); ?> | Now That's Lingerie</title>
		<meta name="description" content="Contact Now That's Lingerie's Bra Doctor for personalized fitting help, and speak with our customer support for questions and concerns" />
		<meta name="keywords" content="" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="scripts/tylerfunctions.js"></script>
		
	</head>

	<body>
		<div id="sitewrapper">
		
			<? include("includes/header.php"); ?>
			<? include("includes/menu.php"); ?>
			
			
			
			<div id="sitecontent">
			<!--Content Block-->
			
				<div style="width:996px;float:left;margin:4px 0 0 2px;">
				
					<p style="font-weight:bold;font-size:24px;font-family:georgia,serif;text-align:center;margin:0 150px 0 150px;"><? echo ucwords($collectioninfo ->getCollection()) . " Collection by " .  ucwords($collectioninfo ->getBrand()); ?></p>
					<p style="font-size:12px;font-family:verdana,sans-serif;line-height:135%;margin:4px 150px 0 150px;"><? echo $collectioninfo ->getDescription(); ?> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. </p>
				
				</div>
					
				<br style="clear:both;" />
				
				<div style="background-image:url('images/960gradient.png');background-color:#692260;width:980px;float:left;margin:10px 0 0 10px;padding-top:3px;padding-bottom:3px;">
				
					<p style="font-weight:bold;color:black;font-size:20px;font-family:georgia,serif;margin:0 25px 0 25px;">Styles in the <? echo ucwords($collectioninfo ->getCollection()) . " Collection";?></p>
				
				</div>
					
				<br style="clear:both;" />		
				
					<?
					$productsInCollection = $collectioninfo ->getProductsincollection();
					
					$str = "";
					
					$prodCounter = 0;
					
					foreach($productsInCollection as $output)
					{
						$currentproductid = $output->getProductId();
						$currentproductbrand = $output->getBrand();
						$currentproductname = $output->getProductName();
						$currentproductitemnumber = $output->getItemNumber();
						$currentproductlink = "details.php?id=". $currentproductitemnumber;
						$currentproductprice = $output->getPrice()->getFormattedPrice(
																		array("class='productlowertablink1'", "class='productlowertablink4'", "class='productlowertablink3'", "class='productlowertablink4'"),
																		$link,
																		true, 
																		false,
																		true);
						$currentproductimagepath = $output->getImagePath();
						$currentproductimage = $currentproductimagepath.$output -> getImage();
						
						$divStrOpener = "<div style='float:left; width: 180px; margin: 5px;'>";
						
						if($prodCounter%5 == 0){
						
							$divStrOpener = "<div style='float:left; width: 180px; margin: 5px 5px 5px 30px;'>";
						}
						
						$str.=
							$divStrOpener."
								<a href=".$currentproductlink." style = 'text-decoration: none;'>
								<div class='productlowertabimg'><img src='".$currentproductimage."' style='width: 180px;' /></div>
								</a>
								
								<div class='productlowertabtext'>
								
									<p>
									<a class='productlowertablink1' style='text-decoration: none;' href='".$currentproductlink."'>".$currentproductname.	"</a>
									</p>
									<p>
									<a class='productlowertablink2' style='text-decoration: none;' href='".$currentproductlink."'>".ucwords($currentproductbrand)."</a>
									</p>".
									$currentproductprice."
								
									<img style='cursor: pointer;' onclick=\"addToBagCollection('$currentproductid', '', '$currency', '$currentproductimagepath', '$currentproductimage')\" src='images/addtobag3.jpg' />
								
								</div>
								
							</div>";
							
							//
						
						if($prodCounter%5 == 4){
							
							$str.="<br style='clear: both' />";
						}
						
						$prodCounter++;
					} 
						
					$str.="<br style='clear: both' />";
					
					echo $str;
					?>
				
				
				<div style="background-image:url('images/960gradient.png');background-color:#692260;width:980px;float:left;margin:10px 0 0 10px;padding-top:3px;padding-bottom:3px;">
				
					<p style="font-weight:bold;color:black;font-size:20px;font-family:georgia,serif;margin:0 25px 0 25px;">Styles in the <? echo "Other Styles by " .  ucwords($collectioninfo ->getBrand());?></p>
					
				</div>
					
				<br style="clear:both;" />
				
				
					
						
			
			<!--/Content Block/-->
			</div>
			
			<? include("includes/footer.php"); ?>

		</div>
		
		<!--this is the area where pop ups will happen
		the background is what becomes opaque and black;
		contactArea is what gets filled with what gets popped up
		the x is for the closing button
		
		-->
		<div id='popupContact'>
			<div id='popupContactClose'>x</div>
			<div id='contactArea'>
			
			
			</div>
			
			<div id='popupContactClose2'>CLOSE</div>
		</div>
		<div id="backgroundPopup"></div>
		
	</body>
</html>