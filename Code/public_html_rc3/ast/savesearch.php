<?php

// get form name if defined, use default if not
require_once($_SERVER['NLIBS'].'/Search/form.class.php');
if($x_auth = @$_COOKIE['x-auth']){

	$SQL = new SearchSQL(array('x_a'=>$x_auth));
	$SQL->save_last_search(@$_COOKIE['last_search'],@$_GET['n']);  // pull through submitted name if sent

	// save the search to user's list of saved searches.  Over-write exsiting / REPLACE or INSERT... 
	printf('<em>Saved</em>');

}
else {
    printf('<b>Error saving search..</b>');
}
?>
