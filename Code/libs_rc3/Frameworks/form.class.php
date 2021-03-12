<?php

require_once($_SERVER['NLIBS'].'/Core/submit.lib.php');

class Form {

	private $action     = '';
	private $method     = '';
	private $name       = '';
	private $id         = '';
	private $sessid     = ''; 
	private $input_arry = array();  // array of form elements

	protected $DATA;

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct($config=array()){
		// perform some initialization actions here	
		$this->method = (isset($config['method'])) ? $config['method'] : 'POST';
		$this->action = (isset($config['action'])) ? $config['action'] : $_SERVER['REQUEST_URI'];
		$this->name   = (isset($config['name']))   ? $config['name']   : $this->_namegen();
		$this->id     = (isset($config['id']))     ? $config['id']     : $this->_id();
		$this->sessid = session_id();

		$this->_handleData();
		
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  destructor to help the GC close out memory use
	protected function __distruct(){
		// delete a bunch of stuff here
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _handleData(){
		if(!$this->DATA){
			// perform local initialization actions here	
			global $DATA;
			$this->DATA = $DATA;  // localize this global
		}
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _namegen(){
		return substr(md5(session_id()),2,8);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _id(){
		return substr(md5(session_id()),2,10);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _text_input($config=array()){
		$inputs  = '<input type="text" size="%d" name="%s" value="%s" class="%s" id="%s" %s>';
		$size    = (isset($config['size'])) ? $config['size'] : 50;
		$class   = (isset($config['class'])) ? $config['class'] : 'input_text';
		$name    = (isset($config['name'])) ? $config['name'] : 'terms';
		$elemid   = (isset($config['id'])) ? $config['id'] : $name;
		$value   = (@$this->DATA[$name])?:'';
		$jScript = (isset($config['jScript'])) ? $config['jScript'] : ''; 
		// create the input box
		return sprintf($inputs,$size,$name,$value,$class,$elemid,$jScript);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _radio_button($config=array()){
		$radiobtn = '<input type="radio" name="%s" value="%s" class="%s" id="%s" %s %s>';
		$class    = (isset($config['class'])) ? $config['class'] : 'cbx_option';
		$name     = (isset($config['name'])) ? $config['name'] : 'cbx_option';
		$value    = (isset($config['value'])) ? $config['value'] : 'on';
		$elemid   = (isset($config['id'])) ? $config['id'] : $name.'_'.$value;
		$jScript  = (isset($config['jScript'])) ? $config['jScript'] : ''; 
		$status   = (@$config['default']) ? 'checked' : ''; 
		// create the input box
		return sprintf($radiobtn,$name,$value,$class,$elemid,$jScript,$status);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _checkbox($config=array()){
		$checkbox = '<input type="checkbox" name="%s" value="%s" class="%s" id="%s" %s %s>';
		$class    = (isset($config['class'])) ? $config['class'] : 'cbx_option';
		$name     = (isset($config['name'])) ? $config['name'] : 'cbx_option';
		$elemid   = (isset($config['id'])) ? $config['id'] : $name;
		$value    = (isset($config['value'])) ? $config['value'] : 'on';
		$jScript  = (isset($config['jScript'])) ? $config['jScript'] : ''; 
		$status   = (@$this->DATA[$name]) ? 'checked' : '';
		// create the input box
		return sprintf($checkbox,$name,$value,$class,$elemid,$jScript,$status);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _selectBox($config=array()){
		$selectbox = '<select name="%s" class="%s" id="%s" %s>%s</select>';
		$class     = (isset($config['class'])) ? $config['class'] : 'cbx_option';
		$name      = (isset($config['name'])) ? $config['name'] : 'cbx_option';
		$elemid    = (isset($config['id'])) ? $config['id'] : $name;
		$value     = (isset($config['value'])) ? $config['value'] : 'on';
		$jScript   = (isset($config['jScript'])) ? $config['jScript'] : '';
		$status    = (@$this->DATA[$name]) ? 'checked' : '';
		$options   = (isset($config['options'])) ? $config['options'] : array(''=>' -- Select --');
		$selector  = '';
		if(is_array($options)){
			foreach($options as $key => $text){
				$selector .= sprintf('<option value="%s">%s</option>',$key,$text);
			}
		}
		// create the input box
		return sprintf($selectbox,$name,$class,$elemid,$jScript,$selector);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _selectDrop($config=array()){
		$select    = '<select name="%s" class="%s" id="%s" %s>%s</select>';
		$class     = (isset($config['class'])) ? $config['class'] : 'selector';
		$name      = (isset($config['name'])) ? $config['name'] : 'selector';
		$elemid    = (isset($config['id'])) ? $config['id'] : $name;
		$value     = (isset($config['value'])) ? $config['value'] : 'selected';
		$jScript   = (isset($config['jScript'])) ? $config['jScript'] : '';
		$selected  = (@$this->DATA[$name])?:'';
		$options   = (isset($config['options'])) ? $config['options'] : array(''=>' -- Select --');
		$selector  = '';
		if(is_array($options)){
			foreach($options as $key => $text){
				$flag = ($key == $selected) ? 'selected' : '';
				$selector .= sprintf('<option value="%s" %s>%s</option>',$key,$flag,$text);
			}
		}
		// create the input box
		return sprintf($select,$name,$class,$elemid,$jScript,$selector);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _button($config){
		$buttons = '<input type="%s" id="%s" name="%s" value="%s" class="%s" %s>';
		$type    = (isset($config['type'])) ? $config['type'] : 'submit';
		$class   = (isset($config['class'])) ? $config['class'] : 'button';
		$name    = (isset($config['name'])) ? $config['name'] : 'terms';
		$value   = (isset($config['DATA'][$name])) ? $config['DATA'][$name] : '';
		$id      = (isset($config['DATA'][$id])) ? $config['DATA'][$id] : 'button_1';
		$jScript = (isset($config['jScript'])) ? $config['jScript'] : ''; 
		// create the input box
		return sprintf($buttons,$type,$id,$name,$value,$class,$jScript);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _terms_adv($config){

		$adv = '<div id="advterms"><ul>';
		
		// setup terms checkbox 
		$config['name']   = 'titleOnly';
		$config['value']  = (isset($config['value'])) ? $config['value'] : 'titleOnly';
		$adv .= '<li>'.$this->_checkbox($config).' search titles only</li>';

		// setup the search mode options
		$config['name']   = 'mode';
		$adv .= '<li>'.$this->search_mode($config).'</li>';
		// advanced membership options
		$member = $this->_terms_adv_member($config);
		$adv .= '<li><div id="member_adv">'.$member.'</div></li>';

		$adv .= '<ul></div>';

		return $adv;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _terms_adv_member($config){

		$adv = '';
		
		// these features are only granted for advanced users
		switch($config['ut']) {
			case 'admin':
			case 'pro':
			case 'basic':
				$config['name']   = 'recentOnly';
				$config['value']  = (isset($config['value'])) ? $config['value'] : 'recentOnly';
				$adv .= '<li>'.$this->recentonly($config).'</li>';
				$config['name']   = 'termsBlockFilter';
				$config['value']  = (isset($config['value'])) ? $config['value'] : 'termsBlockFilter';
				$adv .= '<li>'.$this->exclude_term($config).'</li>';
				break;		
			default :
				$adv .= '<p><em>Members have access to more advanced features.</em><br><a href="/Membership" target="membership"><u>Sign up now, for FREE!</u></a></p>';
		}

		//$adv .= '<ul></div>';

		return $adv;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	private function _saved_search($config){

		$saved = '<div id="savedSearches"><ul></ul></div><script>loadSavedSearches("savedSearches");</script>';

		return $saved;
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//   force the data to be something other than
	//   the global data variable
	protected function data($DATA=array()){
		$this->DATA = $DATA;
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function mbr_terms($config=array()){
		return $this->_terms_adv_member($config);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function search_term($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		$label = (isset($config['label'])) ? $config['label'] : 'Find me this: ';
		$config['size']     = (isset($config['size'])) ? $config['size'] : 30;
		$config['name'] = 'search_terms';
		$input = $this->_text_input($config);
		return "<div class='$class terms'><b>$label</b> $input</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//    search terms input with a 'title only'
	//    checkbox on same line.
	public function search_term_to($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		// setup input text box
		$config['size']  = (isset($config['size'])) ? $config['size'] : 30;
		$config['name']  = 'search_terms';
		$terms = $this->_text_input($config);
		// setup terms checkbox 
		$config['name']   = 'titleOnly';
		$config['value']  = (isset($config['value'])) ? $config['value'] : 'titleOnly';
		$tonly = $this->_checkbox($config);
		// return section
		//return "<div class='$class terms'><b>Search For:</b>${terms} ${tonly}in title only</div>".PHP_EOL;
		return "<div class='$class terms'>${terms} ${tonly}in title only</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//    search terms input with a 'title only'
	//    checkbox on same line.
	public function saved_search($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		// setup input text box
		$config['size']  = (isset($config['size'])) ? $config['size'] : 30;
		$config['name']  = 'search_terms';
		$terms = $this->_text_input($config);
		// show/hide advanced radio
		$svdLink = '<a href="#saved" class="advlink" onClick="toggleDiv(\'#savedSearches\'); return false;"> Saved Searches</a>';
		/* S A V E D   S E A R C H   S E C T I O N  */
		$saved = $this->_saved_search($config);
		// return section
		return "<div class='$class saved'><b>Saved Searches:</b> ${svdLink} ${saved}</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//    search terms input with a 'title only'
	//    checkbox on same line.
	public function search_term_adv($config=array()){
		$class = ((isset($config['div_class'])) ? $config['div_class'] : '').' input-em-1_6';
		// setup input text box
		$config['size']  = (isset($config['size'])) ? $config['size'] : 30;
		$config['name']  = 'search_terms';
		$terms = $this->_text_input($config);
		// show/hide advanced radio
		$advLink = '<a href="#adv" class="advlink" onClick="toggleDiv(\'#advterms\'); return false;"> Advanced</a>';
		/* A D V A N C E D   S E C T I O N  */
		$adv  = $this->_terms_adv($config);
		// return section
		//return "<div class='$class terms'><b>Search For:</b>${terms} ${advLink} ${adv}</div>".PHP_EOL;
		return "<div class='$class terms'>${terms} ${advLink} ${adv}</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function exclude_term($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		$label = (isset($config['label'])) ? $config['label'] : 'ignore in title: ';
		$config['size']     = (isset($config['size'])) ? $config['size'] : 30;
		$config['name'] = 'exclude_terms';
		$input = $this->_text_input($config);
		return "<div class='$class'>$label $input</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function manufacture($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		$label = (isset($config['label'])) ? $config['label'] : 'Manufacture: ';
		$config['size']     = (isset($config['size'])) ? $config['size'] : 20;
		//$config['jScript'] .= 'onClick="year_validate();" ';
		$config['name']     = (isset($config['name'])) ? $config['name'] : 'manufacture';
		$input = $this->_text_input($config);
		return "<div class='$class'>$label $input</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function search_mode($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		$name  = (isset($config['name'])) ? $config['name'] : 'mode';
		$butns = array();

		// default search all 
		$config['name']    = $name;

		// set car search radio
		$config['value']   = 'cta';
		$config['id']      = 'mode_cta';
		$butns[] =$this->_radio_button($config).'Cars';
		
		// set truck search radio
		$config['value']   = 'cta';
		$config['id']      = 'mode_cta';
		$butns[] = $this->_radio_button($config).'Trucks';
		
		// set motorcycle search radio
		$config['value']   = 'mca';
		$config['id']      = 'mode_mca';
		$butns[] = $this->_radio_button($config).'Motorcycles';
		
		// set boats search radio
		$config['value']   = 'boa';
		$config['id']      = 'mode_boa';
		$butns[] = $this->_radio_button($config).'Boats';
		
		// set default 'Any' search type 'sss'
		$config['default'] = true;
		$config['value']   = 'sss';
		$config['id']      = 'mode_sss';
		$butns[] = $this->_radio_button($config).'Anything';

		// assemble
		$radios = join('&nbsp; ',$butns);

		// build element
		return "<div class='$class'>${radios}</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function search_vehicle_or_parts($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		$name  = (isset($config['name'])) ? $config['name'] : 'subsearch';

		// create year start input
		$config['name'] = $name.'_type';
		$config['value'] = 'vehicle';
		$config['default'] = true;
		$vehicles_radio = $this->_radio_button($config);	

		// create year end input
		$config['name'] = $name.'_type';
		$config['value'] = 'parts';
		$config['default'] = false;
		$parts_radio = $this->_radio_button($config);

		// build element
		return "<div class='$class'>${vehicles_radio}Vehicles  ${parts_radio}Parts</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function search_years($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		$name  = (isset($config['name'])) ? $config['name'] : 'model_year';
		$label = (isset($config['label'])) ? $config['label'] : 'Years: ';
		$config['size']      = (isset($config['size'])) ? $config['size'] : 8;
		//@$config['jScript'] .= 'onClick="year_validate();" ';

		// create year start input
		$config['name'] = $name.'_start';
		$start = $this->_text_input($config);	

		// create year end input
		$config['name'] = $name.'_end';
		$end = $this->_text_input($config);

		// build element
		return "<div class='$class'>$label $start to $end</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function price_range($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : '';
		$name  = (isset($config['name'])) ? $config['name'] : 'price_usd';
		$label = (isset($config['label'])) ? $config['label'] : 'Price Range (USD): ';
		$config['size']     = (isset($config['size'])) ? $config['size'] : 8;
		//$config['jScript'] .= 'onClick="year_validate();" ';

		// create year start input
		$config['name'] = $name.'_low';
		$start = $this->_text_input($config);	

		// create year end input
		$config['name'] = $name.'_high';
		$end = $this->_text_input($config);

		// build element
		return "<div class='$class'>$label $start to $end</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function button_submit($config=array()){
		$config['type'] = 'submit';
		$class          = (isset($config['div_class'])) ? $config['div_class'] : 'submit_button';
		$config['name'] = (isset($config['name'])) ? $config['name'] : 'submit_search';
		$label          = (isset($config['label'])) ? $config['label'] : '';
		$value          = (isset($config['DATA'][$config['name']])) ? $config['DATA'][$config['name']] : '';
		$config['DATA'][$config['name']]  = (isset($config['DATA'][$config['name']])) ? $config['DATA'][$config['name']] : 'Submit Search';
		$button = $this->_button($config);
		return "<div class='$class buttons'>$label $button</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function button($config=array()){
		$config['type'] = 'button';
		$class          = (isset($config['div_class'])) ? $config['div_class'] : 'submit_button';
		$config['name'] = (isset($config['name'])) ? $config['name'] : 'submit_search';
		$label          = (isset($config['label'])) ? $config['label'] : '';
		$value          = (isset($config['DATA'][$config['name']])) ? $config['DATA'][$config['name']] : '';
		$config['DATA'][$config['name']]  = (isset($config['DATA'][$config['name']])) ? $config['DATA'][$config['name']] : 'Submit Search';
		$button         = $this->_button($config);
		return "<div class='$class buttons'>$label $button</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function titleonly($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : 'checkbox';
	$label = (isset($config['label'])) ? $config['label'] : 'Search Ad Title Only:';
		$config['name']   = (isset($config['name'])) ? $config['name'] : 'titleOnly';
		$config['value']   = (isset($config['value'])) ? $config['value'] : 'titleOnly';
		$checkbox = $this->_checkbox($config);
		return "<div class='$class'>$label $checkbox</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function recentonly($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : 'checkbox';
		$label = (isset($config['label'])) ? $config['label'] : 'recent ads only';
		$config['name']   = (isset($config['name'])) ? $config['name'] : 'recentOnly';
		$config['value']   = (isset($config['value'])) ? $config['value'] : 'recentOnly';
		$checkbox = $this->_checkbox($config);
		return "<div class='$class'>$checkbox $label</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function showmap($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : 'checkbox';
		$label = (isset($config['label'])) ? $config['label'] : 'Map Results:';
		$config['name']   = (isset($config['name'])) ? $config['name'] : 'showMap';
		$config['value']   = (isset($config['value'])) ? $config['value'] : 'showMap';
		$checkbox = $this->_checkbox($config);
		return "<div class='$class'>$label $checkbox</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function zoneselect($config=array()){
		$class = (isset($config['div_class'])) ? $config['div_class'] : 'select';
		$label = (isset($config['label'])) ? $config['label'] : 'Search Zones:';
		$config['name']   = (isset($config['name'])) ? $config['name'] : 'searchZone';
		$config['value']  = (isset($config['value'])) ? $config['value'] : 'searchZone';
		$selector = $this->_selectDrop($config);
		return "<div class='$class'>$label $selector</div>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  recall the generated form name
	// 
	public function formname(){
		return $this->name;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  recall the generated form id
	//
	public function formid(){
		return $this->id;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   construct a hidden element
	//
	public function hidden($name='generic',$value=''){
		return "<input type='hidden' name='$name' value='$value'/>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   construct a hidden with a provided value
	//
	public function hiddenval($name='generic'){
		$value = (isset($config['DATA'][$name])) ? $config['DATA'][$name] : '';
		return "<input type='hidden' name='${name}' id='${name}' value='${value}'/>".PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  
	public function wrap($html){
		return 	<<<FORM
<!--  F  O  R  M  -->
<!--  SESS_ID $this->sessid -->
<form id="$this->id" action="$this->action" method="$this->method" name="$this->name">
$html
</form>
<!--  /  F  O  R  M  /  -->
FORM;

	}
} /* end of class */

?>
