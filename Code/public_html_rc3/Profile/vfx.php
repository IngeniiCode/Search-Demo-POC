<?php

require_once($_SERVER['NLIBS']."/page.php");
require_once($_SERVER['NLIBS']."/Profile/user.class.php");

if($codes = @$_GET['c']){
	$PROF = new Profile();
	// chop off the first 4 chars and keep only the next 22
	$PROF->verify_code($codes);
}

$PAGE->main_body('<h2>Thank you.  Your Account is now active.</h2><p>Login now to start using your member benefits.</p>');
$PAGE->css('/css/profile.css');
$PAGE->render();

?>
