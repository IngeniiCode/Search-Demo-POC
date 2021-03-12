#!/usr/bin/php
<?php

$infile = $argv[1];
printf("PROCESSING: %s\n",$infile);
$rawfile = file_get_contents($infile);

// minify
$rxs = array(
	'#[\s\t\n]+#'
);
$str = preg_replace($rxs,' ',$rawfile);

// write the minified file
file_put_contents($infile,$str);

print "DONE\n";

?>

