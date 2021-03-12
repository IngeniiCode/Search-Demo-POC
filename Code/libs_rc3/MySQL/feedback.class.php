<?php 

require_once($_SERVER['NLIBS'].'/Core/utils.lib.php');
require_once('main.class.php');

class FeedbackSQL extends MySQL {
	
	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/

	public function __construct(){
		// init sql library
                parent::__construct();  // init parent class.
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E   M E T H O D S 
	  ------------------------------------------------------
	*/

	private function _safe($val){
		$val = trim($val);
		$this->NO = array('#\bSELECT\b#i','#\bDELETE\b#i','#\bcount\(#i','#\binsert\b#i','#;#');
		// scrub out any strings that would appear to be SQL Injection Hacks
		return addslashes(preg_replace($this->NO,' ',$val));
	}
	
	/*
	  ------------------------------------------------------
	  -- P R O T E C T E D   M E T H O D S 
	  ------------------------------------------------------
	*/

	
	/*
	  ------------------------------------------------------
	  -- P U B L I C   M E T H O D S 
	  ------------------------------------------------------
	*/

	public function write_fb_to_db($fid,$x_auth,$DATA){
		$data = $this->_safe(json_encode($DATA));
		$sql  = sprintf('INSERT INTO feedback VALUES("%s","%s",NOW(),"%s")',addslashes($fid),addslashes($x_auth),$data);
		$this->run($sql);
	}
}

