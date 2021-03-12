#!/usr/bin/php
<?php

// load the search crufter
require_once($_SERVER['NLIBS'].'/Solr/geo.class.php');

$GEO      = new solrGeo();
$SEAT     = array();
$DOM      = new DOMDocument;
$reach    = 100;
$offset   = 0;
$numfound = 0;

// setup DOM
$DOM->strictErrorChecking = false;

// LOOP TO INFINITY AND BEYOND!

// select zip codes from database
do {
        $query = sprintf('?q=*:*&start=%d&rows=%d&fl=id,state,city',$offset,$reach);
        $DOCS = array();
        $DATA = json_decode($GEO->query($query),true);
        $numfound = $DATA['response']['numFound'];
        foreach($DATA['response']['docs'] as $geo){
                $county = county_seat($geo);
                $DOCS[] = array(
                        'id'      => $geo['id'],
                        'country' => array('set'=>'US'),
                        'county'  => array('set'=>$county)    // county seat data
                );
        }

        printf('UPDATE: %s%s',$GEO->update_docs($DOCS),PHP_EOL);

        $GEO->write();

        // increment the iteration
        $offset += $reach;

} while ($offset < $numfound);

printf("** DONE **\n");

/*
  F U N C T I O N S 
*/
function county_seat($geo){
	global $SEAT;
	
	$state = $geo['state'];
	$city  = $geo['city'];
	if(!isset($SEAT[$state])){
		// set the state
		$SEAT[$state] = array();
	}
	if(!isset($SEAT[$state][$city])){
		// set the city map to county name
		global $DOM;
		$county = '';
		$url = sprintf('http://quickfacts.census.gov/cgi-bin/qfd/lookup?place=%s',$geo['id']);
printf("URL: %s\n",$url);
		@$DOM->loadHTML(file_get_contents($url));
		foreach($DOM->getElementsByTagName('dd') as $node){
			list($cx,$county,$junk) = split(',',$node->nodeValue,3);
		}
		$SEAT[$state][$city] = $county;		
	}
	printf("%s, %s => %s\n",$city,$state,$SEAT[$state][$city]);
	return $SEAT[$state][$city];
}

?>
?>
