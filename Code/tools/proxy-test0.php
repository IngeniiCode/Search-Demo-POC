<?php

$host = '54.157.130.240';
$port = 55001;
$port = 1080;
/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-type: text/html; charset=utf-8');
/*
  G R A B   R E M O T E   P A G E
*/
$cH = curl_init();
curl_setopt($cH, CURLOPT_URL,'http://www.daviddemartini.com/headertest.php');
curl_setopt($cH, CURLOPT_FRESH_CONNECT, true);
curl_setopt($cH, CURLOPT_MAXREDIRS, 16);
curl_setopt($cH, CURLOPT_TIMEOUT, 30);
curl_setopt($cH, CURLOPT_PROXY, $host);
curl_setopt($cH, CURLOPT_PROXYPORT, $port);
//curl_setopt($cH, CURLOPT_PROXYTYPE, 7);  // 7 = CURLPROXY_SOCKS5_HOSTNAME
curl_setopt($cH, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);  
curl_setopt($cH, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
curl_setopt($cH, CURLOPT_RETURNTRANSFER, true);

echo curl_exec($cH);

?>
