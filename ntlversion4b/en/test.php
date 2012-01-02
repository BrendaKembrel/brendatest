<?
	include("classes/productClass.php");
	include("includes/connect.php");
	
	
	
	$product = new Product($con, "7652-victoria-low-rise-hipster-arianne-lingerie");
	
	echo $product->getProductName()." and also ".$product->getYear();
		
	echo "<br/><br/>";
	
	echo "the product is ".$product->getActivation();
	
	$array = $product->getActivationStatuses();
	
	foreach($array as $val){
	
		echo "<br/>Ac status ".$val."<br/>";
	}
	
	$product = new Product($con, "5139-no-wedgies-no-worries-hi-cut-brief-warners-lingerie");
	
	echo $product->getProductName()." and also ".$product->getYear();
		
	echo "<br/><br/>";
	
	echo "the product is ".$product->getActivation();
	
	$array = $product->getActivationStatuses();
	
	foreach($array as $val){
	
		echo "<br/>Ac status ".$val."<br/>";
	}
	
	
?>