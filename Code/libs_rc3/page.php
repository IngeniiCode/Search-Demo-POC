<?php

require_once('k0re.php');  // REQ BY EVERYTHING

/*
  Library handling for any HTML page processing
*/

/*
  Initialize the PHP Session
*/
session_start();   // this MUST happen before any HTML is transmitted

/*
   REQUIRED INCLUDED
*/

//  Process GET and POST data
require_once($LIBS.'/Core/submit.lib.php');

//  Classes
require_once($LIBS.'/Frameworks/page.class.php');
$PAGE = new Page();

/*
  Init some of the big fat global connectors
*/

$G_MYSQL = new MySQL();
$G_MYSQL->log_access();

?>
