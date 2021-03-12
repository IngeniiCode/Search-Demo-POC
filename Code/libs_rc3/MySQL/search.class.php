<?php 

require_once($_SERVER['NLIBS'].'/Core/utils.lib.php');
require_once('main.class.php');

class SearchSQL extends MySQL {
	
	private $searchId     = '';
	private $userId       = '';
	private $membership   = '';

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/
	public function __construct($settings=array()){
		$this->userId     = (@$settings['x_a'])?:@$_COOKIE['x-auth'];
		$this->membership = (@$settings['mbrshp'])?:0;
		$this->searchId   = (@$settings['searchId'])?:0;
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

	// - - - - - - - - - - - - - - - - - - - - - - - - - - -
	//
	//  `ip` char(16) NOT NULL,
	//  `id` char(22) NOT NULL DEFAULT '',
	//  `dtime` timestamp,
	//  `type`  varchar(8),
	//  `terms` varchar(64),
	//  `searchId` char(22),
	//
	public function log_search($data=array()){
		$searchData = json_encode($data);
		$searchId   = id_gen($searchData);
		// store the history entry 
		$this->run(sprintf('INSERT IGNORE INTO searchHistory VALUES("%s","%s",NOW(),"%s","%s","%s")',
			get_client_ip(),
			addslashes(($this->userId)?:' - guest - '),
			addslashes(@$data['type']),
			addslashes(@$data['search_terms']),
			addslashes($searchId)
		));
		// store the detailed data
		$this->run(sprintf('INSERT IGNORE INTO searchRequest VALUES("%s","%s")',
			addslashes($searchId),
			addslashes($searchData)
		));

		return $searchId;
	}
	
	/*
	  ------------------------------------------------------
	  -- P U B L I C   M E T H O D S 
	  ------------------------------------------------------
	*/

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function getAd($id){
		$sql = 'SELECT itemId,url FROM adsFound WHERE itemId="'.addslashes(trim($id)).'" LIMIT 1;';
		return $this->get_first($sql);
	}
	
	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function get_searchZones(){
		$sql = 'SELECT z.zone_id,z.zone_name,s.state_code FROM searchZone z join state s using(zone_id) order by z.zone_id asc';
		return $this->get_all($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function get_searchStates(){
		$sql = 'SELECT * FROM searchZone z join state s using(zone_id) order by z.zone_id asc';
		return $this->get_all($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function write_regionCL($data){
		if(!$data['state_id']) { return; }

		$fields = array();
		foreach($data as $key => $field){
			$fields[] = sprintf('%s="%s"',$key,$field);
		}
		
		$sql = 'INSERT INTO searchRegionCL SET '.join(',',$fields);
		return $this->run($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function get_regions_by_zone($zone_id=0){
		$sql = 'SELECT * FROM searchRegionCL srcl join state s using(state_id) where srcl.zone_id='.$zone_id.' order by s.zone_id asc';
		return $this->get_all($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//    locate the user's searches, based upon the
	//    level of their membership
	public function get_user_searches(){
		$base  = 'SELECT ss.*,sr.search FROM searchRequest sr JOIN searchSaved ss USING(searchID) WHERE ss.id="%s" order by saved desc limit %d';
		$count = 0;
		switch($this->membership){
			case 'api':
			case 'pro':
			case 'premium':
			case 'basic':
				$count = 5;
				break;
			default:
				return array();  // they are not signed in so no search results can be returned
		};
		$sql = sprintf($base,$this->userId,$count);
		return $this->get_all($sql);
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//    look for the saved search in table, use the logged
	//    in ID, or ID of 0.  Either one is acceptable for
	//    locating the search.
	public function get_saved_search(){
		if(strlen($this->searchId) < 22){ 
			// this is not a saved search
			return $this->get_one('SELECT search FROM searchDefaults WHERE searchId="'.$this->searchId.'";');
		}
		else {
			$sql = sprintf('SELECT sr.search FROM searchRequest sr JOIN searchSaved ss USING(searchID) WHERE sr.searchID="%s" AND ss.id="%s" order by saved desc limit 1',$this->searchId,$this->userId);
			return $this->get_one($sql);
		}			
 
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	//  | searchId | char(22)     | NO   | PRI |                   |                             |
	//  | id       | char(22)     | NO   | PRI | 0                 |                             |
	//  | name     | varchar(127) | NO   | MUL | Search            |                             |
	//  | saved    | timestamp    | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
	// 
	public function save_last_search($json='',$name=''){
		$tname = trim($name);
		if($details = json_decode($json,true)){
			$name = ($tname)?:$details['name'];
			$skey = id_gen($details['searchId'].$name);
			$sql = sprintf('REPLACE INTO searchSaved VALUES("%s","%s","%s","%s",NOW())',
				addslashes($skey),
                                addslashes($details['searchId']),
                                addslashes($this->userId),
                                addslashes($name)
                        );
			$this->run($sql);
		}
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//    track all of the downloads!
	//
	// | searchId   | char(22)  | NO   | MUL |                   |                             |
	// | id         | char(22)  | NO   | MUL | 0                 |                             |
	// | downloaded | timestamp | NO   | MUL | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
	//	
	public function log_download($json){
		if($details = json_decode($json,true)){
			$sql = sprintf('INSERT IGNORE INTO searchDownloads VALUES("%s","%s",NOW())',
                                addslashes($details['searchId']),
                                addslashes($this->userId)
			);
			$this->run($sql);
		}
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	
	public function saveItem($itemId,$url){
		// record the data
		$this->run(sprintf('INSERT IGNORE INTO adsFound values("%s","%s",NOW())',
			addslashes($itemId),
			addslashes($url)
		));
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//    track ad clickthroughs 
	//
	//  | ip          | char(16)  | NO   | PRI |                    |                             |
	//  | id          | char(22)  | NO   | PRI |  -- guest --       |                             |
	//  | itemId      | char(22)  | NO   | PRI |  -- NO ID FOUND -- |                             |
	//  | lastClicked | timestamp | NO   | MUL | CURRENT_TIMESTAMP  | on update CURRENT_TIMESTAMP |
	//
	public function clicked($id,$data=false){
		// record the event
		$this->run(sprintf('REPLACE INTO adsClicked values("%s","%s","%s",NOW())',
			get_client_ip(),
			addslashes(($this->userId)?:' - guest - '),
			addslashes($id)
		));

		if($data){
		// record the data
			$this->run(sprintf('INSERT IGNORE INTO adsClickedData values("%s","%s")',
				addslashes($id),
				addslashes($data)
			));
		}
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 	
	//
	public function generic(){ 

	}

}

