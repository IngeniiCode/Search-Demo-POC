<?php
require_once($_SERVER['NLIBS'].'/Frameworks/form.class.php');

$x_auth = @$_COOKIE['x-auth'];

$FORM = new Form(array('x_a'=>$x_auth,'searchId'=>$DATA['ft'],'div_status'=>'searchStatus','div_results'=>'searchResults'));
$CONTENT = $FORM->mbr_terms(array('ut'=>'basic')); 

?>
<?= $CONTENT ?>
