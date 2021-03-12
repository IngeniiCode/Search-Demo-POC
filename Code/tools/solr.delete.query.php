<?php

//http://www.outspokenninja.com:15450/solr/adc/select?q=desc_short%3A%22Similar%22+&wt=json&indent=true

$id = $argv[1];  // stone age henge app - apple store

$template = 'http://www.outspokenninja.com:15450/solr/adc/update?stream.body=<delete><query>desc_short%3A%22Similar%22</query></delete>&commit=true';

$url = sprintf($template,$solrBase,$id);

printf('URL: %s %s',$url,PHP_EOL);

?>
