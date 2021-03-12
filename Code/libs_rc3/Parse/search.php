<?php

require_once('utils.php');

class Search extends cURL {

	protected $html;
	protected $url;
	protected $terms;
	protected $location;
	protected $DOM;
	protected $query;

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct($query=''){
		$this->query = $query;
		$this->url   = '';
		$this->html  = '';
		$this->DOM   = new DOMDocument;
		parent::__construct('');  // Execute partent construction
		
		// perform some initialization actions here	
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   destructor to help the GC close out memory use
	protected function __distruct(){
		// delete a bunch of stuff here
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//   get the origination page url
	// 
	protected function _get_start_point(){
		$this->html = $this->get($this->url);
		@$this->DOM->loadHTML($this->html);
		return;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   get any page, load into DOM
	// 
	protected function load_page($url){
		$html = $this->get($url);
		@$this->DOM->loadHTML($this->html);
		return $html;
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//   url
	// 
	public function url($url=''){
		if(!$url){
			return $this->url;
		}
		$this->url = $url;
	}

}

?>
