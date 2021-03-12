<?php
/*
   L O G I N   F O R   F A C E B O O K  
*/

require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');

$DATA   = $_POST;   // use ONLY posted data

/* create user profiles connector */
$SQL    = new ProfileSQL($DATA);
$SQL->log_login_attempt();
$SQL->log_access();

?>
