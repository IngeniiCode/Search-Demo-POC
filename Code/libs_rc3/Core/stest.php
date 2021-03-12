<?php
$max = 10000000;

$t0 = microtime(true);
for($i=0;$i<$max;$i++){
	$x = "THIS IS A STRING";
}
printf("\$x = \"THIS IS A STRING\"   %s\n",(microtime(true) - $t0));

$t0 = microtime(true);
for($i=0;$i<$max;$i++){
	$x = 'THIS IS A  STRING';
}
printf("\$x = 'THIS IS A STRING'    %s\n",(microtime(true) - $t0));

$t0 = microtime(true);
for($i=0;$i<$max;$i++){
	$x = "THIS IS A STRING $i";
}
printf("\$x = \"THIS IS A STRING \$i\"   %s\n",(microtime(true) - $t0));

$t0 = microtime(true);
for($i=0;$i<$max;$i++){
	$x = "THIS IS A STRING ${i}";
}
printf("\$x = \"THIS IS A STRING \${i}\"   %s\n",(microtime(true) - $t0));

$t0 = microtime(true);
for($i=0;$i<$max;$i++){
	$x = "THIS IS A ${i} STRING";
}
printf("\$x = \"THIS IS A \${i} STRING\"    %s\n",(microtime(true) - $t0));

$t0 = microtime(true);
for($i=0;$i<$max;$i++){
	$x = 'THIS IS A STRING'.$i;
}
printf("\$x = 'THIS IS A STRING'.\$i       %s\n",(microtime(true) - $t0));

$t0 = microtime(true);
for($i=0;$i<$max;$i++){
	$x = 'THIS IS A '.$i.' STRING';
}
printf("\$x = 'THIS IS A '.\$i.' STRING'  %s\n",(microtime(true) - $t0));

?>
