<?
	
	class Fabric{
		
		//the productId is the id of the product 
		//the fabric belongs to 
		private $productId;
		
		private $fabric;
		private $percentage;
		
		//the type has to do with whether we're dealing with fabric (default), 
		//lining, lace...  The reason why we do this is because a product will 
		//likely have different fabric components for the lace, lining, etc
		//so we need to know which components deal with the product's lace,
		//which deal with the lining, etc
		private $type;
		
		function __construct($productId, $fabric, $percentage, $type){
			
			$this->productId = $productId;
			$this->fabric = $fabric;
			$this->percentage = $percentage;
			$this->type = $type;
		
		}
		
		
		
		//GETTERS
		
		public function getProductId(){
		
			return $this->productId;
		}
		
		public function getFabric(){
		
			return $this->fabric;
		}
	
		public function getType(){
		
			return $this->type;
		}
		
		public function getPercentage(){
		
			return $this->percentage;
		}
	
	}
	

?>