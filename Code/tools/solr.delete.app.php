<?php

require_once('libs/Solr/json.class.php');
$JSON = new solrJSON();

$id = $argv[1];  // stone age henge app - apple store

printf("DELETE [%s]\n",$id);

$JSON->delete_id($id);
$JSON->commit();

?>
