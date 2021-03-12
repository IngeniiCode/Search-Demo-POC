<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr JSON Interface Class 
  +
  + cURL used to post JSON data to the remote Solr server instance
  + 
  + Class does not require the generation of flat files for posts to
  + Solr but instead uses the set variable which can contain 1 or more
  + JSON objects.
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

require_once('ninja.base.class.php');

class solrSubmit extends solrNinja {

	protected $payload;
	private   $cOpt;
	private   $cURL;
	private   $postCount     = 0;       // current count of posts
	private   $commitAt      = 1000;    // issue a commit after 1000 uses regardless
	private   $minimumCommit = 60000;   // set Solr to commit within 1 minute
	private   $template_add  = '';      // holder for the add doc template
	private   $template_del  = '';      // holder for the delete doc template
	private   $template_upd  = '';      // holder for the update doc template

	/* ============================================================
	   #  C O N S T R U C T O R 
           ============================================================
	*/

	public function __construct() {

		parent::__construct();  // init parent class.

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

		$this->_init_templates();

		// init the main configuration -- this should not change
		curl_setopt_array($this->cURL, $this->cOpt);

	}

	/* ============================================================
	   #  P U B L I C   M E T H O D S 
           ============================================================
	*/

	// - - - - - - - - - - - - - - - - - - - - - 
	//   
	public function post($app_json='') {
		$url = $this->getUpdateURL($this->CurrCore);
		$add_doc = sprintf($this->template_add,$this->minimumCommit,$app_json);
		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $add_doc);
		$result = curl_exec($this->cURL);
		if($this->postCount++ > $this->commitAt) {
			$this->_send_commit($url);
			$this->postCount = 0;
		}
		printf("%s ADD\tRESULTS %s\n",__METHOD__,print_r($result,true));
		// return the result for error detection
		return $result;
	}

	// - - - - - - - - - - - - - - - - - - - - - 
	//   
	public function update_docs($docs=array()) {
		$url = $this->getUpdateURL($this->CurrCore);

		$rec_base = '"add": { "commitWithin": %d, "overwrite": true, "doc": %s }';

		$doclist = array();  # clear out the doc list
		foreach($docs as $id => $doc) {
			$doclist[] = sprintf($rec_base,$this->minimumCommit,$doc);
		}
		$update_doc = sprintf('{ %s }',implode(',',$doclist)); 

		$doclist[]  = array();  // unset this thing

		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $update_doc);
		$result = curl_exec($this->cURL);
		if($this->postCount++ > $this->commitAt) {
			$this->_send_commit($url);
		}
		printf("%s UPDATE\tRESULTS %s\n",__METHOD__,print_r($result,true));			
		// return the result for error detection
		return $result;
	}

	// - - - - - - - - - - - - - - - - - - - - - 
	//   
	public function update($update_json='') {
		$url = $this->getUpdateURL($this->CurrCore);
		$rec_base = '{"add": { "commitWithin": %d, "overwrite": true, "doc": %s }}';
		$update_doc = sprintf($rec_base,$this->minimumCommit,$update_json);

		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $update_doc);
		$result = curl_exec($this->cURL);
		if($this->postCount++ > $this->commitAt) {
			$this->_send_commit($url);
		}
		printf("%s UPDATE\tRESULTS %s\n",__METHOD__,print_r($result,true));			
		// return the result for error detection
		return $result;
	}

	// - - - - - - - - - - - - - - - - - - - - - 
	//     delete stuff 
	//
	public function delete_id($id='') {
		$url = $this->getUpdateURL($this->CurrCore);
		$delete_doc = sprintf($this->template_del,$id);
		
		// execute the post to Solr
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $delete_doc);
		$result = curl_exec($this->cURL);
		if($this->postCount++ > $this->commitAt) {
			$this->_send_commit($url);
		}
		printf("%s->%s DELETE\tRESULTS %s\n",__CLASS__,__FUNCTION__,print_r($result,true));			
		// return the result for error detection
		return $result;
	}

	// - - - - - - - - - - - - - - - - - - - - - 
	//   
	public function delete_ids($ids=array()) {
		$url = $this->getUpdateURL($this->CurrCore);
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
		printf("%s DELETE\tRESULTS %s\n",__METHOD__,print_r($result,true));			
		// return the result for error detection
		return $result;
	}

	// - - - - - - - - - - - - - - - - - - - - - 
	//   
	public function commit() {
		return $this->_send_commit($this->getUpdateURL());
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/


	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/

	// - - - - - - - - - - - - - - - - - - - - - 
	// future home to a way of sending a commit.
	//
	private function _send_commit($url=''){
		if(!$url) {
			return;
		}
		$commit_doc = '{"commit": {} }';
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $commit_doc);
		$result = curl_exec($this->cURL);
		//printf("%s->%s ----------------- COMMIT -------------------------\n%s\n RESULTS %s\n",__CLASS__,__FUNCTION__,$commit_doc,print_r($result,true));
		return;
	}

	// - - - - - - - - - - - - - - - - - - - - - 
	//   Solr Templates
	//
	private function _init_templates(){

		// -- -- -- -- -- -- -- -- -- -- -- -- --
		//   JSON ADD
		$this->template_add = <<<JSON_ADD
{ 
"add": {
  "commitWithin": %d, 
  "overwrite": true,
  "doc":  %s
  }
}
JSON_ADD;
	
		// -- -- -- -- -- -- -- -- -- -- -- -- --
		//   JSON UPDATE 
		$this->template_upd = <<<JSON_UPDATE
{ 
 "add": {
  "commitWithin": %d, 
  "doc":  %s
  }
}
JSON_UPDATE;

		// -- -- -- -- -- -- -- -- -- -- -- -- --
		//   JSON DELETE 
		$this->template_del = <<<JSON_DELETE
{ 
"delete": { "id":"%s" }
}
JSON_DELETE;
	
		return;
	}

}   /* END */

?>
