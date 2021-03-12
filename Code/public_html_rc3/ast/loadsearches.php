<?php
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');
require_once($_SERVER['NLIBS'].'/MySQL/search.class.php');

$x_auth = @$_COOKIE['x-auth'];

$PROF = new ProfileSQL();
$SRCH = new SearchSQL(array('x_a'=>$x_auth));

// Check to see if they are logged in, and if so if they have a saved search
if($user = $PROF->user_lookup($x_auth)){
	// select all of the user's saved searches
	$SEARCHES = '';
	if($found = $SRCH->get_user_searches()){
		$SEARCHES = '<ul class="saved_searches">';
		foreach($found as $srch){
			// decode search parameters
			$params = json_decode($srch['search'],true);
			$onClick = sprintf('onClick="return loadSavedSearch(\'%s\',\'%s\');"',$srch['searchId'],$params['type']);
			$SEARCHES .= sprintf('<li>%s: <a href="#" %s>%s</a></li>',ucfirst($params['type']),$onClick,$srch['name']);
		}
		$SEARCHES .= '</ul>';
	}
	else {
		$SEARCHES = '<em>No saved searches found</em>';
	}
	
	$CONTENT = $SEARCHES;
}
else {
	/* Define the Guest message */
	$CONTENT = '<p>Saving your Searches is a powerful feature, and available to all Registered Members.</p><p><a href="/Membership" target="_members"><b>Sign-up Now for Free!</b></a></p>';
} 

?>
<?= $CONTENT ?>
