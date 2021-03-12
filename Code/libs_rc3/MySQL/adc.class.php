<?php 

class mysqlADC {
	protected $conn;
	protected $PROD;
	protected $ADC;
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
		$this->ADC   = null;
		$this->DEBUG = null;
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E   M E T H O D S 
	  ------------------------------------------------------
	*/

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 

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
		$this->socket   = '/tmp/mysql.sock';
		$this->socket   = '/var/lib/mysql/mysql.sock';

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
