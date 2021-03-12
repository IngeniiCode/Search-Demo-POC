<?php
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');

/* create user profiles connector */
$SQL    = new ProfileSQL($DATA);
$SQL->log_login_attempt();
$SQL->log_access();  // log the fact they loaded a login attempt

?>
<h2>Sign-up is Easy and Free!</h2>
<hr>
<h4>Sign-up for Basic Membership - No cost, no obligation.</h4><div id="s_losu" class="login_status"></div>
<form id="f_losu" name="f_losu">
<table class="logins">
  <tr>
    <th>E-Mail Address:</th>
    <td><input type="text" name="new_user" size="24" class="input_text" tabindex="20"> <span id="losu_user"></span></td>
  </tr>
  <tr>
    <th>Password:</th>
    <td><input type="password" name="pwd" size="24" class="input_text" tabindex="22"> <span id="losu_pwd"></span></td> 
  </tr>
  <tr>
    <th>Confirm Password:</th>
    <td><input type="password" name="pwdconf" size="24" class="input_text"  tabindex="23"> <span id="losu_pwdconf"></span></td> 
  </tr>
</table>
<div class="buttons">
<button type="button" name="signup_std" onClick="signupMember('f_losu','s_losu');" tabindex="13">Register Me!</button>
<button type="button" name="cancel" onClick="loginCancel();" tabindex="99">Cancel</button>
</div>
<hr>
<div id="termsblock">
  <a href="/Info/terms.php" target="policy"> Terms of Use </a>
  <a href="/Info/privacy.php" target="policy"> Privacy Policy </a>
</div>

