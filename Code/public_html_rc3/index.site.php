<?php

require_once($_SERVER['NLIBS']."/page.php");

$PAGE->css('/css/jquery-ui.min.css');
$PAGE->enable_search();
$PAGE->jsfile('js/analytics.search.js');
$PAGE->jsfile('/js/doormat.js');
$PAGE->render();

?>
