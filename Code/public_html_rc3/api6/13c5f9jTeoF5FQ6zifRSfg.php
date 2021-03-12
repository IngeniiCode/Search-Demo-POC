<?php

// load the search crufter
require_once($_SERVER['NLIBS'].'/Core/utils.lib.php');
require_once($_SERVER['NLIBS'].'/Search/cl.setup.php');  
require_once($_SERVER['NLIBS'].'/MySQL/search.class.php');

$CLS     = new CraigsList();
$SQL     = new SearchSQL();

//$PROXHOST = '52.91.190.187';
//$PROXPORT = 55002;
//$PROXHOST = '198.169.246.30';
//$PROXPORT = 80;
//$PROXHOST = '52.30.117.47';
//$PROXPORT = 8080;
//$PROXHOST = '163.177.79.5';
//$PROXPORT = 8106;
$PROXHOST = '165.139.179.225';
$PROXPORT = 8080;

// decode the cruft
parse_str($_SERVER['QUERY_STRING'],$DATA);
$PARMS   = json_decode($DATA['p'],true);
$clquery = $CLS->queryObj($PARMS['payload']); 
$mode    = (@$PARMS['payload']['mode'])?:'sss';   // sss is a default seach mode
$region  = @$PARMS['cl_region_key'];
$exclude = @$PARMS['payload']['exclude_terms'];
$added   = 0;
$local   = sprintf('http://%s.craigslist.org',$region);

$CLUrl = sprintf('http://%s.craigslist.org/search/%s?%s',$region,$mode,$clquery);

/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-type: text/javascript; charset=utf-8');
/*
  G R A B   R E M O T E   P A G E
*/
$cH = curl_init();
curl_setopt($cH, CURLOPT_URL, $CLUrl);
curl_setopt($cH, CURLOPT_FRESH_CONNECT, true);
curl_setopt($cH, CURLOPT_MAXREDIRS, 16);
curl_setopt($cH, CURLOPT_TIMEOUT, 90);
//curl_setopt($cH, CURLOPT_PROXY, $PROXHOST);
//curl_setopt($cH, CURLOPT_PROXYPORT, $PROXPORT);
//curl_setopt($cH, CURLOPT_PROXYTYPE, 7);  // 7 = CURLPROXY_SOCKS5_HOSTNAME
//curl_setopt($cH, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)');
curl_setopt($cH, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
curl_setopt($cH, CURLOPT_RETURNTRANSFER, true);
// Exec
//printf('console.log("api6 %s");',$CLUrl);
$html = curl_exec($cH);
//printf("console.log('mode: %s');console.log('ORIGIN: %s');\n",$mode,$_SERVER['HTTP_ORIGIN']);

$seentitle = array(''=>'destroy');

// Parse the data
$DOM = new DOMDocument;
@$DOM->loadHTML(($html)?:'<html><body></body></html>');

$title = '';
foreach($DOM->getElementsByTagName('p') as $lNode) {
	$title .= $lNode->nodeValue;
}
// Check to see if we're being blocked
if(preg_match('#automatically blocked#i',$title)){
	printf('blocked_region("%s");%s',$region,PHP_EOL);
	exit;
	//printf('console.log("%s -- %s");',$CLUrl,str_replace("\n",' ',$title));
}

/* 
   C O R E   L I S T I N G   P A R S E   F U N C T I O N S 
*/
	// find all of the links
	foreach($DOM->getElementsByTagName('p') as $lNode) {
		$href          = '';
		$price         = '';
		$price_actual  = 0;
		$title         = '';
		$target_div_id = $region;
		$ad_style      = 'other';
		$item_id       = '';	
		$title_id      = '';
		if($lNode->getAttribute('class') == 'row'){
			// This is an entry!
			foreach($lNode->getElementsByTagName('a') as $link){
				if($link->getAttribute('class') == 'i'){ 
					$price_actual = preg_replace('/[\$\,]/','',$link->nodeValue);
					$price        = ($price_actual)?'$'.$price_actual:"<span class='noprice'>(no price)</span>";
				}
				if($link->getAttribute('class') == 'hdrlnk'){ 
					$href     = trim(mk_link($link->getAttribute('href')));
					$title    = trim(($link->nodeValue)?:'title');
					$item_id  = id_gen($href);
					$title_id = id_gen(strtolower($title.$price));
				}
			}
		}

		// check to see if here is a title filter, and if so make sure the title does not conflict with it.
		if($exclude){
			$regex = '#('.preg_replace('#[,\s;.]+#','|',trim($exclude)).')#i';
			if(preg_match($regex,$title)){
				// mark as seen so it's not displayed
				$seentitle[$title_id];
				continue;
			}
		}
	
		//parse the response for the region it belongs to.
	
		if(preg_match('#^https?://(.*).craigslist.org/[a-z]+/([a-z]+)/.*#i',$href,$m)){
			$target_div_id = $m[1];
			$ad_style      = set_style($m[2]);
		}
		elseif(preg_match('#^https?://(.*).craigslist.org/([a-z]+)/.*#i',$href,$m)) {
			$target_div_id = $m[1];
			$ad_style      = set_style($m[2]);
		}
		elseif(preg_match('#^https?://(.*).craigslist.org/.*#i',$href,$m)) {
			$target_div_id = $m[1];
		}
	
		// if this search result belongs to a different region -- DUMP IT!
		if($target_div_id == $region){
			$talt  = $ad_style.' sale';
			if(!$seentitle[$title_id]){
				$added++;
				// write the add to the database
				$SQL->saveItem($item_id,$href);
				printf('report_item("%s","%s","%s","%d","%s - %s","%s");%s',$item_id,$target_div_id,$ad_style,$price_actual,addslashes($title),addslashes($price),addslashes($href),PHP_EOL);
				$seentitle[$title_id] = true;
			}
		}
	}

	if(!$added){
		// there is nothing for this zone.. go ahead and delete it.
		printf('drop_region("%s");%s',$region,PHP_EOL);
	}

/*   //  E  N  D   // */

function set_style($style=''){
	switch($style){
		case 'mcy':
		case 'cto':
		case 'boa':
			return 'owner';
		case 'mcd':
		case 'ctd':
		case 'bod':
			return 'dealer';
		default:
	}
	return 'other';
}

function mk_link($link=''){
	global $local;
	if(preg_match('#^http#',$link)){
		return $link;
	}
	return sprintf('%s%s',$local,$link);
}

function sfix($str){
	return str_replace('"','',trim($str));
}


?>

