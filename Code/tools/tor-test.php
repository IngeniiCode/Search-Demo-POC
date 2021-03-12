<?php
$hostname = 'tprox';
$hostname = '127.0.0.1';
$port     = 9050;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://whatismyip.org");
curl_setopt($ch, CURLOPT_PROXY, $hostname);
curl_setopt($ch, CURLOPT_PROXYPORT, $port);
//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($ch, CURLOPT_PROXYTYPE, 7);  // 7 = CURLPROXY_SOCKS5_HOSTNAME
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);

$response = curl_exec($ch);
printf("REPLY %s\n",$response);

?>
