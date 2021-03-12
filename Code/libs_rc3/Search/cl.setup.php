<?php

class CraigsList {

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - - - - - -
	//  convert to CL specific values
	//
	private function _cl_convert($req){
		$tr = array();
		foreach($req as $key => $value){
			if(!$value) { next; }
			switch($key){
				case 'search_terms':
					$tr['query'] = trim($value);
					break;
				case 'price_usd_low':
					$tr['min_price'] = trim($value);	
					break;
				case 'price_usd_high':
					$tr['max_price'] = trim($value);	
					break;
				case 'model_year_start':
					$tr['min_auto_year'] = trim($value);	
					break;
				case 'model_year_end':
					$tr['max_auto_year'] = trim($value);	
					break;
//				case 'mode':
//					$tr[$key] = trim($value);
//					break;
				case 'titleOnly':
					$tr['srchType'] = 'T';
					break;
				case 'recentOnly':
					$tr['postedToday'] = '1';
					break;
				default:
					//$converted[$key] = trim($value);
			}

		}
		return $this->_convert($tr);
	}

	
	// - - - - - - - - - - - - - - - - - - - - - - - - - -
	//   convert array into a query string
	private function _convert($str){
		return http_build_query($str);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - -
	//  add general java script
	// 
	private function _generate_js(){
		$this->_cl_convert();
		$JS  = PHP_EOL.'/* -- S E A R C H -- */';	
		$JS  .= sprintf('%s%s?%s";',PHP_EOL,host_url('/api3/finder.php'),$this->httpq);

		return '<!-- J S   S T A R T  --><script>'.$JS.'</script><!-- J S   E N D -->';
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function gen($req){
		return urlencode($this->_cl_convert($req));
	}

	public function queryObj($req){
		return $this->_cl_convert($req);
	}

} /* end of class */

?>
