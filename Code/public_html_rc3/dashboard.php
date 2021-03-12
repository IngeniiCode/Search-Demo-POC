<?php

require_once($_SERVER['NLIBS']."/page.php");
require_once($_SERVER['NLIBS']."/Frameworks/dashboard.class.php");

$PAGE->css('/css/dashboard.css');
$PAGE->jsfile('https://www.google.com/jsapi');
$PAGE->jsfile('/js/dashboard.js');
$PAGE->main_body($DASH->show());

$PAGE->render();
?>
