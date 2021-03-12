<?php

require_once($_SERVER['NLIBS'].'/MySQL/feedback.class.php');

class Feedback extends FeedbackSQL {

	private $DATA    = '';
	private $outdir  = '';
	private $outfile = '';

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct($input){
		// perform some initialization actions here
		parent::__construct();  // init parent class
		$this->fid     = @$_COOKIE['x-fb']?:time();
		$this->x_auth  = @$_COOKIE['x-auth']?:'guest'.$this->fid;   // this is a marker that user has logged in.
		$this->DATA    = $input;
		// check to see if user is logged in or not
		
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
	//
	public function record() {
		// write to the outfile
		$this->write_fb_to_db($this->fid,$this->x_auth,$this->DATA);
	}
}

?>
