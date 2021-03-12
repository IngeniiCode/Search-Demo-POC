<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Provider Interface Class 
  +
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

require_once('adc.query.class.php');

class Providers extends solrQuery {

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
	public function get_providers($SelArry=array()){
		$q  = array();
		$fq = array();
		foreach($SelArry as $key => $value){
			if (strpos($key,'srvc_',0) !== false) {
				$q[] = sprintf('%s:*',$key);
				next;
			}
			else {
				$fq[] = 'desc_short:*'.trim($value).'*';
			}
		}
		if(count($q)) {
			$sq = urlencode(join(' OR ',$q));
			$fq = urlencode(join(' OR ',$fq));
			$q = $fq;  // if there are not specific options, move search to primary
		}
		else {
			$sq = urlencode(join(' OR ',$fq));  // if there are not specific options, move search to primary
			$fq = '';
		}
		// make it query
		$query = sprintf('?q=%s&fq=%s&rows=250',$sq,$fq);
//printf("%s QUERY: %s\n",__METHOD__,$query);
		
		return $this->query($query);
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/


	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/

}

?>
