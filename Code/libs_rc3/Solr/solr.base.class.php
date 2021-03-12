<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr Base Class --  Ingenii Group LLC 
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr JSON Interface Class 
  + cURL used to post JSON data to the remote Solr server instance
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

class solrBase {

	protected $core_name  = '';
	protected $solrURL    = '';
	protected $solrUpdate = '';
	protected $solrQuery  = '';
	protected $payload;
	private   $cOpt;
	private   $cURL;
	private   $postCount     = 0;       // current count of posts
	private   $commitAt      = 1000;    // issue a commit after 1000 uses regardless
	private   $minimumCommit = 60000;   // set Solr to commit within 1 minute

	/* ============================================================
	   #  C O N S T R U C T O R 
           ============================================================
	*/

	public function __construct($core='selection1') {

		// check to see if CORE_NAME is set in the configs
		$this->core_name = $core;

		// configure cURL
		$this->configCURL();

		// if not configured, use environment variable
		$core_env      = (getenv('SOLR_CORE_'.$core))?:$_SERVER['SOLR_CORE_'.$core];
		$this->solrURL = ($core_env)?:'http://www.outspoken.ninja:15450/solr/'.$this->core_name; 

		// Define the UPDATE and QUERY strings
		$this->solrUpdate = sprintf('%s/update',$this->solrURL);
		$this->solrQuery  = sprintf('%s/select',$this->solrURL);

		return;
	}

	// -------------------------------------------------------
	//  Class Destructor 
	// ------------------------------------------------------- 

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
	//  Return solrUpdate string 
	// ------------------------------------------------------- 
	
	public function getUpdateURL(){
		// Define the UPDATE and QUERY strings
		return $this->solrUpdate = sprintf('%s/update',$this->solrURL);
	}
	
	// -------------------------------------------------------
	//  Return solrQuery string 
	// ------------------------------------------------------- 
	
	public function getQueryURL(){
		// Define the SELECT and QUERY strings
		return $this->solrQuery  = sprintf('%s/select',$this->solrURL);
	}

	// -------------------------------------------------------
	//  Construct query string, set to alwasy return JSON 
	// ------------------------------------------------------- 
	
