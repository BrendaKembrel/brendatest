<script type='text/javascript'>
	
	$(document).ready(function(){
				
		refreshItemsInCart();
		
	});
	
	function changeCurrency($curr){
	
		$.post( "ajax/changeCurrency.php", 
				{	
					
					currency: $curr
					
				},  
				function(responseText){  
			
					$("#siteheader").append(responseText); 
					window.location.reload();
				},  
				"html" 
		);
	}
	
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
	
</script>

<div id="siteheader">
			
				<div id="sitelogodiv">
				
					<a href="/"><img src="images/logo.jpg" alt="Now That's Lingerie - Online Store Selling Brand Name Lingerie" /></a> 
				
				</div>
				
				<div id="sitepromodiv">
				
					<!--<a href="/"><img src="images/promo.jpg" alt="Promotion Image" /></a>-->
					
					<div style='float:left;margin:2px;'>
						<img style='cursor: pointer' src='http://www.nowthatslingerie.com/en/menu/flags/american-flag2.jpg' onclick="changeCurrency('USD')" />
					</div>
					<div style='float:left;margin:2px;'>	
						<img style='cursor: pointer' src='http://www.nowthatslingerie.com/en/menu/flags/canadian-flag2.jpg' onclick="changeCurrency('CAD')" />
					</div>
					<div style='float:left;margin:2px;'>	
						<img style='cursor: pointer' src='http://www.nowthatslingerie.com/en/menu/flags/international-flag2.jpg' onclick="changeCurrency('CAD')" />
					</div>
					<br style='clear:both' />
					<p id='itemsInCart'>
						
					</p>
				
				</div>
				
				<div id="siteloginandsearchdiv">
				
					<div id="sitelogindiv">
					
						<p class="sitewelcomemsg">
						
							Welcome, <span style="font-weight:bold;">DBUsername!</span>
							
							<a class="sitesignin" href="/">Sign Out</a>
						
						</p>
						
						<p class="siteuseroptions">
						
							<a href="/" class="siteoptionlinks">Order History</a> - 
							<a href="/" class="siteoptionlinks">My Profile</a> - 
							<a href="/" class="siteoptionlinks">My Subscriptions</a>
						
						</p>
						
						<p class="siterewards">
						
							My Reward Points - <span style="font-weight:bold;">2614</span>
						
						</p>
					
					</div>
					
					<div id="sitesearchcontainer" style="background-image:url('images/searchbar.jpg');background-repeat:no-repeat;">
				
						<form method='get' action='searchQuery.php'>
								
											<p><input type='text' name='search' class="sitesearchbar" />
											<input type='image' src="images/searchbutton.png" value='Submit' /></p>
						
						</form>
					
					</div>
				
				</div>
				
				<div id="sitebagdiv">
				
					<a href="shoppingCart.php"><img src="images/bag.jpg" alt="View Shopping Bag" /></a>
				
				</div>
				
				<br style="clear:both;" />
			
			</div>