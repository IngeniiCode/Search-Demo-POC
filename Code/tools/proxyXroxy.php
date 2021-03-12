#!/usr/bin/php
<?php

require_once('proxy.test.class.php');

$PROX = new ProxyTest();

foreach(array(
        'http://www.xroxy.com/proxylist.php?port=&type=Anonymous&ssl=&country=CA&latency=&reliability=&sort=reliability&desc=true&pnum=%d#table',
        'http://www.xroxy.com/proxylist.php?port=&type=Anonymous&ssl=&country=US&latency=&reliability=&sort=reliability&desc=true&pnum=%d#table',
        ) as $base ) {

        $ct       = 0;
        $max_page = 0;

        // --- do the proxy page parsing loop ---
        do {
                $url = sprintf($base,$ct++);
		$PROX->xroxy_parse($url);	
                //$max_page = $PROX->xroxy_last_page();
                sprintf("Max Pages: %d\n",$max_page);
		exit;
        } while ($ct < $max_page);

}

?>
