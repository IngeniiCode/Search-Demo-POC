<?php

require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');
require_once($_SERVER['NLIBS'].'/Core/fbsdk.php');

class Session {

	private $SESSION        = array();  // banner  
	//private $expy_seconds = 74000;    // set cookie/session expiration to a fairly long time-- why not?
	private $expy_seconds   = 1300000;  // set cookie/session expiration to 15 days ?
	private $PROF           = array();  // profile
	private $uPro           = array();
	protected $x_auth       = '';

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		// perform some initialization actions here	
		session_start();   // this MUST happen before any HTML is transmitted
		setcookie(session_name(),session_id(),time() + $this->expy_seconds);
		$this->SESSION['STATUS'] = session_status();
		$this->SESSION['ID']     = session_id();

		// Init Failbook Stuffs

		// check to see if user is logged in or not
		$this->x_auth = @$_COOKIE['x-auth'];   // this is a marker that user has logged in.

		// init the profile lib
                // set user configuration
/*
                if($id = $this->user_valid()){
                        $this->USER = array_shift($this->user_lookup($id));
                }
                else {
                        $this->USER['type'] = 'guest';
                }
*/
		$this->PROF = new ProfileSQL(array('x_a'=>$this->x_auth));  // declare the profile class
		$this->uPro = $this->PROF->get();    //
		
		// check to see if the user is valid, and if
		// so what their premissions are

	}

	// - - - - - - - - - - - - - - - - - - - - - -
	
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
	//   return session inforation
	public function sessInfo(){
		$this->SESSION['SESS'] = $_SESSION;
		return $this->SESSION;
	}

}

?>
