<?php

/*
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + Solr P.R.I.M.E.  Base Class -- centralize contact paths 
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

require_once('solr.base.class.php');  // need the base core 

class solrPrime extends solrBase {

	/* ============================================================
	   #  C O N S T R U C T O R 
           ============================================================
	*/

	public function __construct() {
		parent::__construct('prime');  // declare which core
	}

	/* ============================================================
	   #  P R O T E C T E D   M E T H O D S 
           ============================================================
	*/


	/* ============================================================
	   #  P R I V A T E   M E T H O D S 
           ============================================================
	*/


	/* ============================================================
	   #  P U B L I C   M E T H O D S 
           ============================================================
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   retrieve a very basic set of information from solr, 
	//     id
	//
	public function get_provider_basic($id){
		$idex   = $this->_id_exact($id);
		$fields = $this->make_field_list(array('id','home_url','services'));
		$query  = sprintf('?q=%s&fl=%s&wt=json&start=0&rows=1',$idex,$fields);
		$data   = json_decode($this->query($query),true);
		if(isset($data['response']['docs'])){
			return @$data['response']['docs'][0];
		}
                return false; 
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   add providers in bulk
	//
	public function add_providers($providers=array()){
		// add the docs and return response
		return $this->add_docs($providers);
	} 

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   write merge providers in bulk
	//
	public function sync_providers($data=array()){
		// compile a list of providers to batch select
		foreach($this->_get_existing_providers_basic($data) as $prov){
			$id = trim($prov['id']);
			if(@$data[$id]){
				$data[$id] = $this->_integrate($prov,$data[$id]);
			}
		}

		// add the docs and return response
printf("%s ADDING: %s\n",__METHOD__,print_r(array_keys($data),true));
		return $this->add_docs($data);
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

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	// 
	private function _get_existing_providers_basic($providers=array()){
		$idquery = urlencode(join(' ',array_map(idq,array_keys($providers))));
		$fields  = $this->make_field_list(array('id','services','engine_origin'));
		$query   = sprintf('?q=%s&fl=%s&wt=json&start=0&rows=%d',$idquery,$fields,count($providers));
                $data    = json_decode($this->query($query),true);
                if(isset($data['response']['docs'])){
                        return @$data['response']['docs'];
                }
		return array();
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   merge and sort items
	//
	private function _merge($ar1=false,$ar2=false){
		return array_values(
			array_unique(
				array_merge(
					$this->_arry($ar1),
					$this->_arry($ar2)
				),
				SORT_STRING
			)
		);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   make sure what is passed is turned into an array of some
	//   sort
	//
	private function _arry($array=false){
		// check it see if it's defined; if not return empty array
		if(!$array) { 
			return array();
		}
		// if item is an array, return it as is, or return an empty one
		if(is_array($array)){
			return array_values($array);
		}
		else {
			return array();
		}
		return $array;	
	}


	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//  integrate the records 
	//
	private function _integrate($orig,$upd=array()){
		$updated = array();
		foreach($upd as $field => $value){
			switch($field){
				case 'id':
					$updated['id'] = $value;
					break;
				case 'services':
				case 'phone_num':
				case 'engine_origin':
				case 'seed_terms':
				case 'seed_locations':
					$updated[$field] = $this->_merge($orig[$field],$upd[$field]);
					break;
				case '_version_':
				case 'version':
					break;  // do not try to write these
				default:
					// all other fields 
					if($value){
						$updated[$field] = array('set' => $value);
					}
			}
		}
		return $updated;
	}

}   /* END */

?>
