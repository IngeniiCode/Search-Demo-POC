<?php

require_once($_SERVER['NLIBS'].'/Frameworks/form.class.php');   // ensure include of core page code
require_once($_SERVER['NLIBS'].'/MySQL/search.class.php');      // ensure include of core page code
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');     //  load the user profile integrator

class SearchForm extends Form {

	private $formHtml    = '';
	private $searchId    = '';
	private $div_status  = '';
	private $div_results = '';
	private $userId      = '0';
	private $savedSearch = array();
	private $ZONES;
	private $CL;
	private $JS;
	private $SQL;
	private $PROF;
	private $USER;

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct($settings=array()){
		$this->searchId    = (@$settings['searchId'])?:'find';
		$this->div_status  = (@$settings['div_status'])?:'search_status';
		$this->div_results = (@$settings['div_results'])?:'search_results';
		$this->userId      = (@$settings['x_a'])?:'0';
		
		// attempt to extract the user's ID from a cookie or session
	
		// connect to Search	
		$this->SQL = new SearchSQL($settings);
	
		// add profie info
		$this->init_user();
		
		// load the search config
		$this->get_search_config();
		
		// extract the zones
		$this->setzones();

		parent::__construct($this->DATA);  // Execute partent construction
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function init_user(){
                $this->x_auth = @$_COOKIE['x-auth'];   // this is a marker that user has logged in.
		$PROF = new ProfileSQL(array('x_a'=>$this->x_auth));
		
		// set user configuration
		if($id = $PROF->user_valid()){
			$this->USER = array_shift($PROF->user_lookup($id));
		}
		else {
			$this->USER['type'] = 'guest';
		}
		return;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   geographic zone selector
	private function setzones(){
		$ZONES = array();
		foreach( $this->SQL->get_searchZones() as $zone){
			$zone_id = $zone['zone_id'];
			$zone_name = $zone['zone_name'];
			$state_code = $zone['state_code'];
			$ZONES[$zone_id]['name'] = $zone_name;
			$ZONES[$zone_id]['members'][] = $state_code;
		}

		foreach($ZONES as $zone_id => $data){
			$this->ZONES[$zone_id] = sprintf('%s:  (%s)',$data['name'],join(', ',$data['members']));
		}
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  default function, nothing wagered, nothing gained
	//
	private function get_search_config(){
		$this->savedSearch = json_decode($this->SQL->get_saved_search(),true); // get this form SQL
		$this->data($this->savedSearch);
		$this->type = $this->savedSearch['type'];
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  default function, nothing wagered, nothing gained
	//
	private function none(){
		return '';
	}
	
	// - - - - - - - - - - - - - - - - - - - - - -
	//  generate the OnClick string, on the fly
	//
	private function _oncl(){
		return 'onClick="executeSearch(\''.$this->formid().'\',\''.$this->div_status.'\',\''.$this->div_results.'\');return false;"';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	private function find(){
		$TEMPLATE = <<<TEMPLATE
<h3>I Want to Find:</h3> %s %s %s %s %s %s %s %s %s %s
TEMPLATE;
		$this->formHtml = sprintf($TEMPLATE,
			$this->hidden('type','find'),
			$this->hiddenval('zonefilter'),
			$this->search_term_adv(array('ut'=>$this->USER['type'])),
			'',//$this->search_vehicle_or_parts('cta','pta'),
			$this->price_range(),
			$this->search_years(),
			'',//$this->showmap(),
			$this->zoneselect(array('options'=>$this->ZONES)),
			$this->saved_search(array('ut'=>$this->USER['type'])),
			$this->button(array('type'=>'button','jScript'=>$this->_oncl()))
		);
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//  get value useful for other stuffs
	//
	public function get($var=''){
		return @$this->$var;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  allow loading a template string to replace
	//  the default
	//
	function setTemplate($template){
		$this->template = $template;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  BUILD THE FORM
	//
	function build(){
		// build the form
		$FN = $this->type;
		$this->$FN();   // init the type of form
		
		return '<div id="form">'.$this->wrap($this->formHtml).'<div id="'.$this->div_status.'"></div></div>';
	}

} /* end of class */

?>
