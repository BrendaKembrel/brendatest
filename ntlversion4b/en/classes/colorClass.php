<?
	
	class Color{
		
		
		//the unique id of the product the color is
		//associated with
		private $productId;
		private $sizingFormat; //this is the current sizingFormat used for the product
		private $color; //the name of the color
		private $image; //the swatch associated with the image: eg) beverlyred.jpg
		
		private $cid; //the color's unique identifier (an int)
		
		//the id of the image in the images table
		//such that the image corresponds to a product image
		//of the product in the color defined by color
		//for example, the green version of the bra
		//for the color green
		//note that if imageId = -1 or 0, then no product image
		//is associated with the color
		private $imageId; 
		
		private $isNewUntil; //number of days (int)
		private $activationDate; //actual date
		private $deactivationDate; //actual date
		
		private $activation; //released, unreleased or retired
		
		//this function requires the productSizeColor table
		//this is just an array of size names the color has
		private $sizes = array();
		
		function __construct($productId, $sizingFormat, $color, $image, $cid, $imageId, $isNewUntil, $activationDate, $deactivationDate){
			
			//this is the static connection to the sql class
			$con = Utility::getCon();
			
			$this->productId = $productId;
			$this->sizingFormat = $sizingFormat;
			$this->color = $color;
			$this->image = $image;
			$this->cid = $cid;
			$this->imageId = $imageId;
			$this->isNewUntil = $isNewUntil;
			$this->activationDate = $activationDate;
			$this->deactivationDate = $deactivationDate;
			
			$this->activation = $this->getReleasedStatus();
			
			$orderBy = "";
			//here is where we get all sizes corresponding to a color
			if($sizingFormat=="1"){
				
				//we do order desc because the sizesArray
				//will be accessed in reverse order
				$orderBy = "ORDER BY size DESC";
			}
			
			//echo "the sizing format for ".$this->productId." is $sizingFormat <br/>";
			$sqlSizes = "SELECT size
						FROM productSizeColor
						WHERE cid = '".$this->cid."'
						AND size IN 
						(
							SELECT size
							FROM sizeDefault
							WHERE typeId = '".$this->sizingFormat."'
						 )
						 $orderBy";
						 
			$resultSizes = mysql_query($sqlSizes, $con) or die(mysql_error());
			
			while( $rowSizes = mysql_fetch_array($resultSizes, MYSQL_ASSOC) ){
			
				array_push($this->sizes, $rowSizes["size"]);
			
			}
			
		}
		
	
		
		//THESE FUNCTIONS HAVE TO DO WITH THE STATE
		//OF A COLOR
		
		//this function returns true
		//if the color has a special image
		//assigned to it other than the default image
		//of a product
		//else it returns false
		function hasSpecialImage(){
			
			if($this->imageId==-1||$this->imageId==0){
				
				return false;
			}
			else{
				
				return true;
			}
		
		}
		
		//the following function tells us whether
		//or not the color isNew
		//true = yes; false = no
		function isNew(){
			
			$date = date("Y-m-d");
			
			//we only consider released items of course
			if(strcasecmp( $this->getReleasedStatus(), "released")!=0){
			
				return false;
			}
			else{
				
				//we want to know the number of days btw the activation date
				//and today's date.  Since the product is released,
				//clearly today's date>=acDate
				$numDaysProdWasActive = $this->count_days($date, $this->activationDate);
				
				if($this->isNewUntil-$numDaysProdWasActive>=0){
					return true;
				}
				else{
					
					return false;
				}
			}
		
		}
		
		
		//the following function
		//tells us whether this color is:
		//1) released: ac<=today<deac
		//2) retired: today >= deac
		//3) unreleased: otherwise 
		
		function getReleasedStatus(){
			
			$date = date("Y-m-d");
			
			
			if(strcasecmp( $this->activationDate, "YYYY-MM-DD")==0||strcasecmp( $this->activationDate, "0000-00-00")==0){
			
				return "unreleased";
			}
			
			$numDaysDateAc = $this->count_days($date, $this->activationDate);
			$numDaysDateDeac = $this->count_days($date, $this->deactivationDate);
			
			if(strcasecmp( $this->deactivationDate, "YYYY-MM-DD")==0||strcasecmp( $this->deactivationDate, "0000-00-00")==0){
			
				$numDaysDateDeac=-1;
			}
			
			if($numDaysDateAc>=0&&$numDaysDateDeac<0){
				return "released";
			}
			elseif($numDaysDateDeac>=0){
				return "retired";
			}
			else{
				return "unreleased";
			}
		}
		
		//HELPER FUNCTIONS
		

		/**
		 * Calculating the difference between two dates
		 * @author: Elliott White
		 * @author: Jonathan D Eisenhamer.
		 * @link: http://www.quepublishing.com/articles/article.asp?p=664657&rl=1
		 * @since: Dec 1, 2006.
		 */
		
		// Will return the number of days between the two dates passed in
		private function count_days( $a, $b )
		{	
			$aArray = explode("-", $a);
			$bArray = explode("-", $b);
			
			$a = strtotime( $aArray[1]."/".$aArray[2]."/".$aArray[0]." 12:00am" );
			$b = strtotime( $bArray[1]."/".$bArray[2]."/".$bArray[0]." 12:00am" );
			
			if( function_exists( 'date_default_timezone_set' ) )
			{
				// Set the default timezone to US/Eastern
				date_default_timezone_set( 'US/Eastern' );
			}

			// First we need to break these dates into their constituent parts:
			$gd_a = getdate( $a );
			$gd_b = getdate( $b );

			// Now recreate these timestamps, based upon noon on each day
			// The specific time doesn't matter but it must be the same each day
			$a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
			$b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );

			// Subtract these two numbers and divide by the number of seconds in a
			//  day. Round the result since crossing over a daylight savings time
			//  barrier will cause this time to be off by an hour or two.
			return round( ( $a_new - $b_new ) / 86400 );
		}
	
	
		//getters
		
		function getProductId(){
		
			return $this->productId;
		}
		function getSizingFormat(){
		
			return $this->sizingFormat;
			
		}
		function getColor(){
			
			return $this->color;
		}
		function getImage(){
			
			return $this->image;
		}
		function getCid(){
			
			return $this->cid;
		}
		function getImageId(){
			
			return $this->imageId;
		}
		function getIsNewUntil(){
			
			return $this->isNewUntil;
		}
		function getActivationDate(){
			
			return $this->activationDate;
		}
		function getDeactivationDate(){
			
			return $this->deactivationDate;
		}
		function getActivation(){
			
			return $this->activation;
		}
		function getSizes(){
		
			return $this->sizes;
		}
		
		
		
		//setters
		function setIsNewUntil($numDays){
		
			$this->isNewUntil = $numDays;
		}
		
		function setActivationDate($date){
			
			$this->activationDate = $date;
		}
		
		function setDeactivationDate($date){
			
			$this->deactivationDate = $date;
		}
	}
?>