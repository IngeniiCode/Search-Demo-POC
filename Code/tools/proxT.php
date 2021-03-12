#!/usr/bin/php
<?php
date_default_timezone_set("UTC"); // REQUIRED for DATE() to work.

require_once('proxy.test.class.php');
$OPTS = getopt('',array(
	'url:',
	'testcl:',
));
//$URL = (@$OPTS['url'])?:'http://monterey.craigslist.org/';
$URL  = (@$OPTS['url']);
$DB   = new ProxySQL();
$PROX = new ProxyTest();
$DOM  = new DOMDocument;
$DATE = date("Y-m-d H:i:s", time());

$proxies = $DB->get_untested_proxies();
//printf("PROXIES: %s\n",print_r($proxies,true));

// setup the CURL testing
$cH = curl_init();
curl_setopt($cH, CURLOPT_FRESH_CONNECT, true);
curl_setopt($cH, CURLOPT_MAXREDIRS, 2);
curl_setopt($cH, CURLOPT_TIMEOUT, 5);
curl_setopt($cH, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
curl_setopt($cH, CURLOPT_RETURNTRANSFER, true);

$CurrIP = get_current_ip($cH);

// loop through the proxies
foreach($proxies as $prox){
	$PROXHOST = $prox['host'];
	$PROXPORT = $prox['port'];
	
	// set parameters
	$prox['tested_on'] = $DATE;

	// set proxy flags	
	curl_setopt($cH, CURLOPT_PROXY, $PROXHOST);
	curl_setopt($cH, CURLOPT_PROXYPORT, $PROXPORT);
	//curl_setopt($cH, CURLOPT_PROXYTYPE, 7);  // 7 = CURLPROXY_SOCKS5_HOSTNAME

	// Test the Proxy against safe site:
	curl_setopt($cH, CURLOPT_URL,'http://www.daviddemartini.com/proxtest.php');
	$profile = test_proxy();
	if($IP = test_for_clean()){
		printf("UP: %s:%d\n",$PROXHOST,$PROXPORT);
		$prox['status'] = 'up';

		// do the IPs match?
		if($IP === $CurrIP){
			$prox['anon'] = 'up';
		}

		// test proxy for CL safe use
		curl_setopt($cH, CURLOPT_URL,'http://monterey.craigslist.org');
		if(test_cl_safe()){
			printf("CL SAFE: %s:%d\n",$PROXHOST,$PROXPORT);
			$prox['cl_safe'] = 'yes';
		}
		else {
			$prox['cl_safe']   = 'no';
		}
		
		$DB->update_proxy($prox);
	}
	else {
		printf("DUMP: %s:%d\n",$PROXHOST,$PROXPORT);
		$prox['status']  = 'down';
		$prox['cl_safe'] = 'no';
		$prox['anon']    = 'no';
		$DB->update_proxy($prox);
		continue;	
	}
/*
	// see if there is a CL test

	// make proxy insert
	$html = curl_exec($cH);
	@$DOM->loadHTML(($html)?:'<html><body></body></html>');
	$TITLE = get_title($DOM);
	if(preg_match('#^IP:(.*)$#i',$TITLE,$m)){
		

	}

	printf("%s:%d => %s\n",$PROXHOST,$PROXPORT,get_title($DOM));
*/
}

function test_proxy(){
	global $cH;
	global $DOM;
	$prox = array();
	$html = '';
	// set the clean site URL
	curl_setopt($cH, CURLOPT_URL,'http://www.daviddemartini.com/proxtest.php');
	
	// get the data
	if(!$html = curl_exec($cH)){
		$prox['protocol'] = 'http';
		// test for SOCKS5 
		curl_setopt($cH, CURLOPT_PROXYTYPE, 7);  // 7 = CURLPROXY_SOCKS5_HOSTNAME
		if($html = curl_exec($cH)){
			$prox['protocol'] = 'socss5';
		}
	}
	// 
	@$DOM->loadHTML(($html)?:'<html><body></body></html>');
	$TITLE = get_title($DOM);
	if(preg_match('#^IP:(.*)$#i',$TITLE,$m)){
		$prox['IP'] = trim($m[1]);
	}
	return $prox;
}

function test_cl_safe(){
	global $cH;
	global $DOM;
	$html = '';
	// get the data
	if(!$html = curl_exec($cH)){
		curl_setopt($cH, CURLOPT_PROXYTYPE, 7);  // 7 = CURLPROXY_SOCKS5_HOSTNAME
		$html = curl_exec($cH);
	}
	@$DOM->loadHTML(($html)?:'<html><body></body></html>');
	$TITLE = get_title($DOM);
	if(preg_match('#craigslist:#i',$TITLE,$m)){
		return true;	
	}
	// 
	return false;
}

//  function
function get_title($DOM){
	$TITLE = '';
	// Test the title
	$list = $DOM->getElementsByTagName("title");
	if ($list->length > 0) {
        	$TITLE = trim($list->item(0)->textContent);
	}
	return $TITLE;
}

function get_current_ip(){
	global $cH;
	global $DOM;
	// determine our IP
	curl_setopt($cH, CURLOPT_URL,'http://www.daviddemartini.com/proxtest.php');
	// get the data
	$html = curl_exec($cH);
	@$DOM->loadHTML(($html)?:'<html><body></body></html>');
	$TITLE = get_title($DOM);
	if(preg_match('#^IP:(.*)$#i',$TITLE,$m)){
		return trim($m[1]);		
	}
	return '';
}

?>
