<?php

/*
  C U R L   L I B R A R Y   T O   S I M U L A T E   F I R E F O X   R E Q U E S T 
*/

class cURL {
	
	private $cH;
	private $opts;
	private $referer;
	private $agent;
	private $url;
	private $html;

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct($url='',$referer='',$agent=''){
		$this->cH      = curl_init();  // init object
		$this->url     = $url;  
		$this->agent   = ($agent)?:'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10; rv:35.1) Gecko/20100101 Firefox/35.1';  
		$this->referer = ($referer)?:'';
		$this->opts    = array(
			CURLOPT_URL            => $this->url,
			CURLOPT_FRESH_CONNECT  => true,
			CURLOPT_MAXREDIRS      => 5,
			CURLOPT_TIMEOUT        => 90,
			CURLOPT_USERAGENT      => $this->agent,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_REFERER        => $this->referer,
		);
		curl_setopt_array ($this->cH,$this->opts);
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


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//     set / get url 
	//
	function url($url=''){
		if($url){
			$this->url = $url;
		}
		return $this->url;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//     set / get user agent string
	//
	function agent($agent=''){
		if($agent){
			$this->agent = $agent;
		}
		return $this->agent;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//     set / get referer 
	//
	function referer($referer=''){
		if($referer){
			$this->referer = $referer;
		}
		return $this->referer;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   generic setting of data
	//
	public function set($key=false,$value=false){
		if(!$key){
			return $key;
		}
		$this->$key=$value;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   get the website and return the HTML
	// 
	function get($url=''){

		curl_setopt($this->cH, CURLOPT_URL, $this->url);
		curl_setopt($this->cH, CURLOPT_USERAGENT, $this->agent);

		return curl_exec($this->cH);
	}

} /* end class */

?>
