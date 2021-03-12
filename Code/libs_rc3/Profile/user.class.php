<?php

require_once($_SERVER['NLIBS']."/MySQL/profile.class.php");

class Profile extends ProfileSQL {
	
	private $x_auth = '';  // holder for the x-auth cookie

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/

        public function __construct($DATA=array()) {
		$this->x_auth = $_COOKIE['x-auth'];
                parent::__construct(array('x_a'=>$this->x_auth));  // init parent class.
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E   M E T H O D S 
	  ------------------------------------------------------
	*/

	
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

	// ----------------------------------------------------
	//   user lookup
	//
	public function get(){
		return $this->user_lookup($this->x_auth)[0];
	}	

	// ----------------------------------------------------
	//   user lookup
	//
	public function verify_code($code){
		if($this->verify_account($code)){
			// code is verified		
		}
	}
}

?>
