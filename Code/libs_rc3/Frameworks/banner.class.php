<?php

require_once('session.class.php');

class Banner extends Session {

	private $BANNER     = false;  // banner  
	private $MENU       = '';
	private $loggedin   = false;  // user logged in flag
	private $jSession   = '{}';   // session container
	private $FB_API_ID  = '';
	private $FB_SECRET  = '';

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		// perform some initialization actions here
		parent::__construct();  // init parent class.
		$this->jSession     = json_encode($_SESSION);
		$this->FB_API_ID    = '1520439241504815';
		$this->FB_SECRET    = '9d91febd1d8f24c727facf0ad3453e0d';
		$this->profile_page = host_url().'/Profile/index.php';

		// check to see if user is logged in or not
		
	}

	// - - - - - - - - - - - - - - - - - - - - - -

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _finish_loading(){
		$FB   = $this->check_fb_login();
		$MENU = $this->gen_menu();

		$this->BANNER = <<<BANNER
<!--   BANNER   -->
<div>  
  ${FB}
  <div id="site_logo">
    <a href="/index.php"><img src="/igx/logo.70px.png" alt="Outspoken.Ninja -- HOME"><div id="logotext">Outspoken<br>Ninja</div></a>
  </div>
  ${MENU} 
  <div id="loginScreener">
    <div id="loginContainer"> 
      <div id="loginCard">
      </div>
    </div>
  </div>
  <div id="feedbackScreener">
    <div id="feedbackContainer">
      <div id="feedbackCard">
      </div>
    </div>
  </div>
</div>
<!-- / BANNER / -->
BANNER;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _menu_item($name='',$link='',$id='',$class=''){
		$this->MENU .= sprintf('<li%s%s><a href="%s" alt="%s">%s</a></li>',(($id)?" id='$id'":""),(($class)?" class='$class'":""),$link,$name,$name);
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _menu_action($name='',$action='',$id='',$class=''){
		$this->MENU .= sprintf('<li%s%s><a onClick="%s" alt="%s">%s</a></li>',(($id)?" id='$id'":""),(($class)?" class='$class'":""),$action,$name,$name);
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	protected function check_fb_login(){
		return <<<FB
<div id="fb_login">
   <div id="fb-root"></div>
   <div class="fb-like" 
       data-href="https://www.outspoken.ninja" 
       data-layout="button_count" 
       data-action="like" 
       data-show-faces="true" 
       data-share="true">
   </div>
</div>
FB;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	protected function gen_menu(){
		// determine class for some of these options
		// based upon logon status
		$set1 = 'btn_menu menu_hidden';
		$set2 = 'btn_menu menu_visible';
		if($this->x_auth){
			$set1 = 'btn_menu menu_visible';
			$set2 = 'btn_menu menu_hidden';
		}
		$this->_menu_item('Search','/index.php','mnu_search','btn_menu');
		$this->_menu_item('Dashboard','/dashboard.php','mnu_dashboard','btn_menu'); 
		if($this->x_auth){
			//  this will only appear for 'admin' users
			//$this->_menu_item('Metrics','/Metrics/mtx.php','mnu_metrics','btn_menu'); 
		}
//		$this->_menu_item('Events','/Events','mnu_events','btn_menu'); 
//		$this->_menu_item('Articles','/Articles','mnu_articles','btn_menu'); 
		$this->_menu_item('Membership','/Membership','mnu_membership','btn_menu');
		$this->_menu_item('About','/Info','mnu_info','btn_menu');
//		$this->_menu_item('Profile','/Profile','menu_profile',$set1);
		$this->_menu_action('Logout','logout();','menu_logout',$set1);
		$this->_menu_action('Login','launch_login();','menu_logon',$set2);

		return '<div id="menu"><ul>'.$this->MENU .'</ul></div>';
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function make() {
		$this->_finish_loading();
		return $this->BANNER;
	}
}

?>
