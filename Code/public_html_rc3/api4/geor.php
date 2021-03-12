<?php

// load the search crufter
require_once($_SERVER['NLIBS'].'/MySQL/main.class.php');
require_once($_SERVER['NLIBS'].'/Solr/geo.class.php');

$DB  = new MySQL();
$GEO = new solrGeo();

$block = 100;
$curr  = 0;
do {
	$data = $DB->get_all("SELECT * FROM geoCode order by zip asc LIMIT $curr,$block");
	$resp = $GEO->add_locations($data);
	$curr += $block;
	printf("SAVED: %d %s\n",$curr,$resp);
} while (count($data));

$GEO->write();
?>
