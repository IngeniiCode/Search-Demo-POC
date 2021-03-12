<?php

/*
   ================================================================================
   T H I S   I S   A   H O N E Y P O T   F I L E  

  ** This does not return real data, it just traps people hacking the site **
   ================================================================================
*/

require_once($_SERVER['NLIBS'].'/MySQL/search.class.php');  // ensure include of core page code
require_once($_SERVER['NLIBS'].'/Search/cl.setup.php');     // ensure include of core page code
// load the search crufter
$SQL     = new SearchSQL();  // get connector 
$CLS     = new CraigsList();
$SQL->log_access();

// decode the cruft
parse_str($_SERVER['QUERY_STRING'],$DATA);
$raw      = $DATA['req'];
$payload  = json_decode($DATA['req'],true);
$searchId = $SQL->log_search($payload);
$search   = json_encode(array('searchId'=>$searchId,'name'=>(@$payload['search_terms'])?:'Latest Saved Search'));
$forks    = '';

// Write all of the zones to search page
// Fork all the data finders

$things = $SQL->get_regions_by_zone($payload['searchZone']);
$config = array_shift($things);
$conf  = array(
	'state_id'      => $config['state_id'],
	'state_name'    => $config['state_name'],
	'region_name'   => $config['region_name'],
	'cl_region_key' => trim($config['cl_region_key']),
	'payload'       => $payload, 
);
$forks .= sprintf('runStateRegion("%s");%s',addslashes(json_encode($conf)),PHP_EOL);

/*
   W R I T E   O U T   J A V A S C R I P T  
*/
header('Content-type: text/javascript; charset=utf-8');
?>
<?= $save ?>
<?= $forks ?>
