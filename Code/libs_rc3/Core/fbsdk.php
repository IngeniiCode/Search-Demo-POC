<?php

define('FACEBOOK_SDK_V4_SRC_DIR', $_SERVER['NLIBS'].'/fb.sdk.4/src/Facebook/');
require_once($_SERVER['NLIBS'].'/fb.sdk.4/autoload.php');
require_once(FACEBOOK_SDK_V4_SRC_DIR.'FacebookSession.php');

use Facebook\FacebookSession;

FacebookSession::setDefaultApplication('1520439241504815','9d91febd1d8f24c727facf0ad3453e0d');

$FB_SESS = new FacebookSession('access-token');



?>
