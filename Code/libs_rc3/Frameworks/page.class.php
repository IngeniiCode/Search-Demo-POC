<?php
require_once($_SERVER['NLIBS'].'/Core/analytics.lib.php');  // important analytics
require_once('banner.class.php');  // should be with Page

class Page {

	protected $title       = 'Outspoken.Ninja';
	protected $description = "Outspoken.Ninja - Search Tools for your Lifestyle.  Find the things you want, your way.";
	protected $scriptFile  = '';
	protected $baseDir     = '';
	protected $http_code   =  0;
	private $BASE          = '';     // base url for all the stuffs
	private $BN            = false;
	private $TESTING       = '';
	private $HEADER        = '';
	private $TAGS          = '';
	private $BANNER        = '';
	private $MIXIN         = '';
	private $MAIN_BODY     = ''; 
	private $FLOAT         = false;
	private $FOOTER        = false;  // this is from file footer.lib.php
	private $ANALYTICS     = false;  // this is from file analytics.lib.php
	private $LASTLOADS     = '';     // container for last loading stuffs

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		$this->_calc_basedir();
		$this->BN = new Banner();
		$this->_add_required();
		$this->BASE = host_url();   // set the base hostname 
		// perform some initialization actions here	
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   destructor to help the GC close out memory use
	protected function __distruct(){
		// delete a bunch of stuff here
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//   these css and js files need to be available
	//   before adding the customisers, or some libraries
	//   will not initiate properly
	//
	private function _add_required(){
		// add required CSS
		$this->css('/css/ninja.css');
		$this->css('/css/feedback.css');
		$this->css('/css/login.css');
		$this->css('/css/banner.css');
		$this->css('/css/menu.css');
		$this->css('/css/main.css');
		$this->css('/css/forms.css');
		//$this->css('//fonts.googleapis.com/css?family=Days+One');  // webfont 
		$this->css('//fonts.googleapis.com/css?family=Orbitron');  // webfont 
		$this->css('//fonts.googleapis.com/css?family=Syncopate');  // webfont 
	
		// add required JS
		$this->jsfile('/js/q.js');
		$this->jsfile('/js/qui.js');
		//$this->jsfile('/js/fbl.js');
		$this->jsfile('/js/ninja.js');
		$this->jsfile('/js/login.js');
		$this->jsfile('/js/geodata.js.php?q='.time());
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _set_local($type=''){
		// generate file specific and directory specific files
		$ffname = str_replace('.php','.'.$type,$this->scriptFile);
		$dfname = sprintf('%s/'.$type,$this->baseDir);
		// test for existance, include if found.
		if(file_exists($ffname)){
			return file_get_contents($ffname);
		}
		if(file_exists($dfname)){
			return file_get_contents($dfname);
		}
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _title(){
		// look for title file in basedir
		return $this->title = $this->_set_local('title');
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _sessTest(){
		// check to see if hostname is dev.outspoken.ninja
		if($_SERVER['SERVER_NAME'] == 'dev.outspoken.ninja'){
			$stuff  = "<!-- S T U F F -->\n";
			$stuff .= 'SERVER_NAME: '.$_SERVER['SERVER_NAME'].PHP_EOL;
			// add the session information
			$this->SESS = $this->BN->sessInfo();
			$stuff .= sprintf('SESS: %s%s',print_r($this->SESS,true),PHP_EOL);
			// add the cookie information
			$stuff .= sprintf('COOOKIES: %s%s',print_r($_COOKIE,true),PHP_EOL);
			
			return $this->TESTING = sprintf('<pre>%s</pre>',$stuff); 
		}	
		$this->TESTING = '';  // make sure this is null!!!
	}
	
	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _tags(){
		// look for tags file in basedir
		if(!$this->TAGS = $this->_set_local('tags')){
			$TAGS = <<<TAGS
<!--   TAGS   -->
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WWQDVG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WWQDVG');</script>
<!-- End Google Tag Manager -->
<!-- / TAGS / -->
TAGS;
			$this->TAGS = minify($TAGS);
		}
		return;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _header(){
		$TITLE = ($this->title)?:'Outspoken.Ninja';	
		$DESC  = ($this->description)?:'Outspoken.Ninja';
		$MIXIN = ($this->MIXIN)?:'<!-- mix -->';
		//$BASE  = $this->BASE;
		$BASE  = '';

		$HEAD = <<<HEAD
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head> 
<!-- <script src="//load.sumome.com/" data-sumo-site-id="9d938e0a6eddf6ceac010347f603699bcec25e6e0af7ab1be18b7ce653f9cecc" async="async"></script> -->
<meta charset="utf-8">
<base href="${BASE}" />
<title>${TITLE}</title>
<meta property="og:site_name" content="Outspoken Ninja"/>
<meta property="og:title" content="${TITLE}"/>
<meta property="og:description" content="${DESC}"/>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
$MIXIN
</head>  
HEAD;
		$this->HEADER = minify($HEAD);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _banner(){
		$this->BANNER = minify($this->BN->make());
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _main_body(){
		if(!$this->MAIN_BODY){
			return $this->MAIN_BODY = minify($this->_set_local('body'));
		}
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _footer(){

		$FOOTER = <<<FOOTER
<div id="copyright">
  Copyright &copy; 2014 - 2015 <br>Outspoken.Ninja
</div>
<div id="geotrust">
  <!-- GeoTrust QuickSSL [tm] Smart  Icon tag. Do not edit. -->
  <br><script type="text/javascript" src="//smarticon.geotrust.com/si.js"></script> 
  <!-- end  GeoTrust Smart Icon tag -->
</div>
FOOTER;
		$this->FOOTER = minify($FOOTER);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _analytics(){
		// look for localized analytics file, use instead if
		// found.
		$flyts = str_replace('.php','.analytics',$this->scriptFile);
		$ANALYTICS = '';
		
		if(file_exists($flyts)){
			$ANALYTICS .= '<!-- CA -->'.PHP_EOL.file_get_contents($flyts);
		}
		else {
			$ANALYTICS .= '<!-- GA -->'.PHP_EOL.get_analytics();
		}
		$this->ANALYTICS = minify($ANALYTICS);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _lastloads(){
		$this->LASTLOADS = '<script type="text/javascript" src="/js/fbl.js"></script>';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  determine the base path for given file, so that 
	//  other localized content dropped there can be 
	//  automatically injested for re-publishing
	//
	private function _calc_basedir(){
		$this->scriptFile = (@$_SERVER['SCRIPT_FILENAME'])?:'';
		$this->baseDir = dirname($this->scriptFile);
	}


	// - - - - - - - - - - - - - - - - - - - - - -
	//  simplify adding of Google Mapping script
	//  links for any page requiring them
	//
	private function _add_google_mapping(){
		return;  // do not add these for now!
		$this->jsfile('https://maps.googleapis.com/maps/api/js?key=AIzaSyAvMdI5UPz9CP3_fq-jf18BNlEXjqgsmIU&sensor=true');
		$this->jsfile('https://maps.googleapis.com/maps/api/js?libraries=visualization&sensor=true_or_false');	
		$this->jsfile('/js/search.map.js');	
		return;
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	
	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function http_response($code='404'){
		return $this->http_code = $code;
	}
	
	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function set($key=false,$value=false){
		if(!$key){
			return $key;
		}
		return $this->$key = $value;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function feedback_float($float_id=0){
		if($float_id){
			$this->FLOAT = '<div id="fbfloat"><button onClick="launch_feedback();"><span class="request"><span id="fbrotate">[+]</span> feedback</span><span class="reply">Click to<br>provide<br>feedback</span></button></div>';
		}
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function main_body($content='',$append=false){
		$this->MAIN_BODY = minify(($append) ? $this->MAIN_BODY.$content : $content);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function css($link=''){
		return $this->MIXIN .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />%s',$link,PHP_EOL);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function jsfile($link=''){
		$js = sprintf('<script type="text/javascript" src="%s"></script>%s',$link,PHP_EOL);
		$this->MIXIN .= $js;
		return $js;  // send a copy back
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function jsraw($js=''){
		$js = sprintf('<script type="text/javascript">%s</script>%s',$js,PHP_EOL); 
		$this->MIXIN .= $js;
		return $js;  // send a copy back
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   flag adds google map components
	//
	public function add_google_maps(){
		return '';   // do not include these for now
		return 
			'<!-- GOOGLE MAPPING -->'.PHP_EOL.
			$this->jsfile('https://maps.googleapis.com/maps/api/js?key=AIzaSyAvMdI5UPz9CP3_fq-jf18BNlEXjqgsmIU&sensor=true').PHP_EOL.
			$this->jsfile('https://maps.googleapis.com/maps/api/js?libraries=visualization&sensor=true_or_false').PHP_EOL.
			$this->jsfile('/js/search.map.js').PHP_EOL.
			'<div id="googleMap"">Loading Map...<br><img src="/assets/searching.gif"></div>';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   add items required for the search to
	//   function into the mix
	//
	public function enable_search(){
		$this->css('/css/search.css');
		$this->css('/css/flags.css');
		$this->jsfile('/js/search.js');
		//$this->jsfile('/js/search.map.js');
		return;
	} 

	// - - - - - - - - - - - - - - - - - - - - - -
	//   add in the parts needed to make the 
	//   image slider work
	//
	public function add_image_slider(){
		$this->css('/jsImgSlider/themes/1/js-image-slider.css');
		$this->jsfile('/jsImgSlider/themes/1/js-image-slider.js');
		return;
	}
	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function render($content=''){
		// if content is set, then replace all of main body with that content
		// retaining only the page chrome

		$this->_title();
		$this->_header();
		$this->_tags();
		$this->_banner();
		$this->_main_body();
		$this->feedback_float(5);
		$this->_footer();
		$this->_analytics();
		$this->_lastloads();
		// get the session data
		$this->_sessTest();

		print <<<HTML
$this->HEADER
<body>
  <!--  TG -->
  $this->TAGS
  <!-- BN -->
  <div id="head"> $this->BANNER </div>
  <!-- MESSAGING -->
  <div id="messaging"></div>
  <!-- MB -->
  <div id="main"> 
  <noscript><div id="noscript"><h2>No JavaScript?  SAY IT'S NOT SO!</h2><p>Outspoken Ninja relies upon JavaScript to provide search and dynamic content delivery.
Please enable JavaScirpt to get the most out of this website.</p><h2>ありがとう！</h2></div></noscript>
  $this->MAIN_BODY 
  </div>
  <!-- FF -->
  $this->FLOAT
  <!-- FT -->
  <div id="foot"> $this->FOOTER </div>
  <!-- AL -->
  $this->ANALYTICS
  $this->TESTING
  $this->LASTLOADS
</body>
</html>
HTML;

	}

} /* end of class */

?>
