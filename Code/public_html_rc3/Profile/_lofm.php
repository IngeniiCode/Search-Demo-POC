<?php
/*
   L O G I N   F O R   S I G N - U P 
*/

// load MySQL connector to check login
require_once($_SERVER['NLIBS'].'/k0re.php');
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');

/*
   P E R F O R M   T H E   W O R K
*/
$DATA   = $_POST;   // use ONLY posted data 
$status = 'Testing';
$closer = '';

/* create user profiles connector */
$SQL = new ProfileSQL($DATA);
$SQL->log_login_attempt();
$SQL->log_access();

/* check for account conflict -- make sure username is unique */
if($id = $SQL->user_credentials_correct()){
	$status = "Welcome Back!!";	
	$closer = ' setTimeout(function(){ loginDestroy(); }, 3200); updateMenu("in"); refreshPage("login");';
	$date_of_expiry = time() + (60 * 60 * 4) ;  // cookie to last 4 hours
	setcookie('x-auth',$id, $date_of_expiry, '/');  // login cookie lasts for 1 hour
}
else {
	// user is already registered.
	$status = "Unable to Login.  Check caps lock and verify username/password are correct.";
	$closer = 'refreshPage("logout");';
	setcookie('x-auth',null, -1,'/');  // NUKE THE COOKIE!!
}

/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-type: text/javascript; charset=utf-8');
printf('loginStatus("s_lofm","%s"); %s',addslashes($status),$closer);
?>
