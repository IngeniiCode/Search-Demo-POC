<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr Query Interface Class 
  +
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

require_once('ninja.base.class.php');

class solrQuery extends solrNinja {

	/* ============================================================
	   #  C O N S T R U C T O R 
           ============================================================
	*/

	/* ============================================================
	   #  P U B L I C   M E T H O D S 
           ============================================================
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	public function query($query=''){
		return $this->_postQuery($query);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   retrieve a very basic set of information from solr, 
	//     id
	//
	public function get_provider_basic($id){
		$idex   = $this->_id_exact($id);
		$fields = $this->_make_field_list(array('id','application_url','instance_id','appLogo','appLogoMD5','appVersion'));
		$query  = sprintf('?q=%s&fl=%s&wt=json&start=0&rows=1',$idex,$fields);
		$data   = json_decode($this->query($query),true);
		if(isset($data['response']['docs'])){
			return @$data['response']['docs'][0];
		}
                return false; 
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   direct solr call
	public function run($query=''){
		return $this->_post($query);
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	protected function make_field_list($fields=array()) {
		return implode('%2C',$fields);
	}

	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	private function _get_numfound($json){
		$response = json_decode($json,true);
		if(isset($response['response']['numFound'])){
			return sprintf('%d',$response['response']['numFound']);
		}
		return '';
	}
		
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//  new core for _postQuery, which has been converted to a 
	//  wrapper, for support of existing code
	// 
	private function _post($url='') {

		// build query string 
		$cH       = curl_init();
		$cOpt     = array(
			CURLOPT_URL                => $url,   // set the URL
			CURLOPT_FRESH_CONNECT      => 1,      // do not cache the connection
			CURLOPT_RETURNTRANSFER     => 1,      // return the data as text to a variable, not STDOUT
			CURLOPT_CONNECTTIMEOUT     => 20,     // set maximum time to connect to 30 seconds
			CURLOPT_TIMEOUT            => 30,     // maximum processing time in seconds
                );
		curl_setopt_array($cH, $cOpt);
                $data = curl_exec($cH);
		return $data;
	}


	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	private function _id_exact($id=false){
		return sprintf('id%%3A%%22%s%%22',urlencode($id));
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//
	private function _postQuery($query='') {
		// build query string 
		$url = sprintf('%s%s',$this->solrQuery,$query);
		return $this->_post($url);
	}

}

?>
