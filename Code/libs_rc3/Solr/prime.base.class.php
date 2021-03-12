<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr Base Class -- centralize contact paths 
  +
  + This class provides function for setting the URL 
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

class solrPrime {

	protected $core_name  = '';
	protected $solrURL    = '';
	protected $solrUpdate = '';
	protected $solrQuery  = '';

	/* ============================================================
	   #  C O N S T R U C T O R 
           ============================================================
	*/

	public function __construct($config=false) {

		// Import connection data if provided in

		// check to see if CORE_NAME is set in the configs
		$this->core_name = 'prime';

		// if not configured, use environment variable
		$this->solrURL = 'http://www.outspoken.ninja:15450/solr/'.$this->core_name; 

		// Define the UPDATE and QUERY strings
		$this->solrUpdate = sprintf('%s/update',$this->solrURL);
		$this->solrQuery  = sprintf('%s/select',$this->solrURL);

		return;
	}

	// ------------------------------------------------------------
	//   destructor to help the GC close out memory use
	//
	protected function __distruct(){
		return;
		$this->solrUpdate = null;
		$this->solrQuery  = null;
		$this->core_name  = null; 
		$this->solrURL    = null;
	}

	/* ============================================================
	   #  P U B L I C   M E T H O D S 
           ============================================================
	*/

	// -------------------------------------------------------
	// Return solrUpdate string 
	// ------------------------------------------------------- 
	
	public function getUpdateURL(){
		// Define the UPDATE and QUERY strings
		return $this->solrUpdate = sprintf('%s/update',$this->solrURL);
	}
	
	// -------------------------------------------------------
	// Return solrQuery string 
	// ------------------------------------------------------- 
	
	public function getQueryURL(){
		// Define the SELECT and QUERY strings
		return $this->solrQuery  = sprintf('%s/select',$this->solrURL);
	}
	
	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/

	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/

}  /* END */

?>
