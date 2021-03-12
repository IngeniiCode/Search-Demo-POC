<?php

require_once('libs/App/base.page.class.php');  // parent class 

class Page_XROXY  extends BasePage {
	// set some regex prototypes required to parse portions of the scanned
	// page content.
	private $PROXYS    = array();
	private $last_page = 0;

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/

	// ------------------------------------------------------------
	//   destructor to help the GC close out memory use
	//
	protected function __distruct(){
		$this->PROXYS    = null;
		$this->last_page = null;
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	private function process_links(){
		// Iterate over all the <link> tags
		foreach($this->DOM->getElementsByTagName('a') as $href) {
			$parts = array();
			if(preg_match('#proxy&host=([0-9\.]+)&port=(\d+)#',$href->getAttribute('href'),$parts)){
				$proxy = @$parts[1];
				$port  = (@$parts[2])?:'80';
				//printf("%s PROXY:%s  PORT:%d  LINK:%s\n",__FUNCTION__,$proxy,$ports,$href->getAttribute('href'));
				$this->PROXYS[] = sprintf('%s:%d',$proxy,$port);
				continue;	
			}
			if(preg_match('#&pnum=(\d+)#',$href->getAttribute('href'),$parts)){
				$this->last_page = (@$parts[1] > $this->last_page) ? @$parts[1] : $this->last_page; 
			}
		}
		// do some post processing.
		return;
	}

	/*
	  ------------------------------------------------------
	  -- P R O T E C T E D
	  ------------------------------------------------------
	*/


	/*
	  ------------------------------------------------------
	  -- P U B L I C 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	public function parse() {
		$this->process_links();
		return;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	public function getval($valname) {
		return $this->$valname;
	}
	

}	

?>
