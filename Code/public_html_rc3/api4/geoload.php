<?php

// load the search crufter
require_once($_SERVER['NLIBS'].'/MySQL/main.class.php');
require_once($_SERVER['NLIBS'].'/Solr/geo.class.php');

$DB  = new MySQL();
$GEO = new solrGeo();

foreach($DB->get_all('SELECT * FROM geoCode order by zip asc') as $rec){
	$resp = $GEO->add_location($rec);
	printf("ADD: %s\n",$resp);	
}

$GEO->write();
?>
