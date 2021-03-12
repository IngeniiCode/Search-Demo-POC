<?php

// load the search crufter
require_once($_SERVER['NLIBS'].'/MySQL/search.class.php');

$itemId = @$_COOKIE['x-id'];

if($itemId){
	$data = @$_COOKIE['x-ad'];
	$SQL = new SearchSQL();
	$SQL->clicked($itemId,$data);
}

/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-type: text/javascript; charset=utf-8');

/*
   W R I T E   O U T   F I N A L   J A V A S C R I P T 
*/

?>

