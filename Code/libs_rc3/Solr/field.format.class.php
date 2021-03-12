<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr JSON Doc field formatting class 
  +
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/


class solrFormat {

	private $callbacks;

	/* ============================================================
	   #  C O N S T R U C T O R 
           ============================================================
	*/

	public function __construct() {
		return $this->_set_callbacks();	
	}

	// ------------------------------------------------------------
	//   destructor to help the GC close out memory use
	//
	protected function __distruct(){
		$this->callbacks = null;
	}

	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/

	// ------------------------------------------------------------
	// 
	private function _set_callbacks($field=''){
		
		$this->callbacks = array(
/*				-- key ids for app identification --		*/
			"string"			=> '_s',
			"array"				=> '_a',
			"integer"			=> '_i',
			"array2string"			=> '_a2s',
			"float"				=> '_f',
		);

		return;
	}

	// ------------------------------------------------------------
	// 
	private function _i($value=''){
		return (int)$value;
	}

	// ------------------------------------------------------------
	// 
	private function _a($value=''){
		if(!is_array($value)) { return array(); }
		return $value;
	}

	// ------------------------------------------------------------
	// 
	private function _f($value=''){
		return floatval(sprintf('%0.2f',$value));  // return the string or return empty string
	}

	// ------------------------------------------------------------
	// 
	private function _s($value=''){
		if(!$value) { return ''; }
		if(strlen($value) == 0) { return ''; }
		return trim($value); 
	}

	// ------------------------------------------------------------
	// 
	private function _a2s($value=''){
		if(!$value) { return ''; }
		if(!is_array($value)) { return $value; }
		return trim(implode(' ',$value));
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/


	/* ============================================================
	   #  P U B L I C   M E T H O D S 
           ============================================================
	*/

	// ------------------------------------------------------------
	//   automatically determine callback for the field and return
	//   the normalized value, returning a default string process
	//   if not in the field list (in future, callback will only 
	//   define non-string handling
	//
	public function format($field='',$value=false){
		$fn = (@$this->callbacks[$field])?:'_s';
		return $this->$fn($value);
	}


}   /* END */

?>
