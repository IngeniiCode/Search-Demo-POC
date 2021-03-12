<?php
/*
   R E Q U I R E S 
*/
$L = (getenv('NLIBS'))?:$_SERVER['NLIBS']; // generate library path:
require_once($L.'/Core/utils.lib.php');
require_once($L.'/Solr/prime.class.php');

/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-type: text/javascript; charset=utf-8');

$OPTS = getopt('',array(
	'terms:',    // terms
	'location:'  // location
));

// Grab the ep value from input
$PRIME     = new solrPrime();   // instance PRIME Solr API
$filepath  = dirname(__FILE__);
$providers = array();
$reply     = array();
$json      = false;
$cnt_found = 0;
$Q         = array_merge($_GET,$_POST,$OPTS);  // merge command line and web vars
$terms     = trim($Q['terms']);
$location  = trim($Q['location']);
$query     = sprintf('?p="%s"+NEAR%%3A%s&ei=UTF-8&hspart=mozilla&hsimp=yhs-001',urlencode($terms),urlencode($location));
$url       = sprintf('https://search.yahoo.com/yhs/search?p="%s"+NEAR%%3A%s&ei=UTF-8&hspart=mozilla&hsimp=yhs-001',$terms,$location);
$command   = sprintf("%s/yahoo.terms.cjs '%s'",$filepath,$query);

/*
  E X E C U T I O N   M A I N 
*/
printf("SEARCH: '%s' near %s\n",$terms,$location);
foreach(explode("\n",forkCasper($command)) as $resp){
	if($links = json_decode($resp,true)){ 
		foreach($links as $prov){
			$command = sprintf("%s/yahoo.card.parse.js '%s'",$filepath,$prov['engine_url']);
			$reply = forkNode($command);
			if($card = @json_decode($reply,true)){
				$cnt_found++;  // increment counter of links found
				// generate an ID
				$id = id_gen(($card['home_url'])?:$card['phone_num']);
				
				// build provider profile
				$provider = array_merge(array('id'=>$id),$prov,$card);
				$provider['discovered']       = solrTimestamp();
				$provider['engine_origin'][]  = 'yahoo';
				$provider['services'][]       = strtolower($terms);  // downshift it for consistancy in dataset
				$provider['seed_terms'][]     = $terms;
				$provider['seed_locations'][] = $location;

				// store into output list
				$providers[$id] = $provider; 
			}
		}

		// collect all ids into a list and iterate into an ID query
		$solrstat = $PRIME->sync_providers($providers);

		// check for errors
		if($results = json_decode($solrstat,true)){
			if($results['responseHeader']['status'] == 0){
				$PRIME->write();  // force write
			}
			else {
				printf("WRITE ERROR: %s\n",print_r($results,true));
			}
		}
		else {
			printf("SOLR ERROR: [%s]\n",$solrstat);
		}
	}
}

printf("DONE: %d '%s' Providers from %d Links\n",count($providers),$terms,$cnt_found);

// -------------------------------------------------
//  create a simple id selection string for 
//  requesting ids in bulk
//
function idq($val){
	return sprintf('id:"%s"',trim($val));
}

?>

