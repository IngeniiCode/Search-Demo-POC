<?php

require_once('../MySQL/search.class.php');   // ensure include of core page code

$Zlookup = array();

$SQL = new SearchSQL();

foreach( $SQL->get_searchStates() as $zone){
	$key = trim(strtolower($zone['state_name']));
	$Zlookup[$key] = $zone;
}

foreach(json_decode(file_get_contents('./cldata/cl_us_engine.fat.json'),true) as $key => $data){
	$key = trim(strtolower($key));
	$zone =  $Zlookup[$key];
	foreach($data as $region => $rconf) {
		$SQL->write_regionCL(array(
			'cl_region_key' => addslashes($region),
			'region_name'   => addslashes($rconf['name']),
			'state_id'      => $zone['state_id'],
			'zone_id'       => $zone['zone_id'],
			'lat'           => $rconf['geo']['lat'],
			'lon'           => $rconf['geo']['lng'] 
		));
	}
}

//printf("REGIONAL %s\n",print_r($CLRegions,true));
//printf("ZLookup %s\n",print_r($Zlookup,true));

?>
