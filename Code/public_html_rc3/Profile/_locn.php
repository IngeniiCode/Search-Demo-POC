<?php
/*
   L O G I N   C A N C E L E D 
*/

// load MySQL connector to check login
require_once($_SERVER['NLIBS'].'/k0re.php');
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');

$DATA   = array_merge($_POST,array('Login Cancelled by User'));   // use ONLY posted data 

/* create user profiles connector */
$SQL = new ProfileSQL($DATA);
$SQL->log_login_attempt();
$SQL->log_access();

/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-type: text/javascript; charset=utf-8');

?>
console.log('LOGIN CANCELLED');

