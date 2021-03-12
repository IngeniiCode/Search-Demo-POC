<?php

require_once($_SERVER['NLIBS'].'/Solr/provider.search.php');

/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-Type: application/json');

// Grab the ep value from input
$Q = array_merge($_GET,$_POST);
$SQ = array();

// only pull through fields that have some relevant data!
foreach(json_decode($Q['ep'],true) as $key => $value){
	if($value){
		$SQ[$key] = $value;
	}
};

$PROV = new Providers();
$RESP = @json_decode($PROV->get_providers($SQ),true);
$providersFound = (count(@$RESP['response']['docs']))?:0;

$JSON = json_encode(@$RESP['response']['docs']);
?>
<?= $JSON ?>
