#!/usr/bin/php
<?

require_once('../MySQL/search.class.php');
require_once('../Core/utils.lib.php');

$SQL = new SearchSQL();

/* userAgent */
foreach($SQL->get_all('SELECT DISTINCT agentId from userAgent') as $rec){
	$update   = 0;
	$agentId = fix_string($rec['agentId']);
	$update  += ($agentId != $rec['agentId'])?1:0;
	if($update){
		$sql = sprintf('UPDATE userAgent SET agentId="%s" WHERE agentId="%s"',$agentId,$rec['agentId']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/* searchSaved */
foreach($SQL->get_all('SELECT DISTINCT srchKey,searchId,id from searchSaved') as $rec){
	$update   = 0;
	$oldId    = $rec['id'];
	$newID    = fix_string($oldId);
	$srchKey  = fix_string($rec['srchKey']);
	$searchId = fix_string($rec['searchId']);
	$update  += ($newID != $oldId)?1:0;
	$update  += ($srchKey != $rec['srchKey'])?1:0;
	$update  += ($searchId != $rec['searchId'])?1:0;
	if($update){
		$sql = sprintf('UPDATE searchSaved SET id="%s",srchKey="%s",searchId="%s" WHERE id="%s" AND srchKey="%s" AND searchId="%s"',$newID,$srchKey,$searchId,$oldId,$rec['srchKey'],$rec['searchId']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/* searchRequest */
foreach($SQL->get_all('SELECT DISTINCT searchId from searchRequest') as $rec){
	$update   = 0;
	$searchId = fix_string($rec['searchId']);
	$update  += ($searchId != $rec['searchId'])?1:0;
	if($update){
		$sql = sprintf('UPDATE searchRequest SET searchId="%s" WHERE searchId="%s"',$searchId,$rec['searchId']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/* searchHistory */
foreach($SQL->get_all('SELECT DISTINCT searchId,id from searchHistory') as $rec){
	$update   = 0;
	$oldId    = $rec['id'];
	$newID    = fix_string($oldId);
	$searchId = fix_string($rec['searchId']);
	$update  += ($newID != $oldId)?1:0;
	$update  += ($searchId != $rec['searchId'])?1:0;
	if($update){
		$sql = sprintf('UPDATE searchHistory SET id="%s",searchId="%s" WHERE id="%s" AND searchId="%s"',$newID,$searchId,$oldId,$rec['searchId']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/* searchDownloads */
foreach($SQL->get_all('SELECT DISTINCT searchId,id from searchDownloads') as $rec){
	$update   = 0;
	$oldId    = $rec['id'];
	$newID    = fix_string($oldId);
	$searchId = fix_string($rec['searchId']);
	$update  += ($newID != $oldId)?1:0;
	$update  += ($searchId != $rec['searchId'])?1:0;
	if($update){
		$sql = sprintf('UPDATE searchDownloads SET id="%s",searchId="%s" WHERE id="%s" AND searchId="%s"',$newID,$searchId,$oldId,$rec['searchId']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}


/*  searchDefaults */
foreach($SQL->get_all('SELECT searchId FROM searchDefaults') as $rec){
	$oldId = $rec['searchId'];
	$newID = fix_string($oldId);
	if($oldId != $newID){
		$sql = sprintf('UPDATE searchDefaults SET searchId="%s" WHERE searchId="%s"',$newID,$oldId);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/*  refererSite */
foreach($SQL->get_all('SELECT refID FROM refererSite') as $rec){
	$oldId = $rec['refID'];
	$newID = fix_string($oldId);
	if($oldId != $newID){
		$sql = sprintf('UPDATE refererSite SET refID="%s" WHERE refID="%s"',$newID,$oldId);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/*  profileVerify */
foreach($SQL->get_all('SELECT id FROM profileVerify') as $rec){
	$oldId = $rec['id'];
	$newID = fix_string($oldId);
	if($oldId != $newID){
		$sql = sprintf('UPDATE profileVerify SET id="%s" WHERE id="%s"',$newID,$oldId);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/*  profileType */
foreach($SQL->get_all('SELECT id FROM profileType') as $rec){
	$oldId = $rec['id'];
	$newID = fix_string($oldId);
	if($oldId != $newID){
		$sql = sprintf('UPDATE profileType SET id="%s" WHERE id="%s"',$newID,$oldId);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/*  profile */
foreach($SQL->get_all('SELECT id FROM profile') as $rec){
	$oldId = $rec['id'];
	$newID = fix_string($oldId);
	if($oldId != $newID){
		$sql = sprintf('UPDATE profile SET id="%s" WHERE id="%s"',$newID,$oldId);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}



/* logins */
foreach($SQL->get_all('SELECT id from logins') as $rec){
	$update  = 0;
	$oldId   = $rec['id'];
	$newId   = fix_string($oldId);
	$update += ($oldId != $newId)?1:0;
	if($update){
		$sql = sprintf('UPDATE IGNORE logins SET id="%s" WHERE id="%s"',$newId,$oldId);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/* adsFound */
foreach($SQL->get_all('SELECT itemId from adsFound') as $rec){
	$update  = 0;
	$itemId  = fix_string($rec['itemId']);
	$update += ($itemId != $rec['itemId'])?1:0;
	if($update){
		$sql = sprintf('UPDATE adsFound SET itemId="%s" WHERE itemId="%s"',$itemId,$rec['itemId']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/* accessLog */
foreach($SQL->get_all('SELECT DISTINCT id,refID,agentID from accessLog') as $rec){
	$update  = 0;
	$oldId   = $rec['id'];
	$newID   = fix_string($oldId);
	$refID   = fix_string($rec['refID']);
	$agentID = fix_string($rec['agentID']);
	$update += ($newID != $oldId)?1:0;
	$update += ($refID != $rec['refID'])?1:0;
	$update += ($agentID != $rec['agentID'])?1:0;
	if($update){
		$sql = sprintf('UPDATE accessLog SET id="%s",refID="%s",agentID="%s" WHERE id="%s" AND refID="%s" AND agentID="%s"',$newID,$refID,$agentID,$oldId,$rec['refID'],$rec['agentID']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

/* adsClicked */
foreach($SQL->get_all('SELECT DISTINCT id,itemId from adsClicked') as $rec){
	$update  = 0;
	$oldId   = $rec['id'];
	$newID   = fix_string($oldId);
	$itemId  = fix_string($rec['itemId']);
	$update += ($newID != $oldId)?1:0;
	$update += ($itemId != $rec['itemId'])?1:0;
	if($update){
		$sql = sprintf('UPDATE adsClicked SET id="%s",itemId="%s" WHERE id="%s" AND itemId="%s"',$newID,$itemId,$oldId,$rec['itemId']);
		printf("SQL: %s\n",$sql);
		$SQL->run($sql);
	}
}

function fix_string($str){
	$pats = array('/','+','.');
	$subs = array('_','-','_');
	return str_replace($pats,$subs,$str);
}

/* cleanups */
$SQL->run('DROP TABLE IF EXISTS `item`');
$SQL->run('DROP TABLE IF EXISTS `itemMFG`');
$SQL->run('DROP TABLE IF EXISTS `survey`');
$SQL->run('DROP TABLE IF EXISTS `surveyAnswers`');
$SQL->run('DROP TABLE IF EXISTS `surveyIntro`');
$SQL->run('DROP TABLE IF EXISTS `surveyPage`');
$SQL->run('DROP TABLE IF EXISTS `surveyProduct`');
$SQL->run('DROP TABLE IF EXISTS `surveyQuestion`');
$SQL->run('DROP TABLE IF EXISTS `surveyResponse`');
$SQL->run('DROP TABLE IF EXISTS `surveyResponseDetails`');
$SQL->run('DROP TABLE IF EXISTS `surveySummary`');

?>
