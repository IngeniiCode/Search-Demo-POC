<?php

require_once('search.php');
require_once($_SERVER['NLIBS'].'/Solr/adc.submit.class.php');

class YahooParse extends Search {

	protected $links = array();
	private   $solr_result = '';
	private   $base        = 'https://search.yahoo.com/yhs/search';
	private   $dockey      = '';
	private   $SOLR        = false;  // submit connector
	private   $DOCS        = array();
	
	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct($query=''){
		parent::__construct($query);  // Execute partent construction
		$this->_synth_start_url($query);
		$this->SOLR = new solrSubmit();  // submit connector
		
		// perform some initialization actions here	
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	private function _synth_start_url($query){
		$REQ            = json_decode($query['ep'],true);
		$this->dockey   = (trim(@$REQ['key']))?:'services';
		$this->terms    = (trim(@$REQ['terms']))?:'';
		$this->location = (trim(@$REQ['location']))?:'';

		$encq = urlencode(sprintf('%s NEAR:%s',$this->terms,$this->location));
		$this->url  = sprintf('%s?p=%s&ei=UTF-8&hspart=mozilla&hsimp=yhs-001',$this->base,$encq);
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	
	// - - - - - - - - - - - - - - - - - - - - - -
	//
	protected function add_facilities($data){
		$fct = 0;
		if($links = @json_decode($data,true)){
			foreach($links as $fac){
				$fct++;
				$docs[] = array(
					'id'              => md5(json_encode($fac)),  // has entire thing.. simplest to do for now.
					'home_url'        => array('set' => $fac['site']),
					'engine_url'      => array('set' => $fac['href']),
					'desc_short'      => array('set' => $fac['contents']),
					'location_str'    => array('set' => $this->location),
					'engine_origin'   => 'yahoo',
					$this->dockey     => array('set' => $this->terms),
				);
			}
			if(count($docs)>0){
				$this->solr_result = $this->SOLR->add_docs($docs);
			}
		}
		return $fct;
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//   parse for all the link block
	public function parse_for_items(){

		$results = execNode(__FILE__,'yahoo.search.main.js',$this->url);

		$fct = $this->add_facilities(execNode(__FILE__,'yahoo.search.links.js',trim($results)));

		//$file = dirname(__FILE__).'/TEST.DATA.json';
		//$fct   =  $this->add_facilities(file_get_contents($file));

		return addslashes('<div><em>'.$fct.'</em> <u>'.ucfirst($this->terms).'</u><br>Providers Located</div>');

	}
	
}

?>
