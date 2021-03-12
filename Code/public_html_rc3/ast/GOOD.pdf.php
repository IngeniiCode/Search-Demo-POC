<?php

/*
   Include TC PDF configurator 
*/
require_once('pdf.cnf.php');

// Incluce Ninja Data //
$REPORT = json_decode(@$_POST['MMHD'],true);
$ReportTitle = sprintf('Outspoken Ninja Report - %s',(@$REPORT['info']['name'])?:'Web Search');

$TEMP = array();
if(isset($REPORT['ads'])){
	foreach($REPORT['ads'] as $id => $ad){
		$region = @$ad['regionkey'];
		$state = $REPORT['regions'][$region]['state'];
		$name  = $REPORT['regions'][$region]['name'];
		$TEMP[$state][$name][$id] = $ad;
	}
}
/*	
$HTML .= '<pre>'.print_r($REPORT['ads'],true).'</pre>';
$HTML .= '<pre>'.print_r($TEMP,true).'</pre>';
*/

// Create List
$HTML .= '<table cellspacing="0" cellpadding="2" border="1" BORDERCOLOR="#BBB">';
foreach($TEMP as $state => $regions){
	$HTML .= '<tr><th colspan="2" style="background-color:#EEE; font-size: 200%; text-align: left;">'.$state.'</th></tr>';
	foreach($regions as $region => $items){
		$HTML .= '<tr><td width="40"></td><th style="background-color:#FFF; font-size: 150%; text-align: left;">'.$region.'</th></tr>';
		foreach($items as $item){
			$HTML .= '<tr><td></td><td style="font-size: 100%;"><a href="'.$item['url'].'">'.$item['title'].'</td></tr>';
		}
	}
}
$HTML .= '</table>';


echo $HTML;

//============================================================+
// END OF FILE
//============================================================+

?>
