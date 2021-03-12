<?php

/*
  This is the Core (k0re) include file

  Placing the core files in a single file GREATLY streamlines the
  process of requiring all the bits we need to make this thing fly
*/

/*
  Set the timezone
*/
date_default_timezone_set('UTC');

//  Library Base Path -- relative to all else
$LIBS = $_SERVER['NLIBS'];

/*
   REQUIRED INCLUDED
*/

// Main Components
require_once($LIBS.'/Core/utils.lib.php');

//  Classes
require_once($LIBS.'/MySQL/main.class.php');

?>
