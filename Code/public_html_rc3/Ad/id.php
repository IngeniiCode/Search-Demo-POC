<?php

require_once($_SERVER['NLIBS']."/page.php");
require_once($_SERVER['NLIBS'].'/MySQL/search.class.php');

$url = '/index.php';

if($id = @$_GET['id']){
	$uid = (@$_COOKIE['x-auth'])?:'';
        $SQL = new SearchSQL(array('x_a'=>$uid));
	if($item = @$SQL->getAd($id)){
		$itemId = $item['itemId'];
		$url    = $item['url'];
		$data   = @$_COOKIE['x-ad'];
		$SQL->clicked($itemId,$data);
	}
}

// Check to see if the page was a local reference 
// or it was an external link.  

if(preg_match('#outspokenninja\.com#i',@$_SERVER['HTTP_REFERER'])){
	header("Location: ${url}"); /* Redirect browser */
	exit();
}
else {
	$AD = file_get_contents($url);
	$BODY = "<h3>Loading Ad:  ${url}</h3> ${AD} <script> window.location.assign('${url}'); </script>";
	$PAGE->css('/css/jquery-ui.min.css');
	$PAGE->main_body($BODY);
	$PAGE->enable_search();
	$PAGE->render();
}
?>
