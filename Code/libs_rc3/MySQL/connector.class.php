<?php 

class mysqlConnect {
	protected $conn;
	protected $PROD;
	protected $IG;
	protected $DEBUG;
	private   $hostname;
	private   $user;
	private   $password;
	private   $database;
	private   $socket;

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	public function __construct($config=false){
		// set connection flags
		
		// make data connections.
		$this->_mysql_connect($config);

		// Verify that there is a connection before continuing
		if(!$this->conn) {
			die("No MySQL connection established\n");
		}
		$this->_query('SET NAMES utf8'); // forgot why we do this already...
	}

	// ------------------------------------------------------------
	//   destructor to help the GC close out memory use
	//
	protected function __distruct(){
		$this->conn  = null;
		$this->PROD  = null;
		$this->IG    = null;
		$this->DEBUG = null;
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E   M E T H O D S 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	private function response_id($ip=false,$zip='00000',$surveyId='0',$email='null@dev.null'){
		$timestring = date('YmdHi',time());  // 1 hour granularity
		$ip         = ($ip)?:get_client_ip();
	
		// create and return the code
		return id_gen($ip.$zip.$surveyId.$email); 	
	}


	/*
	  ------------------------------------------------------
	  -- P R O T E C T E D   M E T H O D S 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	protected function _mysql_connect() {
		$this->hostname = 'localhost';
		$this->user     = 'ninjaadmin';
		$this->password = 'mrd5813y';
		$this->database = 'ninja_prod';
		if(!isset($_SERVER['NINJAPROD'])){
			$this->socket   = '/tmp/mysql.sock';
			$this->socket   = '/var/lib/mysql/mysql.sock';
		}

		if($this->DEBUG){
			printf("Establish MySQL Connection to %s\n",$this->hostname);
		}

		$this->conn = new mysqli($this->hostname,$this->user,$this->password,$this->database,null,$this->socket);

		if($this->DEBUG){
			printf("Connection %s\n",print_r($this->conn,true));
		}
		
		// test the connection
		if(mysqli_connect_errno()){
			die("FATAL ERROR ".mysqli_connect_errno().":\tCannot establish MySQL Connection to $this->hostname\n");
		}

		return;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//   `ip` char(16) NOT NULL,
	//   `id` char(22) NOT NULL DEFAULT '',
	//   `when` timestamp,
	//   `page` varchar(64),
	//   `agentId` char(22) NOT NULL,
	//
	public function log_access(){
		$agentId = addslashes(id_gen(@$_SERVER['HTTP_USER_AGENT']));
		$refId   = addslashes(id_gen(@$_SERVER['HTTP_REFERER']));
		// log the entry
		$this->run(sprintf('INSERT IGNORE INTO accessLog VALUES("%s","%s",NOW(),"%s","%s","%s")',
			get_client_ip(),
			(@$_COOKIE['x-auth'])?:' - guest - ',
			addslashes(@$_SERVER['SCRIPT_NAME'].@$_SERVER['QUERY_STRING']),
			$refId,
			$agentId
		));
		// save this user agent
		$this->run(sprintf('INSERT IGNORE INTO userAgent VALUES("%s","%s")',
			$agentId,
			addslashes(@$_SERVER['HTTP_USER_AGENT'])
		));
		// save this refering site
		$this->run(sprintf('INSERT IGNORE INTO refererSite VALUES("%s","%s")',
			$refId,
			addslashes(@$_SERVER['HTTP_REFERER'])
		));
		// safe the referer if set
		
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	//   | responseId | varchar(26) | NO   | PRI | NULL                |       |
	//   | when       | timestamp   | NO   |     | 0000-00-00 00:00:00 |       |
	//   | ip         | varchar(18) | YES  |     | NULL                |       |
	//   | zip        | char(5)     | YES  |     | NULL                |       |
	//   | data       | text        | YES  |     | NULL                |       |
	//
	public function store_survey($DATA=array()) {

		$ip     = get_client_ip();
		$when   = 'NOW()';  // sql equiv of now
		$resp   = (@$DATA['response'])?:'00000';  //  response sets some key IDs
		$surId  = (@$DATA[$resp])?:0;   //  the survey ID is stored in the response key, not exactly secret but not totally obvious
		$zip    = addslashes((@$DATA['ninja_zip_code'])?:'00000');
		$email  = addslashes((@$DATA['ninja_email'])?:'null@dev.null');
		$data   = addslashes(json_encode($DATA));
		$respId = $this->response_id($ip,$zip,$surId,$email);

		// Insert the detailed response record		
		$SQL = sprintf('INSERT IGNORE INTO surveyResponse VALUES("%s",%s,"%s","%s","%s")',$respId,$when,$ip,$zip,$data);

		// Insert the summary record
		$resp = $this->run($SQL);
	
		return $SQL;

	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	protected function connect($config=false) {
		return $this->_mysql_connect($config);
	}

	/*
	  ------------------------------------------------------
	  -- P U B L I C   M E T H O D S 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	public function destruct(){
		if(isset($this->conn)){
			$this->conn->close();
		}
	}
	
}  /*  -- END OF CLASS -- */
