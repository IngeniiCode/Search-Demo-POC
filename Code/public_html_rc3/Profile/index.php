<?php

require_once($_SERVER['NLIBS']."/page.php");
require_once($_SERVER['NLIBS']."/Profile/user.class.php");

$PRO    = new Profile();  // declare the profile class
$prof   = $PRO->get();    // 

$Dta    = print_r($prof,true);  // convert to a formatted string of the data

$user   = (@$prof['uname']);
$date   = (@$prof['added']);
$status = (@$prof['status']);

$profile = <<<PROFILE
  <h3>User Profile</h3>
  <p><b>E-mail Address:</b> ${user}</p>
  <p><b>Signup Date:</b> ${date}</p>
  <p><b>Status:</b> ${status}</p>
  <p><b>Saved Searches:</b><div id="savedSearches"></div></p>
<script>
loadSavedSearches('savedSearches');
</script>
PROFILE;

$PAGE->main_body($profile);
$PAGE->css('/css/profile.css');
$PAGE->jsfile('/js/profile.js');
$PAGE->render();

?>
