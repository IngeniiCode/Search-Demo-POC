<?php

// load the search crufter

// decode the cruft
parse_str($_SERVER['QUERY_STRING'],$DATA);
$query  = $DATA['q'];
$terms  = json_decode($DATA['q'],true);
$type   = $terms['DATA']['type'];
$outdiv = $terms['outdiv'];
$debug  = print_r($terms,true);

// determine which search to execute
$SEARCH_JS_CODE = <<<BASE
store_query('$query');
add_store_url('sfbay');
add_store_url('salem');
add_store_url('eugene');
add_store_url('reno');
add_store_url('lasvegas');
BASE;

switch($type){
	case 'motorcycle':
		$SEARCH_JS_CODE .= "search_motorcycle('$query');";
		break;
	case 'car':
		$SEARCH_JS_CODE .= "search_car('$query');";
		break;
	case 'truck':
		$SEARCH_JS_CODE .= "search_truck('$query');";
		break;
	default:
		$SEARCH_JS_CODE .= "search_hard('$query');";
}

/*
   W R I T E   O U T   J A V A S C R I P T  
*/
header('Content-type: text/javascript; charset=utf-8');
print <<<JS
window.parent.document.getElementById("$outdiv").innerHTML = '<div id="apiout"><h6>The Ninja is Collecting your Results</h6><img src="/assets/searching.gif"></div>';
check_counter();
$SEARCH_JS_CODE   // this is no a blocking operation
imdone();
JS;

sleep(10);

?>
