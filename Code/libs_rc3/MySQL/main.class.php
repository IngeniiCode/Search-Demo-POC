<?php 

require_once('connector.class.php');

class MySQL extends mysqlConnect {
	
	private $archive_path;

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/

	public function __construct(){
		$this->__init();
	}

	// ------------------------------------------------------------
	//   destructor to help the GC close out memory use
	//
	protected function __distruct(){
		$this->archive_path = null;
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E   M E T H O D S 
	  ------------------------------------------------------
	*/
	
	/*
	  ------------------------------------------------------
	  -- P R O T E C T E D   M E T H O D S 
	  ------------------------------------------------------
	*/

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//  this function replaces the use of __construct 
	//  so that __construct can very easily be extened by 
	//  subclasses, or left in tact.  Flexibility is the 
	//  goal
	//
	protected function __init($config=false) {
		// make data connections.
		$this->connect($config);
		//  set all names to utf8 -- for saftey!
		$this->run('SET NAMES utf8');	

		return;
	}

	
	/*
	  ------------------------------------------------------
	  -- P U B L I C   M E T H O D S 
	  ------------------------------------------------------
	*/

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//  escape a field, making it quote safe for inserts
	//
	public function esc($str=''){
		return $this->conn->real_escape_string($str);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//    close connection
	public function close(){
		$this->conn->close();
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//  wrapper for all query functions.	
	//	
	public function run($sql = 'SELECT 0') {
		// CONVERTED TO OO CALL
		unset($res);
		$res = $this->conn->query($sql);
		if($res) {
			return $res; 
		}
		return;
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function get_first($sql='SELECT 0') {
		$res = $this->run($sql);
		return $res->fetch_assoc();  // return first record, found
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function get_one($sql='SELECT 0') {
		if($res = $this->run($sql)){
			if($resp = $res->fetch_assoc()){
				return array_shift($resp); 
			}
		}
		return false; 
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function get_all($sql='SELECT 0') {
		$data = array();   // it's possible we'll run out of memory handling it this way
		$res = $this->run($sql);
		while ($row = $res->fetch_assoc()){
			$data[] = $row;  // push this into the stack
		}
		
		return $data; // return the stack.
	}

}

