<?php 

require_once($_SERVER['NLIBS'].'/Core/utils.lib.php');
require_once('main.class.php');

class ProfileSQL extends MySQL {
	
	private $archive_path;
	private $uname;  // user name (e-mail)
	private $id;     // user id
	private $code;   // verification code
	private $pwd;    // password value
	private $DATA;   // declare the data store
	private $NO;     // store hack removals
	private $ERR;    // holds signin or other errors

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
        public function __construct($DATA=array()) {
		// store data blob
		$this->DATA = $DATA;
		
		// init sql library
                parent::__construct();  // init parent class.
		
		// configure the anti-hacking array
		$this->nohack();

		// check to see if user known, and if so
		// set that to the module data storage
		if($results = $this->user_lookup(@$DATA['x_a'])){
			$user = $results[0];
			$this->DATA  = array_merge($DATA,$user);
			$this->id    = $user['id'];
			$this->uname = $user['name'];
		}
		else {
			$this->uname = strtolower((trim(@$DATA['user']))?:trim(@$DATA['new_user']));  // trim and downshift username
			$this->id    = (@$DATA['x_a'])?:id_gen($this->uname);
			$this->pwd   = @$DATA['pwd'];
		}
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E   M E T H O D S 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	private function nohack(){
		$this->NO = array('#\bSELECT\b#i','#\bDELETE\b#i','#\bcount\(#i','#\binsert\b#i','#;#');
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _safe($val){
		$val = trim($val);
		$this->NO = array('#\bSELECT\b#i','#\bDELETE\b#i','#\bcount\(#i','#\binsert\b#i','#;#');
		// scrub out any strings that would appear to be SQL Injection Hacks
		return addslashes(preg_replace($this->NO,' ',$val));
	}
	
	/*
	  ------------------------------------------------------
	  -- P R O T E C T E D   M E T H O D S 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//  simply update the verification code, if the user is
	//  unverified 
	//
	protected function verify_account($code){
		$sql = sprintf('UPDATE profileVerify pv JOIN profile p USING(id) SET p.status="verified",p.verified=NOW(),updated=NOW() WHERE pv.key="%s" AND pv.type="verify_account" AND p.status="pending";',addslashes($this->_safe($code)));
		return $this->run($sql);
	}
	
	/*
	  ------------------------------------------------------
	  -- P U B L I C   M E T H O D S 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//   get a value
	// 
	public function get($vname=false){
		if(!$vname) {
			return $this->DATA;
		}
		return @$this->$vname;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	//  `ip` char(16) NOT NULL,
	//  `id` char(22) NOT NULL DEFAULT '',
	//  `uname` varchar(255) NOT NULL,
	//  `when` timestamp,
	//  `data`  text,
	//  `useragent` varchar(128) NOT NULL,
	//
	public function log_login_attempt(){
		$this->ERR = '';
		$this->run(sprintf('INSERT IGNORE INTO logins VALUES("%s","%s","%s",NOW(),"%s","%s")',
			get_client_ip(),
			$this->_safe($this->id),
			$this->_safe($this->uname),
			addslashes(json_encode($this->DATA)),
			$this->_safe(@$_SERVER['HTTP_USER_AGENT'])
		));
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	public function user_valid(){
		$sql = sprintf('SELECT id FROM profile WHERE id="%s";',$this->_safe($this->id));
		$result = $this->get_one($sql);
		//printf("%s SQL: %s\n%s RESULT: %s\n",__METHOD__,$sql,__METHOD__,print_r($result,true));
		return $result;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	public function user_already_registered(){
		$this->ERR = 'Account already registered with that name';
		$sql = sprintf('SELECT id FROM profile WHERE id="%s";',$this->_safe($this->id));
		$result = $this->get_one($sql);
		return $result;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	public function user_lookup($id='-no-user-name-given-'){
		$sql = sprintf('SELECT * FROM profile p LEFT JOIN profileType t USING(id) WHERE p.id="%s";',$this->_safe($id));
		if($result = $this->get_all($sql)){
			return $result;
		}
		return false;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	public function user_credentials_correct(){
		$this->ERR = 'Account already registered with that name';
		$sql = sprintf('SELECT id FROM profile WHERE id="%s" AND pswdenc="%s" AND status IN("pending","verified");',$this->_safe($this->id),$this->_safe(key_gen($this->pwd)));
//printf("%s-SQL: %s\n",__METHOD__,$sql);
		$result = $this->get_one($sql);
		return $result;
	}
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	public function create_new_user(){
		$this->ERR = '';
		// create profile entry
		//  `id`  char(22) NOT NULL DEFAULT '',
		//  `fb_id`  int NULL,
		//  `ip`  char(16) NOT NULL,
		//  `status` enum('pending','verified','expired','blocked') NOT NULL default 'pending',
		//  `uname` varchar(255) NOT NULL,
		//  `pswdenc` varchar(48) NOT NULL,
		//  `added` timestamp NOT NULL,
		//  `verified` timestamp NOT NULL,
		//  `updated` timestamp NOT NULL,
		if($this->run(sprintf('INSERT INTO profile VALUES("%s","","%s","pending","%s","%s",NOW(),"",NOW())',
			$this->_safe($this->id),
			get_client_ip(),
			$this->_safe($this->uname),
			$this->_safe(key_gen($this->pwd))
			))){

			// create profileType entry
			//  `id`  char(22) NOT NULL DEFAULT '',
			//  `type` enum('free','basic','premium','pro','admin','api') NOT NULL DEFAULT 'free',
			//  `updated` timestamp NOT NULL,
			//  `updated_id` char(22) NOT NULL DEFAULT '',
			$this->run(sprintf('INSERT IGNORE INTO profileType VALUES("%s","free",NOW(),"%s")',
				$this->_safe($this->id),
				$this->_safe($this->id)
			));

			// create profileType entry
			//  `id`  char(22) NOT NULL DEFAULT '',
			//  `type` enum('verify_account','pwd_reset') NOT NULL DEFAULT 'verify_account',
			//  `key` char(43) NOT NULL DEFAULT '',
			//  `verified` timestamp NOT NULL,
			//  `exipres` timestamp NOT NULL,
			$this->code = key_gen($this->id.$this->pwd.time());
			$this->run(sprintf('INSERT IGNORE INTO profileVerify VALUES("%s","verify_account","%s","",DATE_ADD(NOW(),INTERVAL 1 HOUR))',
				$this->_safe($this->id),
				$this->_safe($this->code)
			));
		}
		else {
			$this->ERR = "Unexpected Error occured creating account.  Unable to continue at this time.";
		}
		return $this->id;
	}
}

