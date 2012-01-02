<?
	
	class Product{
		
		//this is the path to the images folder
		private $pathToImagesFolder = "http://nowthatslingerie.com/en/images/";
		
		//the following values are immediately available
		//from the product table
		private $productId;
		private $itemNumber;
		private $modelNumber;
		private $brand;
		private $productName;
		private $title;
		private $description;
		private $frenchDescription;
		private $braDoctorHelp;
		
		//this will be a collection object
		private $collection;
		
		//this will be a price object
		private $price;
		
		private $year;
		private $season;
		
		private $itemType;
		private $sizingFormat;
		
		//this has an array of related products
		private $relatedProducts;
		
		//this has to do with the default color of this product's image
		private $defaultColor;
		
		//this is the link that will be associated with the product
		//if it has a default color, then that must be included
		private $link;
		
		//*******************
		//the following values are available from the productColor table
		//this is an array of all colors associated with a product whether
		//active, new or not
		//this is an array of color objects
		private $colors = array();
		
		//*******************
		//the following values are available from the productFabric table
		//this is going to be an array of all fabric objects associated
		//with the product
		private $fabrics = array();
		
		//$con: sql connection
		//$itemNumber: string representing the itemNumber
		//$defaultColor: string representing the specific color
		//associated with this product (in the event of there being 
		//a special image for it).  Note that, if defaultColor is "",
		//then of course the default image is the image associated with 
		//the product
		//to get around whether the person constructs
		//with an itemNumber or id (since there's no method overloading in php)
		//I'll check if $itemNumberOrId is numeric.  If yes, then it can only be a product id
		//otherwise, it's clearly an itemNumber
		function __construct($itemNumberOrId, $defaultColor){
			
			//these are calling static methods of the Utility class
			$promoArray = Utility::getPromoArray();
			$currency = Utility::getCurrency();
			$con = Utility::getCon();
			
			if(is_numeric($itemNumberOrId) ){
				
				//then we know that $itemNumberOrId is 
				//a productId
				//and we must query using the productId
				$sql = "SELECT *
						FROM product
						WHERE productId = '$itemNumberOrId'";
			}
			else{
				
				//it's an itemNumber
				$sql = "SELECT *
						FROM product
						WHERE itemNumber LIKE '$itemNumberOrId'";
			}
			
			
					
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			if( !empty($row) ){
				
				$this->defaultColor = $defaultColor;
				$this->itemNumber = $row["itemNumber"];
				$this->productId = $row["productId"];
				$this->modelNumber = $row["modelNumber"];
				$this->brand = stripslashes( $row["brand"] );
				$this->productName = stripslashes( $row["productName"] );
				
				$this->description = stripslashes( $row["description"] );
				$this->frenchDescription = stripslashes( $row["frenchDescription"] );
				$this->title = stripslashes( $row["title"] );
				$this->braDoctorHelp = stripslashes( $row["braDoctorHelp"] );
				
				$this->collection = stripslashes($row["collection"]);
				
				$this->price = new Price($row["price"], $row["finalPrice"], $row["priceType"], $row["canPrice"], $row["canFinalPrice"], $row["canPriceType"], $this);
				
				$this->year = $row["year"];
				$this->season = strtolower( $row["season"] );
				
				$this->itemType = $row["itemType"];
				//we need the sizingFormat because,
				//when a user is going to get all size for a certain color
				//if the product first had one sizingFormat, then it switched
				//we need to make sure we're searching for sizes of the product
				//in the current sizing format
				$this->sizingFormat = $row["sizingFormat"];
				
				$sqlColor = "SELECT *
							FROM productColor
							WHERE productId LIKE '".$this->productId."'";
							
				$resultColor = mysql_query($sqlColor, $con) or die(mysql_error());
				
				while( $rowColor = mysql_fetch_array($resultColor, MYSQL_ASSOC) ){
				
					$color = $rowColor["color"];
					$image = $rowColor["image"];
					$cid = $rowColor["cid"];
					$imageId = $rowColor["imageId"];
					$isNewUntil = $rowColor["isNewUntil"];
					$activationDate = $rowColor["activationDate"];
					$deactivationDate = $rowColor["deactivationDate"];
					
					//note: we need the sizingFormat because this will have to be passed down
					//all the way to the size level
					
					$colorTemp = new Color($this->productId, $this->sizingFormat, $color, $image, $cid, $imageId, $isNewUntil, $activationDate, $deactivationDate);
					
					array_push($this->colors, $colorTemp);
					
				}
				
				//here we will populate the product's array of fabrics
				//with the corresponding fabric objects
				$sqlFab = " SELECT *
							FROM productFabric
							WHERE productId = '".$this->productId."'";
				
				$resultFab = mysql_query($sqlFab, $con) or die(mysql_error());
				
				while( $rowFab = mysql_fetch_array($resultFab, MYSQL_ASSOC) ){
					
					$fabric = new Fabric($this->productId, $rowFab["fabric"], $rowFab["percentage"], $rowFab["type"]);
					
					array_push($this->fabrics, $fabric);
				}
			
			}
			else{
			
				//no such product exists
				$this->productId = -1;
			}
			
			
			if($defaultColor==""){
				$this->link = "id=".$itemNumber;
			}
			else{
				
				$this->link = "id=".$itemNumber."&defaultColor=".$defaultColor;
			}
			
		}
		
		//THESE FUNCTIONS DEAL WITH GETTING LISTS
		//OF OTHER PRODUCTS GIVEN SOME SPECIFICATION
		
		//this function returns an array of 
		//product objects with the same productId
		//as $this->productId
		//it works as follows:
		//a product with different colors
		//may have different images for these colors 
		//so, this takes in an arrayofstatuses; gets all colors
		//with the desired statuses and then checks if there is a specific
		//image associated with that color
		//if yes, it creates a product object having that defaultColor
		//and adds it to the final array that it will later return
		function getAllVersionsOfProducts($arrayofstatuses){
			
			//the array of applicable products we will be returning
			$arrayOfProducts = array();
			
			//we get the colors based on their activations
			$applicableColorsArray = $this->getColorsByActivation($arrayofstatuses);
			
			//now, we only want to have one product with the default image,
			//so we need a boolean to ensure that the default image is added only once
			//note that we can't just add a product with the default image (no default color,
			//in other words), because it is not necessarily true that a color that uses the default
			//image will have the desired activation level
			$defaultImageApprop = false;
			
			foreach($applicableColorsArray as $color){
				
				//we check if the color has a special
				//image associated with it.  If so,
				//then we know to create a product with that image
				if($color->hasSpecialImage()){
					
					array_push($arrayOfProducts, new Product($this->itemNumber, $color->getColor()));
					
				}
				else{
					
					//we can now add a product with a default image
					$defaultImageApprop = true;
				}
			
			}
			
			//now, if at least one color exists with the desired activation level
			//that uses the default image, we can add a product with the default image
			if($defaultImageApprop){
				array_push($arrayOfProducts, new Product($this->itemNumber, ""));
			}
			
			return $arrayOfProducts;
		}
		
		//THESE FUNCTIONS DEAL WITH IMAGES
		
		//this function returns an array of other
		//product views of the product (a max of 2)
		//ie: an array of strings representing images
		//note that if the product was presented with color=defaultColor
		//and it has another image other than the default image as its main image
		//then the default image will be in one of the product views
		//and its default image will not be one of the other product views of course
		//inputs: $con->sql connection; $defaultColor->String		
		function getOtherProductViews($defaultColor){
			
			//we statically get the sql connection
			$con = Utility::getCon();
			
			//first, we need to know what the default image
			//of the product is given its defaultColor
			$defImgOfProd = $this->getImageWithDefaultColor($defaultColor);
			$actualDefaultImage = $this->getDefaultImage();
			
			$productViews = array($defImgOfProd);
			
			$counter = 0;
			
			$sql = "SELECT image
					FROM images
					WHERE productId = '".$this->productId."' 
					AND type LIKE 'product view'
					AND image NOT LIKE '".$defImgOfProd."'";
			
			$result = mysql_query($sql, $con) or die(mysql_error());
			
			while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				
				array_push($productViews, $row["image"]);
				$counter++;
			}
			
			//if less than 2 images were added
			//and the default image is not the same
			//as the specific default image for this
			//version of the product, then we can add
			//the default image as a product view
			if($counter<2&&strcasecmp($actualDefaultImage, $defImgOfProd)!=0){
				
				array_push($productViews, $actualDefaultImage);
				
			}
			
			return $productViews;
		}
		
		//this function returns the image with the color=defaultColor
		//in the form blah.jpg
		function getImage(){
			
			return $this->getImageWithDefaultColor($this->defaultColor);
		}
		
		//this function returns the default image in the form blah.jpg
		function getDefaultImage(){
			
			//returns the default image
			return $this->getImageWithId(-1);
		}
		
		//this function takes in an imageId 
		//and returns the image associated with that id
		//if the imageId is -1 or 0, then return the default image
		function getImageWithId($imageId){
			
			//we statically get the sql connection
			$con = Utility::getCon();
			
			if($imageId==-1||$imageId==0){
				
				$sql = "SELECT image
						FROM images
						WHERE productId = '".$this->productId."' 
						AND type LIKE 'default'";
						
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				return $row["image"];
			
			}
			else{
				
				$sql = "SELECT image
						FROM images
						WHERE imageId = '".$imageId."'";
						
				$result = mysql_query($sql, $con) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				return $row["image"];
			}
			
		
		}
		
		
		//this function takes in the defaultColor
		//and returns the image associated with that color
		//if such an image exists; else it returns the default image
		//$con: the sql connection
		//$defaultColor: a string representing the color name
		function getImageWithDefaultColor($defaultColor){
			
			$imageId = -1;
			
			if($defaultColor!=""){
				//first we loop through our list of
				//color objects
				foreach($this->colors as $color){
				
					if(strcasecmp($defaultColor, $color->getColor())==0){
					
						//then the imageId associated with the defaultColor is:
						$imageId = $color->getImageId();
						break;
					}	
				}
			}
			
			return $this->getImageWithId($imageId);
		
		}
		
		//this function returns the image
		//even with the image path attached to it
		function getImageWithPath($defaultColor){
			
			$imagePath = $this->getImagePath();
			
			$image = $imagePath.$this->getImage();
			
			return $image;
		}
		
		//this function returns the path to the images
		function getImagePath(){
			
			$imagePath = $this->pathToImagesFolder;
			
			if($this->year!=""){
				
				$imagePath.=$this->year."/";
			}
			
			if($this->season!=""&&$this->season!="none"){
			
				$imagePath.=$this->season."/";
			}
			
			$imagePath.= str_replace(" ", "-", $this->brand)."/";
			
			return $imagePath;
		}
		
		//THESE FUNCTIONS DEAL WITH COLORS
		//**************************************************
		//this function returns an array of active color 
		//objects based on a size String
		function getColorsBySize($size){
			
			$colorsBySize = array();
			
			$activeColors = $this->getActiveColors();
			
			foreach($activeColors as $color){
			
				if( in_array($size, $color->getSizes()) ){
				
					array_push($colorsBySize, $color);
				}
				
			}
			
			return $colorsBySize;
		}
		
		//this function returns an array of colors objects
		//of the desired activation statuses
		//and of the desired size 
		//$size: string
		//$activationstatuses: array
		function getColorsBySizeWithAcStatus($size, $activationStatuses){
			
			$colorsBySize = array();
			
			$activeColors = $this->getColorsByActivation($activationStatuses);
			
			foreach($activeColors as $color){
			
				if( in_array($size, $color->getSizes()) ){
				
					array_push($colorsBySize, $color);
				}
				
			}
			
			return $colorsBySize;
		}
		//functions for getting special info
		//returns an array of all new colors
		//of the product
		public function getNewColors(){
			
			$arrayOfNewColors = array();
			
			//we loop through all color objects associated
			//with the color and we check if they're new
			foreach($this->colors as $color){
				
				if($color->isNew()){
					
					//then we add the color to the array
					array_push($arrayOfNewColors, $color);
				}
			}
			
			return $arrayOfNewColors;
		}
		
		
		//returns an array of all active colors
		public function getActiveColors(){
			
			$arrayOfColors = array();
			
			//we loop through all color objects associated
			//with the color and we check if they're released
			foreach($this->colors as $color){
				
				if(strcasecmp($color->getActivation(), "released")==0){
					
					//then we add the color to the array
					array_push($arrayOfColors, $color);
				}
			}
			
			return $arrayOfColors;
		}
		
		//this function returns an array of all colors
		//that have the activation levels defined by the array of
		//activation levels
		public function getColorsByActivation($arrayOfActivationLevels){
			
			$arrayOfColors = array();
			
			//we loop through all activation levels
			//and only add the color to the array if
			//it has the appropriate activation level
			foreach($arrayOfActivationLevels as $ac){
				
				foreach($this->colors as $color){
				
					if(strcasecmp($color->getActivation(), $ac)==0){
						
						//then we add the color to the array
						array_push($arrayOfColors, $color);
					}
				}
			}
			
			return $arrayOfColors;
		
		}
		
		//this function returns an array of colorswatches
		//(strings representing the img of each colorswatch)
		//that match the input array of color objects
		//input: array of color object
		//output: array of strings corresponding to their colorwatches
		public function getColorswatches($colorArray){
			
			$colorswatchesArray = array();
			
			foreach($colorArray as $color){
			
				array_push($colorswatchesArray, $color->getImage());
			}
			
			return $colorswatchesArray;
		}
		
		//this function returns an array of color names
		//(strings representing the name of each color)
		//that match the input array of color objects
		//input: array of color object
		//output: array of strings corresponding to their color names
		public function getColorNames($colorArray){
			
			$colornamesArray = array();
			
			foreach($colorArray as $color){
			
				array_push($colornamesArray, $color->getColor());
			}
			
			return $colornamesArray;
		
		}
		
		//THESE FUNCTIONS DEAL WITH SIZES
		//**************************************************
		
		//this function takes an array of sizes (in strings)
		//and returns an array of those sizes sorted
		//by size
		public function orderedSizeArray($sizeArray){
			
			//this is the array we will return in the end
			$orderedSizeArray = array();
			
			//this is the array that will hold the ids
			$arrayOfIdsInSizeDefault = array();
			
			//this is the array that holds a correlation between
			//ids and sizes
			$specialArray = array();
			
			$braType = false;
			
			//the way this works is as follows
			//in the sizeDefault table, sizes 
			//were inserted based on their typeId
			//in ascending order
			//so, this means that, the size at id 8
			//is technically smaller than the size at id 9
			//assuming both are of the same typeId
			foreach($sizeArray as $size){
				
				//so we get the id of the size
				//and then store it an an array
				$sql = "SELECT id, typeId
					FROM sizeDefault
					WHERE size LIKE '".$size."'";
			
				$result = mysql_query($sql, Utility::getCon()) or die(mysql_error());
				
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				$id = $row["id"];
				$typeId = $row["typeId"];
				
				//for bras, we order alphabetically,
				//not the way we ordered the sizing 
				if($typeId == 1){
				
					$braType = true;
				}
			
				
				array_push($arrayOfIdsInSizeDefault, $id);
				
				//at the same time, we will store the correlation
				//between id and size in another array
				$specialArray[$id] = $size;
			}
			
			//if we're not dealing with bras
			if(!$braType){
			
				//we sort the array of ids
				sort($arrayOfIdsInSizeDefault, SORT_NUMERIC);
				
				//now we loop through those ordered ids
				//and then add (thus in order)
				//the size stored in specialArray at each id value
				//into our orderedSizeArray
				foreach($arrayOfIdsInSizeDefault as $id){
					
					array_push($orderedSizeArray, $specialArray[$id]);
				}
				
			}
			else{
				
				foreach($arrayOfIdsInSizeDefault as $id){
					
					array_push($orderedSizeArray, $specialArray[$id]);
				}
				
				//we order $orderedSizeArray alphabetically
				sort($orderedSizeArray, SORT_STRING);
				
			}
			
			
			
			return $orderedSizeArray;
		}
		
		//this function returns an array
		//of all active sizes of a product
		//returns an array of Strings
		//such that the strings are ordered by size
		public function getSizes(){
			
			$arrayOfSizes = array();
			
			//we get all active colors
			$arrayOfActiveColors = $this->getActiveColors();

			if(!empty($arrayOfActiveColors)){
				
				//then we know there are some active sizes
				foreach($arrayOfActiveColors as $color){
					
					//we get all sizes for the color and store them
					//in an array
					$tempArrayOfSizes = $color->getSizes();
					
					foreach($tempArrayOfSizes as $size){
						
						//we loop through the sizes in this array
						//and add them to the main array only if they're
						//not already stored there
						if(!in_array($size, $arrayOfSizes)){
							
							array_push($arrayOfSizes, $size);
						}
						
					}//end inner foreach
					
				}//end outer foreach
				
			}
			
			//we return an ordered version of the sizes
			return $this->orderedSizeArray( $arrayOfSizes );
		}
		
		//this function returns an array of unique sizes
		//given all the colors in the colorArray
		public function getSizesGivenColors($colorArray){
			
			$arrayOfSizes = array();
			
			foreach($colorArray as $color){
				
				//we get all sizes for the color and store them
				//in an array
				$tempArrayOfSizes = $color->getSizes();
				
				foreach($tempArrayOfSizes as $size){
					
					//we loop through the sizes in this array
					//and add them to the main array only if they're
					//not already stored there
					if(!in_array($size, $arrayOfSizes) ){
						
						array_push($arrayOfSizes, $size);
					}
					
				}//end inner foreach
				
			}//end outer foreach
			
			//we return an ordered version of the sizes
			return $this->orderedSizeArray( $arrayOfSizes );
		
		}
		
		//this function returns an array as follows
		//the color at index 0 of colorArray
		//will have the corresponding sizes at index 0 of sizesArray
		public function getSizesForEachColor($colorArray){
			
			$arrayOfSizes = array();
			
			foreach($colorArray as $color){
				
				//we get all sizes for the color and store them
				// in the sizes array
				//we get an ordered version of their sizes
				array_push($arrayOfSizes, $this->orderedSizeArray( $color->getSizes() ));
				
			}//end outer foreach
			
			return $arrayOfSizes;
		
		}
		
		//this function is based on the old function
		//from product.php
		//it returns an array of array
		//where array[0] = desired sizes array ($sizesArray[$size] = index1,index5, such that
		//colorswatchesArray[index1] and colornamesArray[index1] has our desired info
		//for a color that has size $size
		//array[1] = desired colorswatches array (strings)
		//array[2] = desired colornames array (strings of names)
		
		function getDesiredSizeColorArrays($arrayOfActivationLevels){
			
			//this is an array of color objects
			$colorArray = $this->getColorsByActivation($arrayOfActivationLevels);
			
			//this is an array of colorswatch strings such that the first
			//corresponds to the first color in the activeColorsArray
			$colorswatchesArray = $this->getColorswatches($colorArray);
			
			//this is an array of colorname strings such that the first
			//corresponds to the first color in the activeColorsArray
			$colornamesArray = $this->getColornames($colorArray);
			
			//this is an array such that tempSizesArray[$color object] = an array of all sizes for that color object
			$tempSizesArray = $this->getSizesForEachColor($colorArray);
			
			//sizesArray will have the form sizesArray[size]  = index of matching color1 in colorArray and 
			//colorswatchesArray with that size,index of matching color2, etc
			//where the colors are active
			
			$sizesArray = array("Select Your Size"=>"");
			
			//first we set the keys of the sizesArray so that the
			//sizes will be presented in the desired order
			foreach($this->getSizesGivenColors($colorArray) as $size){
				$sizesArray[$size] = "";
			}
			
			$countColors = 0;
			
			//we now loop through the colors and their array of sizes
			foreach($tempSizesArray as $color=>$arrayOfCorrespSizes){
				
				foreach($arrayOfCorrespSizes as $size){
					
					//now, we find the key for the corresponding size
					//in the sizesArray, and concatenated $countColors to
					//the value there.  This basically means that
					//$colorswatchesArray[$countColors] and $colornamesArray[$countColors]
					//is where we will find info for a color of size $size
					$sizesArray[$size] .= $countColors.",";
					
				}
				
				//we want "Select Your Size" to display all color options
				//so we automatically concatenate $countColors to it
				$sizesArray["Select Your Size"] .= $countColors.",";	
				
				$countColors++;
			}
			
			return array($sizesArray, $colorswatchesArray, $colornamesArray);
		}
		
		//THESE FUNCTIONS DEAL WITH FABRICS
		//**************************************************
		
		//this function returns an array organized in the following manner
		//$array["fabric"] = array(fabricobj1)
		//$array["lining"] = array(fabricobj1, fabricobj2)
		//the array organizes fabric objects by their type
		public function getOrganizedFabrics(){
			
			$arrayOfOrganizedFabrics = array();
			
			foreach($this->fabrics as $fabric){
				
				$type = $fabric->getType();
				
				//if the key exists, we do the following: 
				if(array_key_exists($type, $arrayOfOrganizedFabrics)){
					
					array_push( $arrayOfOrganizedFabrics[$type], $fabric);
				}
				else{
					
					//otherwise we add it to the array as follows
					$arrayOfOrganizedFabrics[$type] = array($fabric);
				}
			
			}
			
			return $arrayOfOrganizedFabrics;
		
		}
		
		
		//THESE FUNCTIONS HAVE TO DO WITH THE STATE OF A PRODUCT
		//**************************************************
		//returns true if product is active
		//and false otherwise
		public function isActive(){
			
			$arrayOfAcColors = $this->getActiveColors();
			
			if( !empty( $arrayOfAcColors ) ){
				
				return true;
			}
			
			return false;
		}
		
		//THESE FUNCTIONS HAVE TO DO WITH STATE OF A PRODUCT
		//************************************************
		
		//returns true if the product has at least
		//one new color
		public function isNew(){
			
			if(count($this->getNewColors())>0){
			
				return true;
			}
			
			return false;
		}
		//this function returns the status of the product
		//whether released, retired or unreleased
		public function getActivation(){
			
			$activeColors = $this->getActiveColors();
			$unreleasedColors = $this->getColorsByActivation( array("unreleased") );
			$retiredColors = $this->getColorsByActivation( array("retired") );
			
			//it checks active, then unreleased, then retired
			if(!empty($activeColors)){
				
				return "released";
			}
			elseif(!empty($unreleasedColors)){
				
				return "unreleased";
			}
			else{
				return "retired";
			}
		}
		
		//this function returns the statuses a product has 
		//that is, if it has colors of different statuses
		//released, unreleased and retired
		public function getActivationStatuses(){
			
			$arrayOfStatuses = array();
			
			$activeColors = $this->getActiveColors();
			$unreleasedColors = $this->getColorsByActivation( array("unreleased") );
			$retiredColors = $this->getColorsByActivation( array("retired") );
			
			if(!empty($activeColors)){
			
				array_push($arrayOfStatuses, "released");
			}
			
			if(!empty($unreleasedColors)){
			
				array_push($arrayOfStatuses, "unreleased");
			}
			
			if(!empty($retiredColors)){
				
				array_push($arrayOfStatuses, "retired");
			}
			
			return $arrayOfStatuses;
		}
		
		//THESE ARE SPECIAL FUNCTIONS
		//***************************
		
		function getCollectionId(){
			
			$sql = "SELECT collectionId
					FROM collection
					WHERE collection LIKE '".$this->collection."'";
			
			$result = mysql_query($sql, Utility::getCon()) or die(mysql_error());
			
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			return $row["collectionId"];
		}
		
		
		//GETTERS
		//getter functions which get private variables of this class
		public function getItemNumber(){
		
			return $this->itemNumber;
		}
		
		public function getProductId(){
		
			return $this->productId;
		}
		
		public function getModelNumber(){
		
			return $this->modelNumber;
		}
		
		public function getBrand(){
		
			return $this->brand;
		}
		
		public function getProductName(){
		
			return $this->productName;
		}
		
		public function getDescription(){
		
			return $this->description;
		}
		
		public function getFrenchDescription(){
		
			return $this->frenchDescription;
		}
		
		public function getTitle(){
		
			return $this->title;
		}
		
		public function getBraDoctorHelp(){
		
			return $this->braDoctorHelp;
		}
		
		public function getCollection(){
		
			return $this->collection;
		}
		
		public function getPrice(){
		
			return $this->price;
		}
		
		public function getYear(){
		
			return $this->year;
		}
		
		public function getSeason(){
		
			return $this->season;
		}
		
		public function getItemType(){
		
			return $this->itemType;
		}
		
		public function getSizingFormat(){
		
			return $this->sizingFormat;
		}
		
		public function getFabrics(){
		
			return $this->fabrics;
		}
		
		public function getDefaultColor(){
			
			return $this->defaultColor;
		}
		
		public function getLink(){
			
			return $this->link;
		}
		
		public function getColors(){
			
			return $this->colors;
		}
	}

?>