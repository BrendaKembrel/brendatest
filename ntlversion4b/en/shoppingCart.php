<?	
	include("classes/utilityClass.php");
	include("classes/productClass.php");
	include("classes/promoClass.php");
	include("classes/colorClass.php");
	include("classes/fabricClass.php");
	include("classes/priceClass.php");
	//INCLUDES
	include("includes/connect.php");
	
	include("includes/functions/shoppingCartFunctionsB.php");
	include("includes/headercad.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

		<head>
		
			<?
				
			?>
			
			<meta http-equiv="content-type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="css/ntlcurrent.css" />
			
			
			<title>Now That's Lingerie Shopping Cart</title>
			
			<link rel="shortcut icon" href="http://www.nowthatslingerie.com/en/images/favicon.ico" />
			<script type="text/javascript" src="scripts/jquery.js"></script>
			
			<!--my javascript functions-->
			<script type='text/javascript'>
				
				$(document).ready(function(){
				
					updatePromoArea();
					
				});
				
				/*
				function confirmPromoSwitch($blah){
					confirm($blah);
				}
				*/
				
				//this function refreshes the number of items in cart
				//that is displayed to the user up top
				function refreshItemsInCart(){
					
					$.post(  "ajax/refreshItemsInCart.php", 
						{
							
						},
						function(responseText){ 
							
							$("#itemsInCart").html(responseText);
						},						
						"html"
					);
					
				
				}
				
				function confirmPromoSwitch($promoOld, $promoNew){
					
					$str = "These promos can't be combined.  Click 'OK' if you want to use "+$promoNew+" instead of "+$promoOld+".  Otherwise click 'Cancel'";
					if(confirm($str)){
						
						removePromo($promoOld);
						addPromoNew($promoNew);
					}
										
				}
				
				function addPromoNew($promoNew){
				
					$.post(  "ajax/addPromoNew.php", 
						{
							promo: $promoNew
						},
						function(responseText){ 
							
							$("#shoppingCartpromoComment").html(responseText);
							updatePromoArea();
						},						
						"html"
					);
				
				}
				
				//this function adds a promo
				function addPromo(){
				
					var promo = $("#shoppingCartpromoInput").val();
					
					$.post(  "ajax/addPromo.php", 
						{
							promo: promo
						},
						function(responseText){ 
							
							$("#shoppingCartpromoComment").html(responseText);
							updatePromoArea();
						},						
						"html"
					  );
					
					$("#shoppingCartpromoInput").val("");
				}
				
				function updatePromoArea(){
					
					$.post(  "ajax/updatePromoArea.php", 
						{
						
						},
						function(responseText){ 
							
							$("#shoppingCartpromoArea").html(responseText);
							refreshCart();//which also refreshes prices
							//refreshPrices()
						},						
						"html"
					  );
				
				}
				
				function removePromo($promo){
					
					$.post(  "ajax/removePromo.php", 
						{
							promo: $promo
						},
						function(responseText){ 
							
							$("#shoppingCartpromoArea").append(responseText);
							updatePromoArea();
							
						},						
						"html"
					  );
					
				}
				
				function remove($counter, $productId, $defaultColor, $color, $size){
					
					alert("Removing "+$counter+" "+$productId +" "+$defaultColor+" "+$color+" "+$size);
					$.post(  "ajax/removeFromCart.php", 
						{
							productId: $productId,
							defaultColor: $defaultColor,
							color : $color,
							size: $size
						},
						function(responseText){ 
							
							$("#shoppingCartshoppingBag").append(responseText);
							refreshCart();
						},						
						"html"
					  );
				
				}
				
				function updateQty($counter, $productId,  $defaultColor, $size, $color){
					
					$qty = $("#"+$counter).val();
					
					$.post(  "ajax/updateCart.php", 
						{
							qty: $qty,
							productId: $productId,
							defaultColor: $defaultColor,
							size: $size,
							color: $color
						},
						function(responseText){ 
							
							$("#shoppingCartshoppingBag").append(responseText);
							refreshCart();
						},						
						"html"
					  );
				
				}
				
				function refreshCart(){
					
					$.post(  "ajax/refreshCart.php", 
						{
							
						},
						function(responseText){ 
							
							$("#shoppingCartshoppingBag").html(responseText);
							refreshPrices(0);
							refreshItemsInCart();
						},						
						"html"
					  );
				
				}
				
				function refreshPrices($taxes){
					
					$.post(  "ajax/refreshPrices.php", 
						{
							taxes: $taxes
						},
						function(responseText){ 
							
							$("#shoppingCartpricing").html(responseText);
						
						},						
						"html"
					  );
					
				}
				
				function getTaxes(){
					
					refreshPrices($("#shoppingCarttaxValues").val());
				}
				
			</script>
			<!--end js functions-->
			
			<?
				//GLOBAL VARIABLES
				
				//$shipping
				$sqlShipping = "SELECT shipping
									FROM shipping";
								
				$resultShipping = mysql_query($sqlShipping, $con) or die(mysql_error());
				
				$rowShipping = mysql_fetch_array($resultShipping, MYSQL_ASSOC);
				
				$shipping = $rowShipping[shipping];
			
				//$link = "";
				if(isset($_REQUEST['link'])){
								
					$link = mysql_real_escape_string($_REQUEST['link']) ;
					$link = "details.php?id=".$link;
				}
				
				//$currency
				//since we need to decide whether we're displaying in CAD
				//or USD, we do so immediately
				
				$currency = "CAD";
				
				if(isset($_SESSION['currency'])){
					
					$currency = $_SESSION['currency'];
				}
				
				$promoArray = array();
				if(isset($_SESSION['promoArray'])){
					
					$p = $_SESSION['promoArray'];
				}
				
				
				//PHP FUNCTIONS
				
				
				
			?>
			
		
		</head>
		
		
		<body>
				 
	
		<div id="sitewrapper">
		
			<? include("includes/header.php"); ?>
			<? include("includes/menu.php"); ?>
			
			
			<div id='shoppingCartwrapper2'>
			
				<div id='shoppingCartpromotions'>
					<p id='shoppingCartpromoInst' style="width:188px;">
						If you have a promo code, enter it here, and then click Done.
					</p>
					
					<input type='text' name='promoInput' id='shoppingCartpromoInput'><button type='button' onclick='addPromo()'>Done!</button>
					<div id='shoppingCartpromoComment'>
					
					</div>
					<br style='clear:both'>
					<div id='shoppingCartpromoArea'>
					
					</div>
					<br style='clear: both'>
				</div>
				
				<div id='shoppingCartwrapper3'>
				
					<form name='shoppingCart' action='shoppingCart.php' method='POST'>
					
						<div id='shoppingCartshoppingBag'>
							
							<?displayShoppingBag($con);?>
							
						</div>
					
					</form>
					
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					
					<div id='shoppingCartotherInfo'>
						
						<div id='shoppingCartshoppingInfo'>
						
							<a href="http://www.nowthatslingerie.com"><img src='images/continueShopping1.png' alt='Continue Shopping'></a><br/>
							*If you have any trouble, please don't hesitate to contact us by clicking <a href="contact_us.php" style="font-weight:bold;color:#shoppingCart660099" target="_blank">here</a>.
							
						</div>
						
						<div id='shoppingCarttotalsinfo'>
							<div id='shoppingCarttotals'>
								<div id='shoppingCarttaxes'>
								
									<p style="font-size:12px;font-weight:bold;font-family:verdana,sans-serif;">If you're from Canada, please choose your province for an estimate of applicable taxes: </p>
									<select id='shoppingCarttaxValues' onchange='getTaxes()'>
										<option value='0'>None</option>
										<option value='5'>Alberta</option>
										<option value='12'>B.C</option>
										<option value='5'>Manitoba</option>
										<option value='15'>Nova Scotia</option>
										<option value='13'>New Brunswick</option>
										<option value='13'>Nfld. and Lab.</option>
										<option value='5'>N.W.T</option>
										<option value='5'>Nunavut</option>
										<option value='13'>Ontario</option>
										<option value='5'>P.E.I</option>
										<option value='13.925'>Quebec</option>
										<option value='5'>Saskatchewan</option>
										<option value='5'>Yukon</option>
									</select>
								</div>
								<br style='clear:both'>
								<div id='shoppingCartpricing'>
									
								</div>
								<br style='clear:both'>
							</div>
							
								<input type='image' name='proceed_to_checkout' src='images/checkout1.png' alt='Proceed to Checkout'>
						
						</div>
						
					</div>
					</form>
					<br style='clear:both'>
				</div>
				<br style="clear:both;" />
			
				<? include("includes/footer.php"); ?>
			</div>
			
		
		</div>
		
		<!--the google analytics part-->									
		<script type='text/javascript'>

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-1091489-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>	
		
		
		</body>
		
		</html>
