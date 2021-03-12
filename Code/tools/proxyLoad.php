#!/usr/bin/php
<?php

require_once(getenv('NLIBS').'/MySQL/proxy.class.php');

// include our database connector

$OPTS = getopt('',array(
	'infile:'
));
$DB = new ProxySQL();

printf("Infile: %s\n",$OPTS['infile']);

// Write Proxies
$DB->write_proxies(file_get_contents($OPTS['infile']));


?>
