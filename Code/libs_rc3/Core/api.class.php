<?php

class api {

	private $key = 'MIIEogIBAAKCAQEAjb/4IdRpiNIpB39I/4PPkmfOs8BeaB+jUs12IYN65d6gVvqqr0alLgHxcBW2';

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		// perform some initialization actions here
		$this->set_key();
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
	public function encode($str) {
		// write to the outfile
		return urlencode(encrypt((json_encode($str),$this->key));
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function decode() {
		return json_decode(urldecode(decrypt($encrypted,$this->key)),true);
	}
}

?>
