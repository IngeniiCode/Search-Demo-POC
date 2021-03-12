<?

require_once('../MySQL/search.class.php');
require_once('../Core/utils.lib.php');

$SQL = new SearchSQL();

// get all the current records

foreach($SQL->get_all('SELECT * from  searchSaved_Old') as $rec){
	printf("REC: %s\n",print_r($rec,true));
	$name  = $rec['name'];
	$srch  = $rec['searchId'];
	$id    = $rec['id'];
	$skey  = id_gen($id.$name);
	$sql   = sprintf('REPLACE INTO searchSaved VALUES("%s","%s","%s","%s",NOW())',
                                addslashes($skey),
                                addslashes($srch),
                                addslashes($id),
                                addslashes($name)
	);
	printf("SQL: %s\n",$sql);
	$SQL->run($sql);
}

?>
