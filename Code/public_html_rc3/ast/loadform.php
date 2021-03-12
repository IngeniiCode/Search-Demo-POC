<?php
require_once($_SERVER['NLIBS'].'/Search/form.class.php');

$x_auth = @$_COOKIE['x-auth'];

$FORM = new SearchForm(array('x_a'=>$x_auth,'searchId'=>$DATA['ft'],'div_status'=>'searchStatus','div_results'=>'searchResults'));
print $FORM->build();

?>
