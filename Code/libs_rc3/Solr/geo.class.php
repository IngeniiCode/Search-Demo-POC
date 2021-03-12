<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr P.R.I.M.E.  Base Class -- centralize contact paths 
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

require_once('solr.base.class.php');  // need the base core 

class solrGeo extends solrBase {

	/* ============================================================
	   #  C O N S T R U C T O R 
           ============================================================
	*/

	public function __construct() {
		parent::__construct('geo');  // declare which core
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/


	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/

	function _location($rec){
		return array(
			'id'          => $rec['zip'],
			'city'        => $rec['city'],
			'geolocation' => sprintf('%s,%s',trim($rec['latitude']),trim($rec['longitude'])),
			'latitude'    => $rec['latitude'],
			'longitude'   => $rec['longitude'],
			'postal'      => $rec['zip'],
			'state'       => $rec['state'],
			'county'      => $rec['county'],
			'country'     => $rec['country'],
		);
	}

	/* ============================================================
	   #  P U B L I C   M E T H O D S 
           ============================================================
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	public function update_docs($docs=array()) {
		return $this->add_docs($docs);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   add a geo record into Solr
	//
	public function add_location($rec){
		$doc = json_encode($this->_location($rec));
		return $this->update($doc);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   add a geo record into Solr
	//
	public function add_locations($data){
		foreach($data as $rec){
			$docs[] = $this->_location($rec);
		}
		return $this->update_docs($docs);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   analog to commit
	// 
	public function write(){
		$this->commit();
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/


	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/


}   /* END */

?>
