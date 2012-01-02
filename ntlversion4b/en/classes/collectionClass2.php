<?

	class CollectionClass
	{
		//These tell us what type of collection we're looking for. They're the variables we need to populate first.
		private $collection;
		private $collectionID;
		private $brand;
		private $activationstatus = array();
		
		//Property of the Collection itself
		private $description;
		
		//All of the products in the collection. An array of Product objects.
		private $productsincollection = array();
			
			//$con: sql connection
			//$activationstatus: An array of the activation statuses you're looking for for the products
			//$collectionid: an int = the primary key of the collection table
			function __construct($con,$collectionid,$activationstatus, $currency, $promoArray){
				
				//Need to write a function for getting the brand and collection based on the collection ID.
			
			$this->activationstatus = $activationstatus;
			$this->con = $con;
			
			$this->description = $this->outputCollectionDescription($con,$collectionid);
			$this->collection = $this->outputCollectionName($con,$collectionid);
			$this->brand = $this->outputCollectionBrand($con,$collectionid);
			$this->collectionID = $collectionid;
			$this->productsincollection = $this->productsInCollection($con,$this->collection,$this->activationstatus, $currency, $promoArray);
				
			/*echo $this->description . "<br />";
			echo $this->collection . "<br />";
			echo $this->brand . "<br /><br />";
			
			for($a = 0;$a <count($this->productsincollection);$a++){
				echo $this->productsincollection[$a]->getProductName() . "<br />";
			}
			
			for($a = 0;$a <count($this->othercollections);$a++){
				echo $othercollections[$a] . "<br />";
			}*/
		}
		
		//Gets the collection description.
		
		
		function outputCollectionDescription($con,$collectionID){
			
			$sql = "SELECT description
					FROM collection
					WHERE collectionId LIKE '$collectionID'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $row['description'];
		}
		
		//Gets the collection name
		function outputCollectionName($con,$collectionID){
			$sql = "SELECT collection
					FROM collection
					WHERE collectionId LIKE '$collectionID'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			return $row['collection'];
		}
		
		//Gets the collection's brand
		function outputCollectionBrand($con,$collectionID){
			$sql = "SELECT brand
					FROM collection
					WHERE collectionId LIKE '$collectionID'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			return $row['brand'];
		}
		
		/////////////////////
		/////Parameters/////
		//collection = the collection name of the collection whose products you need.
		//activationstatus = An array of the activation status you're looking for for the products.
		
		/////Returns/////
		//An array of the products in the collection that also match up with the requested activation status.
		////////////////
		function productsInCollection($con,$collectionname,$activationstatus, $currency, $promoArray)
		{
			$arrayofproducts = array();;
			$activationstatuses = array();
			$candidatepasses = 0;
			$counter = 0;
			
			$brand = $this->brand;
			
			$sql = "SELECT itemNumber
					FROM product
					WHERE collection LIKE '$collectionname'
					AND brand LIKE '$brand'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			
				$candidate = new Product($con,$row['itemNumber'],"", $currency, $promoArray);
				
				$activationstatuses = $candidate -> getActivationStatuses();
				
				if($this -> compareActivationStatus($activationstatus,$activationstatuses))
				{
					$arrayofproducts[$counter] = $candidate;
					$counter = $counter + 1;
				}
			}
			
			return $arrayofproducts;
		}
		
		/////////////////////
		/////Parameters/////
		//status1 = Statuses you're looking to match up with
		//status2 = Statuses of the product you're looking up
		
		/////Returns/////
		//If any of the statuses match up, even only once, it returns true. Otherwise it returns false.
		////////////////
		
		function compareActivationStatus($status1,$status2){
		
			$candidatepasses = 0;
			
			for($a = 0; $a < count($status1); $a++) {
				for($b = 0; $b < count($status2); $b++){
					if(strcasecmp($status1[$a],$status2[$b]) == 0){
						$candidatepasses = 1;
						
						if($candidatepasses == 1){
							break;
						}
					}
					
					if($candidatepasses == 1){
						break;
					}
				}
			}
			
			if($candidatepasses == 1){
				return true;
			}
			else{ 						
				return false;
			}
		}
		
		function collectionActivity($collectionID,$activationstatus)
		{
		
			$collection = new CollectionClass($this->con,$collectionID,$activationstatus);
			 
			$allstatuses = array();
			
			for($a = 0;$a < count($collection -> productsincollection);$a++)
			{
				$activationstatuses = $collection -> productsincollection[$a] -> getActivationStatuses();
				
				for($b = 0; $b < count($activationstatuses); $b++)
				{
					array_push($allstatuses,$activationstatuses[$b]);
				}
			}
			$allstatuses = array_unique($allstatuses);
			
			return $allstatuses;
		}
		
		function isNew($collectionID)
		{
			//Step 1 - 3 = Run this -> productsInCollection
			//Step 4 = Run Brenda's function on the product object to check for isNew
			//Step 5 = If any of step 4 returns true, flip a switch and break out of the loop.
			//Step 6 = Returns true or false.
		}
		
		function otherCollections($numberofoutputs,$con,$brand,$activationstatus,$exclude){
		
			$arrayofcollections = array();
			$arrayofselectedcollections = array();
		
			$sql = "SELECT collectionId
					FROM collection
					WHERE brand LIKE '$brand'
					AND collection NOT LIKE '$exclude'";
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$status = $this -> collectionActivity($row['collectionId'],$activationstatus);
				
				if($this -> compareActivationStatus($status,$activationstatus))
				{
					array_push($arrayofcollections,$row['collectionId']);
				}
			}
			
			echo count($arrayofcollections) . "<br />";
			echo $numberofoutputs. "<br />". "<br />";
			
			if(count($arrayofcollections) < $numberofoutputs){
			
				foreach($arrayofcollections as $output)
				{
					echo $output . "<br />";
				}
			
				return $arrayofcollections;
			}
			
			while(count($arrayofselectedcollections) < $numberofoutputs){
				array_push($arrayofselectedcollections,rand(0,count($arrayofcollections) - 1));
				$arrayofselectedcollections = array_unique($arrayofselectedcollections);
			}
			
			foreach($arrayofselectedcollections as $output)
			{
				echo $output . "<br />";
			}
			
			return $arrayofselectedcollections;
		}
		
		//GETTERS
		
		function getCollectionID(){
			return $this->collectionID;
		}
		
		function getCollection(){
			return $this->collection;
		}
		
		function getBrand(){
			return $this->brand;
		}
		
		function getActivationstatus(){
			return $this->activationstatus;
		}
		
		function getDescription(){
			return $this->description;
		}
		
		function getProductsincollection(){
			return $this->productsincollection;
		}
		
	}


?>