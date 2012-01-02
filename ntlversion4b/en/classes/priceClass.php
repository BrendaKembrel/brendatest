<?
	
	class Price{
	
		//these values are retrieved from the Utility class
		//statically
		private $currency;
		private $promoArray;
		
		private $price;
		private $finalPrice;
		private $priceType;
		private $canPrice;
		private $canFinalPrice;
		private $canPriceType;
		private $product; //the product object the price belongs to
		
		//*$arrayOfWords: 
		//$arrayOfWords[0] = word assoc'd with reg price
		//$arrayOfWords[1] = word assoc'd with our price
		//$arrayOfWords[2] = word assoc'd with sale price
		//$arrayOfWords[3] = word assoc'd with clearance price
		private $arrayOfWordsPriceType;
		//word associated with promo price
		private $promoWord; 
		
		//these two are calculated by us
		//if $currency = USD, then this will
		//be calculated for USD
		//otherwise, for CAD
		private $promoPrice;
		private $percentageOff;
		
		//$currency: CAD or USD, the current currency desired
		//$price: the regular price of the itme
		//$finalPrice: the final price of the item, either regular ($price == $finalPrice) or sale or clearance or our price
		//$priceType: 1 = Reg, 2 = Our, 3 = Sale, 4 = Clearance
		//$product = the product object for this price
		//$promoArray = the session array of current promos applied to the user (all these promos must be combinable of course,
		//but that is taken care of when the user adds promos).  Note that all sitewide promos still apply to the user automatically
		//and are included in the promoArray since they are always combinable
		function __construct($price, $finalPrice, $priceType, $canPrice, $canFinalPrice, $canPriceType, $product){
			
			//this is the sql connection we get statically
			//from the Utility class
			$con = Utility::getCon();
			$this->promoArray = Utility::getPromoArray();
			
			
			//we get the currency statically from the Utility class
			$this->currency = Utility::getCurrency();
			
			$this->price = $price;
			$this->finalPrice = $finalPrice;
			$this->priceType = $priceType;
			
			$this->canPrice = $canPrice;
			$this->canFinalPrice = $canFinalPrice;
			$this->canPriceType = $canPriceType;
			
			$this->product = $product;
			
			//calling this function sets our promoPrice
			//AND percentageOff variable appropriately
			$this->updatePromoPrice($this->promoArray);
			
			//here we set the array of words
			//which are the words associated with pricing
			$this->arrayOfWordsPriceType = array("Reg", "Our Price", "Sale", "Clearance");
			$this->promoWord = "Promo";
			
		}
		
		//function
		
		
		//input: 
		
		//*$arrayOfFormatting = an array of classes or styles associated with formatting reg price, interm price and discounted price
		//it'll say style='border: 1px solid black; color: red' etc
		//*$product = the product object in question (which of course the price object ($this) belongs to)
		//*$promoArray and array of all promos that apply to the user; clearly, these must be able
		//to be used in conjunction with each other
		//*$putYouSave: if true, the string will contain the percentage saved (assuming their is one)
		//false, it won't mention it
		//*$putStrikeThrough: if true, puts a strike through the regular price
		//if applicable
		//*$useWords: true if you want to show words like Reg:; false otherwise
		//returns a string with the formatted price
		public function getFormattedPrice($arrayOfFormatting, $link, $putYouSave, $putStrikeThrough, $useWords){
			
			//first off, given the currency, we need to figure out
			//which prices to use
			if(strcasecmp($this->currency, "CAD")==0){
				
				$finalPrice = $this->canFinalPrice;
				$price = $this->canPrice;
				$priceType = $this->canPriceType;
				
			}
			else{
				
				$finalPrice = $this->finalPrice;
				$price = $this->price;
				$priceType = $this->priceType;
			
			}
			
			//we then get the promoPrice and percentageOff
			$promoPrice = $this->promoPrice;
			$percentageOff = $this->percentageOff;
			
			//we want to know the word associated with the priceType of final price
			//to do so, we must find out what the priceType is
			if(strcasecmp($this->currency, "CAD")==0){
				
				$priceType = $this->canPriceType;
			}
			else{
				$priceType = $this->priceType;
			}
			
			//these are the words that will be used next to our prices
			//in the event that the user chooses to display words such as "Reg:"
			$priceTypeWord = "";
			$regPriceWord = "";
			$promoWord = "";
			
			if($useWords){
			
				switch($priceType){
					
					case(2):{
						$priceTypeWord = $this->arrayOfWordsPriceType[1].":  ";
					}break;
					case(3):{
						$priceTypeWord = $this->arrayOfWordsPriceType[2].":  ";
					}break;
					case(4):{
						$priceTypeWord = $this->arrayOfWordsPriceType[3].":  ";
					}break;
				}
				
				$regPriceWord = $this->arrayOfWordsPriceType[0].":  ";
				$promoWord = $this->promoWord.":  ";
			}
			
			//now for a bit of formatting
			
			//this is our final formatted String
			$formattedString =  "";
			
			//these 2 will help us with our formatting
			$float = ""; //this is if you want to show the "You Save" part; If so, then you want to float it left of the discounted price;
			if($putYouSave&&$percentageOff>0){
				
				$float = 'float: left;';
			}
			
			$addMarginToReg = "";//this is to add a margin to the regular price, in the event that it's the only price displayed
			
			//we will be formatting the following strings
			//but we only use a combo of them depending on how our prices end up
			$regPriceForm = "";
			$intermPriceForm = ""; //this is if the product was at a special price, but we also have a promo
			$promoPriceForm = ""; //this is if there's a promo price
			$specialPriceForm = ""; //this is if there's a special price, but no promo
			
			//now there are 4 possible price cases:
			
			//we have a promo and an intermediate price (product = special price w/o promo)
			if($promoPrice!=$finalPrice&&$promoPrice!=0&&$finalPrice!=$price){
				
				$regPriceForm = $arrayOfFormatting[0];
				$intermPriceForm = $arrayOfFormatting[1];
				$promoPriceForm = $arrayOfFormatting[2];
				
				//now, in the event the user wanted a strikethrough, we must add one as necessary
				if($putStrikeThrough){
				
					//if $arrayOfFormatting contains the word "style=", then we know we must
					//take a substr of style and then append text-decoration: line-through to it for the intermediate price
					//and we want to grey out the regular price
					if(stripos($intermPriceForm, "style") !== false){
						
						//we also must check if the user put a semi-colon or not and add one if necessary
						if(substr($intermPriceForm, -2)!=";'"&&substr($intermPriceForm, -2)!=';"'){
							$intermPriceForm = substr($intermPriceForm, 0, (strlen($intermPriceForm)-1) ).";text-decoration: line-through;'";
						}
						else{
							$intermPriceForm = substr($intermPriceForm, 0, (strlen($intermPriceForm)-1) )."text-decoration: line-through;'";
						}
						
					}
					else{
						$intermPriceForm.=" style='text-decoration: line-through;'";
					}
					
					//now, we deal with the greying out of the regular price
					if(stripos($regPriceForm, "style") !== false){
						
						//we also must check if the user put a semi-colon or not and add one if necessary
						if(substr($regPriceForm, -2)!=";'"&&substr($regPriceForm, -2)!=';"'){
							$regPriceForm = substr($regPriceForm, 0, (strlen($regPriceForm)-1) ).";color: #DDDDDD;'";
						}
						else{
							$regPriceForm = substr($regPriceForm, 0, (strlen($regPriceForm)-1) )."color: #DDDDDD;'";
						}
						
					}
					else{
					
						$regPriceForm.=" style='color: #DDDDDD;'";
					}
					
				}
				
				
				
				
				//now the way the price is depends on whether or not we have a link
				if($link!=""){
					
					$formattedString.="<p><a ".$regPriceForm." href='".$link."' >".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</a></p>
									   <p><a ".$intermPriceForm." href='".$link."' >".$priceTypeWord."$".number_format( $finalPrice, 2)." ".$this->currency."</a></p>
									   <p style='$float'><a ".$promoPriceForm." href='".$link."' >".$promoWord."$".number_format( $promoPrice, 2)." ".$this->currency."</a></p>
										";
				}
				else{
				
					//now we have to make room for the float: left part of the promoPrice in the event 
					//that putYouSave is true
					if(stripos($promoPriceForm, "style") !== false){
						
						//we also must check if the user put a semi-colon or not and add one if necessary
						if(substr($promoPriceForm, -2)!=";'"&&substr($promoPriceForm, -2)!=';"'){
							$promoPriceForm = substr($promoPriceForm, 0, (strlen($promoPriceForm)-1) ).";".$float."'";
						}
						else{
							$promoPriceForm = substr($promoPriceForm, 0, (strlen($promoPriceForm)-1) ).$float."'";
						}
						
					}
					else{
					
						$promoPriceForm.=" style='".$float."'";
					}
				
					$formattedString.="<p ".$regPriceForm.">".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</p>
									   <p ".$intermPriceForm.">".$priceTypeWord."$".number_format( $finalPrice, 2)." ".$this->currency."</p>
									   <p ".$promoPriceForm.">".$promoWord."$".number_format( $promoPrice, 2)." ".$this->currency."</p>
										";
				}
				
				
			}//end if we're in case 1
			//we have a promo, no intermediate price (product = regular price without promo)
			elseif($promoPrice!=$finalPrice&&$promoPrice!=0&&$finalPrice==$price){
				
				$regPriceForm = $arrayOfFormatting[0];
				$promoPriceForm = $arrayOfFormatting[2];
				
				//now, in the event the user wanted a strikethrough, we must add one as necessary
				if($putStrikeThrough){
				
					//now, we deal with adding a strike through regular price
					if(stripos($regPriceForm, "style") !== false){
						
						//we also must check if the user put a semi-colon or not and add one if necessary
						if(substr($regPriceForm, -2)!=";'"&&substr($regPriceForm, -2)!=';"'){
							$regPriceForm = substr($regPriceForm, 0, (strlen($regPriceForm)-1) ).";text-decoration: line-through;'";
						}
						else{
							$regPriceForm = substr($regPriceForm, 0, (strlen($regPriceForm)-1) )."text-decoration: line-through;'";
						}
						
					}
					else{
					
						$regPriceForm.=" style='text-decoration: line-through;'";
					}
				}
				
				
				
				//now the way the price is depends on whether or not we have a link
				if($link!=""){
					
					$formattedString.="<p><a ".$regPriceForm." href='".$link."' >".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</a></p>
									   <p style='$float'><a ".$promoPriceForm." href='".$link."' >".$promoWord."$".number_format( $promoPrice, 2)." ".$this->currency."</a></p>
										";
				}
				else{
				
					//now we have to make room for the float: left part of the promoPrice in the event 
					//that putYouSave is true
					if(stripos($promoPriceForm, "style") !== false){
						
						//we also must check if the user put a semi-colon or not and add one if necessary
						if(substr($promoPriceForm, -2)!=";'"&&substr($promoPriceForm, -2)!=';"'){
							$promoPriceForm = substr($promoPriceForm, 0, (strlen($promoPriceForm)-1) ).";".$float."'";
						}
						else{
							$promoPriceForm = substr($promoPriceForm, 0, (strlen($promoPriceForm)-1) ).$float."'";
						}
						
					}
					else{
					
						$promoPriceForm.=" style='".$float."'";
					}
				
					$formattedString.="<p ".$regPriceForm.">".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</p>
									   <p ".$promoPriceForm.">".$promoWord."$".number_format( $promoPrice, 2)." ".$this->currency."</p>
										";
				}
				
			}
			//we have no promo, but we have a special price
			elseif(($promoPrice==$finalPrice||$promoPrice == 0)&&$finalPrice!=$price){
				
				$regPriceForm = $arrayOfFormatting[0];
				$specialPriceForm = $arrayOfFormatting[2];
				
				//now, in the event the user wanted a strikethrough, we must add one as necessary
				if($putStrikeThrough){
				
					//now, we deal with adding a strike through regular price
					if(stripos($regPriceForm, "style") !== false){
						
						//we also must check if the user put a semi-colon or not and add one if necessary
						if(substr($regPriceForm, -2)!=";'"&&substr($regPriceForm, -2)!=';"'){
							$regPriceForm = substr($regPriceForm, 0, (strlen($regPriceForm)-1) ).";text-decoration: line-through;'";
						}
						else{
							$regPriceForm = substr($regPriceForm, 0, (strlen($regPriceForm)-1) )."text-decoration: line-through;'";
						}
						
					}
					else{
					
						$regPriceForm.=" style='text-decoration: line-through;'";
					}
				}
				
				
				
				
				//now the way the price is depends on whether or not we have a link
				if($link!=""){
					
					$formattedString.="<p><a ".$regPriceForm." href='".$link."' >".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</a></p>
									   <p style='$float'><a ".$specialPriceForm." href='".$link."' >".$priceTypeWord."$".number_format( $finalPrice, 2)." ".$this->currency."</a></p>
										";
				}
				else{
					
					//now we have to make room for the float: left part of the promoPrice in the event 
					//that putYouSave is true
					if(stripos($specialPriceForm, "style") !== false){
						
						//we also must check if the user put a semi-colon or not and add one if necessary
						if(substr($specialPriceForm, -2)!=";'"&&substr($specialPriceForm, -2)!=';"'){
							$specialPriceForm = substr($specialPriceForm, 0, (strlen($specialPriceForm)-1) ).";".$float."'";
						}
						else{
							$specialPriceForm = substr($specialPriceForm, 0, (strlen($specialPriceForm)-1) ).$float."'";
						}
						
					}
					else{
					
						$specialPriceForm.=" style='".$float."'";
					}
				
					$formattedString.="<p ".$regPriceForm.">".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</p>
									   <p ".$specialPriceForm.">".$priceTypeWord."$".number_format( $finalPrice, 2)." ".$this->currency."</p>
										";
				}
			}
			//we have no promo and the product is regular price
			else{
				
				$regPriceForm = $arrayOfFormatting[0];
				
				//now the way the price is depends on whether or not we have a link
				if($link!=""){
					
					$formattedString.="<p style='margin-bottom: 5px;'><a ".$regPriceForm." href='".$link."' >".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</a></p>";
										
				}
				else{
					$formattedString.="<p ".$regPriceForm."><span style='margin-bottom:5px;'>".$regPriceWord."$".number_format( $price, 2)." ".$this->currency."</span></p>";
				}
				
			}
			
			//now, if the user wants us to putYouSave and there is a percentageOff, we do that as well
			if($putYouSave&&$percentageOff>0){
				
				if($link==""){
					$formattedString .= "<p ".$arrayOfFormatting[3]." style='margin-left: 2px;float: left;'>(".$percentageOff."% Off!)</p>
									   <br style='clear: both' />";
				}
				else{
					$formattedString .= "<p style='margin-left: 2px;float:left;'><a ".$arrayOfFormatting[3]." style='text-decoration:none' href='".$link."' >(".$percentageOff."% Off!)</a></p>
									   <br style='clear: both' />";
				}
				
			}			
		
			
			
			return $formattedString;
			
		}
		
		
		//other functions
		public function getRegPriceGivenCurrency(){
		
			if(strcasecmp($this->currency, "CAD")==0){
				
				return $this->canPrice;
			}
			else{
				return $this->price;
			}
		}
		
		public function getFinalPriceGivenCurrency(){
		
			if(strcasecmp($this->currency, "CAD")==0){
				
				return $this->canFinalPrice;
			}
			else{
				return $this->finalPrice;
			}
		}
		
		//this function returns the price you'll pay for 
		//the item.  That is, if promos apply, it'll be the promoPrice
		//otherwise, it'll be the finalPrice
		public function getPriceToPay(){
			
			if($this->promoPrice!=0){
				
				//already adapted to the currency
				//note, if there's no promo, then promoPrice = finalPrice
				//so it doesn't matter if we return it because it's the same thing
				//as the else clause
				return $this->promoPrice;
			
			}
			else{
			
				return $this->getFinalPriceGivenCurrency();
			}
		
		}
		
		
		//this function changes the promo price and the percentageOff
		//based on the promoArray given (in case the session promoArray is updated)
		public function updatePromoPrice($promoArray){
			
			//now, we set the promoPrice to the finalPrice for starters
			$promoPrice = $finalPrice; //thsi will be CAD or USD as necessary
			$percentageOff = 0;  //this will be comparing CAD or USD prices as necessary
			
			if(empty($promoArray)){
				return;
			}
			
			//we reset the promoPrice to the finalPrice
			//and the percentageOff to 0
			//first we determine whether we do so for CAD or USD
			if(strcasecmp($this->currency, "CAD")==0){
				
				//we initialize the promoPrice at the finalPrice
				$promoPrice = $this->canFinalPrice;
				$finalPrice = $this->canFinalPrice;
				$price = $this->canPrice;
				$priceType = $this->canPriceType;
				
			}
			else{
				
				//we initialize the promoPrice at the finalPrice
				$promoPrice = $this->finalPrice; 
				$finalPrice = $this->finalPrice;
				$price = $this->price;
				$priceType = $this->priceType;
			
			}
			
			
			//now we calculate the promoPrice
			
			//determine whether promoArray will contain strings or objects
			foreach($promoArray as $promo){
			
				$promoI = new Promo($promo);
				
				$promoPrice = $promoI->getPriceProduct($promoPrice, $this->currency, $this->product);
				
			}
			
			//clearly, if $promoPrice != $finalPrice, a promo occurred, and we can calculate the percentage off
			
			if($promoPrice != $finalPrice){
				$percentageOff = round(100*(($price - $promoPrice)/$price));
			}
			else{
				
				//there may be still a percentage off, if we're dealing with a clearance or sale item
				//for example
				//this will occur as long as $finalPrice!=$price
				if($price!=$finalPrice){
				
					$percentageOff = round(100*(($price - $finalPrice)/$price));
				}
			}
			
			//so now we can set our private variables
			$this->promoPrice = $promoPrice;
			$this->percentageOff = $percentageOff;
			
		}
		
		//SETTER FUNCTIONS
		
		//$promoPriceWord : word assoc'd with promo price
		public function setPromoWord($promoPriceWord){
			
			$this->promoWord = $promoPriceWord;
		}
		
		//$arrayOfWordsPriceType[0]: assoc'd with reg
		//$arrayOfWordsPriceType[1]: assoc'd with our price
		//$arrayOfWordsPriceType[2]: assoc'd with sale price
		//$arrayOfWordsPriceType[3]: assoc'd with clearance price
		public function setArrayOfWordsPriceType($arrayOfWords){
			
			$this->arrayOfWordsPriceType = $arrayOfWords;
		}
		
		//this function sets the currency and updates
		//the promoPrice and percentageOff as necessary
		public function setCurrency($currency){
			
			//this is in case the currency changes
			$this->currency = $currency;
			
			//of course we will automatically have to update
			//the promoPrice and percentageOff 
			//it will do its calculations based on the new
			//value of the currency
			updatePromoPrice($this->promoArray);
			
		}
		
		//this function sets the promoArray and updates
		//the promoPrice and percentageOff as necessary
		public function setPromoArray($promoArray){
			//this is in case the promoArray changes
			$this->promoArray = $promoArray;
			
			//of course we will automatically have to update
			//the promoPrice and percentageOff 
			updatePromoPrice($promoArray);
		}
		
		
		//getter functions
		
		public function getPrice(){
		
			return $this->price;
		}
		public function getFinalPrice(){
		
			return $this->finalPrice;
		}
		public function getPriceType(){
		
			return $this->priceType;
		}
		
		public function getCanPrice(){
		
			return $this->canPrice;
		}
		public function getCanFinalPrice(){
		
			return $this->canFinalPrice;
		}
		public function getCanPriceType(){
		
			return $this->canPriceType;
		}
		
		public function getProduct(){
			
			return $this->product;
		}
		
		public function getPromoArray(){
			return $this->promoArray;
		}
		
		public function getPromoPrice(){
		
			return $this->promoPrice;
		}
		public function getPercentageOff(){
		
			return $this->percentageOff;
		}
	}

?>