#!/usr/bin/php
<?php
date_default_timezone_set("UTC"); // REQUIRED for DATE() to work.

require_once('proxy.test.class.php');
$OPTS = getopt('',array(
	'url:',
	'testcl:',
));
$URL  = (@$OPTS['url']);
$DB   = new ProxySQL();
$PROX = new ProxyTest();

$proxies = $DB->get_active_proxies();

// loop through the proxies
foreach($proxies as $prox){
	$PROX->validate($prox);
	//exit;
}

?>
