<?php

require_once('../g12/Libs/dom.helpers.lib.php');

// DOM init
$DOMp = new DOMDocument;
$DOMc = new DOMDocument;
$data = array();

// configure CURL
$cH = curl_init();
curl_setopt($cH, CURLOPT_FRESH_CONNECT, true);
curl_setopt($cH, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($cH, CURLOPT_MAXREDIRS, 16);
curl_setopt($cH, CURLOPT_TIMEOUT, 90);
curl_setopt($cH, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)');
curl_setopt($cH, CURLOPT_RETURNTRANSFER, true);

// get the main landing page
$startUrl = 'http://www.craigslist.org/about/sites#US';
curl_setopt($cH, CURLOPT_URL,$startUrl);
$DOMp->loadHTML(curl_exec($cH));

foreach($DOMp->getElementsByTagName('h1') as $cNode) {
	if($country = $cNode->nodeValue){
		$data[$country] = array();
		if($div = get_next_sibling_type('div',$cNode)){
			//iterate through the states
			foreach($div->getElementsByTagName('h4') as $sNode) {
				if($state = $sNode->nodeValue){
					$data[$country][$state] = array();
					if($ul = get_next_sibling_type('ul',$sNode)){
						foreach($ul->getElementsByTagName('a') as $rNode) {
							$city = ucwords($rNode->nodeValue);
							$data[$country][ucwords($state)][$city] = array(
								'url' => $rNode->getAttribute('href'),
								'geo' => get_geocode($city,$state)
							);
						}
					}
				}
			}
		}
	}
}

// Create the raw data file
$fh = fopen('cl_raw.json','w');
fwrite($fh,json_encode($data,JSON_PRETTY_PRINT));
fclose($fh);

// Create the continental files
foreach($data as $cont => $states){
	// write the full data file
	$file = sprintf('cl_%s_full.json',strtolower(str_replace(' ','_',$cont)));
	$fh = fopen($file,'w');
	fwrite($fh,json_encode($states));
	fclose($fh);
	// write the engine file
	$engineArry = array();
	foreach($states as $state => $regions){
		$engineArry[$state] = array();
		foreach($regions as $name => $data){
			$url = (@$data['url'])?:'';
			if(preg_match('#^https?://(.*).craigslist.*#i',$url,$m)){
				$key = strtolower(trim($m[1]));
				$engineArry[$state][$key]['name'] = $name;
				$engineArry[$state][$key]['geo']  = get_geocode($name,$state);
			}	
		}
	}
	$file = sprintf('cl_%s_engine.json',strtolower(str_replace(' ','_',$cont)));
	$fh = fopen($file,'w');
	fwrite($fh,json_encode($engineArry,JSON_PRETTY_PRINT));
	fclose($fh);
	printf("ENGINE: %s\n",print_r($engineArry,true));
}

// ---------------------------------------------------
//
function get_geocode($city='',$state='',$country=''){
	global $cH;
	global $DOMc;
	$tries     = 0;
	$mask      = 'http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false';
	$ecity     = urlencode(trim($city));
	$estate    = urlencode(trim($state));
	$ecountry  = urlencode(trim($country));
	//$query   = sprintf('%s%%2C+%s%%2C+%s%%2C',$ecity,$estate,$ecountry);
	$query     = sprintf('%s%%2C+%s',$ecity,$estate);
	$url       = sprintf($mask,$query);
	curl_setopt($cH, CURLOPT_URL,$url);
	do {
		if($tries++ > 3) {
			return array('lat'=>0,'lng'=>0);
			printf("FAILED: %s\n",$url);
		}
		printf("URL: %s\n",$url);
		if($resp = curl_exec($cH)){
			// if this parsed, return the geo data
			if($data = json_decode($resp,true)){
				if(isset($data['results'][0]['geometry']['location'])){
					return $data['results'][0]['geometry']['location'];
				}
			}
		}
		sleep(1);
	} while ($tries < 3);
}

?>