	public function query($query=''){
		return $this->_postQuery($query.'&wt=json&indent=3');
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/

	// -------------------------------------------------------
	//  Construct a POST operation and execute 
	// ------------------------------------------------------- 
	
	protected function post($json='') {
		$url = $this->getUpdateURL();
		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_POST, 1); 
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $json);
		$result = curl_exec($this->cURL);
printf("%s RESP: %s\n",__METHOD__,$result);
		$curlinfo = curl_getinfo($this->cURL);
		if(!$curlinfo['http_code'] == 200){
			printf("%s ERROR: %s\n%s\nCURL: %s\n",__METHOD__,$json,$result,print_r($curlinfo,true));
		}
		if($this->postCount++ > $this->commitAt) {
			$this->commit();
			$this->postCount = 0;
		}
		// return the result for error detection
		return $result;
	}

	// -------------------------------------------------------
	//  Execute a batch add of docs into Solr 
	// ------------------------------------------------------- 
	
	protected function add_docs($docs=array()) {
		$url = $this->getUpdateURL();
		$doclist = array();  # clear out the doc list
		foreach($docs as $id => $doc) {
			$doclist[] = $this->_add_rec($doc); 
		}

		$add_doc = sprintf('{ %s }',implode(',',$doclist));

		// execute the post to Solr
		$return = $this->post($add_doc);
		$this->commit();

		// return the result for error detection
		return $return;
	}

	// -------------------------------------------------------
	//  Analog or add_docs  --  if there ever returns a time
	//  these need to be separated, this will make that job
	//  simpler 
	// ------------------------------------------------------- 
	
	protected function update_docs($docs=array()) {
		return $this->add_docs($docs);
	}

	// -------------------------------------------------------
	//  Update a single doc 
	// ------------------------------------------------------- 
	
	protected function update($update_json='') {
		$url = $this->getUpdateURL();

		$update_doc = $this->json_add($update_json); 

		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $update_doc);
		$result = curl_exec($this->cURL);
		if($this->postCount++ > $this->commitAt) {
			$this->_send_commit($url);
		}
		// return the result for error detection
		return $result;
	}

	// -------------------------------------------------------
	//  Delete a doc from Solr 
	// ------------------------------------------------------- 
	
	protected function delete_id($id='') {
		$url = $this->getUpdateURL();
		$delete_doc = $this->json_delete($id); 
		
		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $delete_doc);
		$result = curl_exec($this->cURL);
		if($this->postCount++ > $this->commitAt) {
			$this->_send_commit($url);
		}
		// return the result for error detection
		return $result;
	}

	// -------------------------------------------------------
	//  Delete a doc from Solr 
	// ------------------------------------------------------- 
	
	public function delete_ids($ids=array()) {
		$url = $this->getUpdateURL();
		$rec_base = '"delete": { "id": "%s" }';

		$doclist = array();  # clear out the doc list
		foreach($ids as $id) {
			$doclist[] = sprintf($rec_base,$id);
		}
		$delete_doc = sprintf('{ %s }',implode(',',$doclist)); 
		$doclist[]  = array();  // unset this thing

		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $delete_doc);
		$result = curl_exec($this->cURL);
		if($this->postCount++ > $this->commitAt) {
			$this->_send_commit($url);
		}
		// return the result for error detection
		return $result;
	}

	// -------------------------------------------------------
	//  Direct call to Solr, to do something -- (basic) 
	// ------------------------------------------------------- 
	
	protected function run($query=''){
		return $this->_post($query);
	}
	
	// -------------------------------------------------------
	//  Force a COMMIT 
	// ------------------------------------------------------- 
	
	protected function commit() {
		return $this->_send_commit($this->getUpdateURL());
	}

	// -------------------------------------------------------
	//  Fields list formatter 
	// ------------------------------------------------------- 
	
	protected function make_field_list($fields=array()) {
		$this->_make_field_list($fields);
	}

	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/

	// -------------------------------------------------------
	//  determine the number of records found in result set 
	// ------------------------------------------------------- 
	
	private function _get_numfound($json){
		$response = json_decode($json,true);
		if(isset($response['response']['numFound'])){
			return sprintf('%d',$response['response']['numFound']);
		}
		return '';
	}

	// -------------------------------------------------------
	//  New core for _postQuery, which has been converted to a 
	//  wrapper, for support of existing code
	// ------------------------------------------------------- 
	 
	private function _post($url='') {
		curl_setopt($this->cURL,CURLOPT_URL,$url);
                $data = curl_exec($this->cURL);
		return $data;
	}

	// -------------------------------------------------------
	//  Implode a field list to simplify creating complex
	//  Solr query GET strings
	// ------------------------------------------------------- 
	
	private function _make_field_list($fields=array()) {
		return implode('%2C',$fields);
	}

	// -------------------------------------------------------
	//  Request and exact ID from the data store 
	// ------------------------------------------------------- 
	
	private function _id_exact($id=false){
		return sprintf('id%%3A%%22%s%%22',urlencode($id));
	}

	// -------------------------------------------------------
	//  New wrapper for _post  (legacy support)
	// ------------------------------------------------------- 
	
	private function _postQuery($query='') {
		// build query string 
		$url = sprintf('%s%s',$this->solrQuery,$query);
		curl_setopt($this->cURL, CURLOPT_POST, 0);  // this is a hack for now
		$resp = $this->_post($url);
		curl_setopt($this->cURL, CURLOPT_POST, 1);  // this is a hack for now
		return $resp;
	}

	// -------------------------------------------------------
	//  Configure the cURL parameters 
	// ------------------------------------------------------- 
	
	private function configCURL() {
		// construct the CURL parameters for JSON posting
		$this->cURL = curl_init();
		$this->cOpt = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST           => 1,
			CURLOPT_VERBOSE        => 0,  // change to 1 for verbosity
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)',  // others should be used in future
		);

		// init the main configuration -- this should not change
		curl_setopt_array($this->cURL, $this->cOpt);

		return;
	}

	// -------------------------------------------------------
	//  Send Commit Base Operation 
	// ------------------------------------------------------- 

	private function _send_commit($url=''){
		if(!$url) {
			return;
		}
		$commit_doc = '{"commit": {} }';
		curl_setopt($this->cURL, CURLOPT_POST, 1); 
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $commit_doc);
		return curl_exec($this->cURL);
	}

	// -------------------------------------------------------
	//  Build an Multi-Rec doc formatting 
	// ------------------------------------------------------- 

	private function _add_rec($doc=false){
		if(!$doc) return;
		return sprintf('"add": { "commitWithin": %d, "overwrite": true, "doc": %s }',$this->minimumCommit,json_encode($doc));
	}

	// -------------------------------------------------------
	//  Build an ADD template 
	// ------------------------------------------------------- 

	private function json_add($json='{}'){
		return sprintf('{"add": {"commitWithin": %d,"overwrite": true,"doc": %s } }',$this->minimumCommit,$json);
	}

	// -------------------------------------------------------
	//  Build an UPDATE template 
	// ------------------------------------------------------- 

	private function json_update($json='{}'){
		return sprintf('{"add": {"commitWithin": %d,"doc": %s } }',$this->minimumCommit,$json);
	}

	// -------------------------------------------------------
	//  Build a DELETE template 
	// ------------------------------------------------------- 

	private function json_delete($id=''){
		return sprintf('{"delete":{ "id":"%s" } }',addslashes($id));
	}


}  /* END */

?>
