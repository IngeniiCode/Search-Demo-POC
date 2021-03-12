<?php
require_once($_SERVER['NLIBS']."/Core/utils.lib.php");
$ip  = get_client_ip();
$geo = get_location_ip($ip);
// Just for debugging
if(!@$geo['lat']){
	$geo = json_encode(array(
                        'lat'    => '36.971298',
                        'lon'    => '-121.987503',
                        'city'   => 'Santa Crux',
                        'ccode'  => 'USA',
                        'county' => 'United of States'	
	));  /* TEMPORARY !!! */
}
$geocookie = addslashes(json_encode($geo));
?>
/*  remote user ip <?= $ip ?>  */
bakeCookie("x-geo-ninja","<?= $geocookie ?>",4);
