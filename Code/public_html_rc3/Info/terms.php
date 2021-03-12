<?php

require_once($_SERVER['NLIBS']."/page.php");

//$PAGE->add_image_slider();
$PAGE->css('/jsImgSlider/themes/0/js-image-slider.css');
$PAGE->jsfile('/jsImgSlider/themes/0/js-image-slider.js');
$PAGE->render();

?>
