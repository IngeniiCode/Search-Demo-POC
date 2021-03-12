<?php 

require_once($_SERVER['NLIBS'].'/Core/utils.lib.php');
require_once('main.class.php');

class ProxySQL extends MySQL {
	
	private $searchId     = '';
	private $userId       = '';
	private $membership   = '';

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/
	public function __construct($settings=array()){
		parent::__construct($settings);  // Execute partent construction
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

	
	/*
	  ------------------------------------------------------
	  -- P U B L I C   M E T H O D S 
	  ------------------------------------------------------
	*/

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//   write proxies into the database
	// 
	public function write_proxies($data){
		$sqlInsert = 'INSERT IGNORE INTO proxyPool (host,port,added_on) VALUES ';
		$inputs    = array();
		// iterate and write!!
		foreach(explode("\n",$data) as $line){
			if($proxy = trim($line)){
				list($host,$ip) = explode(':',trim($line));
				$inputs[] = sprintf("('%s',%d,NOW())",$host,$ip);
			}
		}
		$SQL = sprintf('%s %s;',$sqlInsert,join(',',$inputs));

		return $this->run($SQL);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//   select proxies that have not yet been tested
	//
	public function get_active_proxies(){
		$sql = 'SELECT host,port FROM proxyPool where status="up" order by added_on asc';
		return $this->get_all($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//   select proxies that have not yet been tested
	//
	public function get_untested_proxies(){
		$sql = 'SELECT host,port FROM proxyPool where status="new" order by added_on asc';
		return $this->get_all($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//   select proxies that have not yet been tested or 
	//   are currently SL safe
	//
	public function get_testable_proxies(){
		$sql = 'SELECT host,port FROM proxyPool where status="new" OR cl_safe="yes" order by added_on asc';
		return $this->get_all($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//   update proxy
	//
	public function update_proxy($proxy){
		$fields = array_keys($proxy);
		$values = array_map(function ($val) { return '"'.addslashes(trim($val)).'"'; },$proxy);
		// array_map ( callable $callback , array $array1 [, array $... ] )
		$SQL = sprintf('REPLACE INTO proxyPool (%s) VALUES (%s)',join(',',$fields),join(',',$values));
		//printf("%s %s\n",__METHOD__,$SQL);
		$this->run($SQL);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function generic(){ 

	}

}

