<script type='text/javascript'>
	
	function confirmValueThatGetsReduced(){
		
		if(confirm("Press OK if the promo affects the cart total. \n\n Press cancel if it affects individual product prices")){
			location.href="promosStep0.php?action=insert&valueThatGetsReduced=cart total";
		}
		else{
			
			location.href="promosStep0.php?action=insert&valueThatGetsReduced=product price";
		}
	
	}
	
	confirmValueThatGetsReduced();
</script>