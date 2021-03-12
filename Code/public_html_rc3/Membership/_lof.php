<?php
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');

/* create user profiles connector */
$SQL = new ProfileSQL($DATA);
$SQL->log_login_attempt();
$SQL->log_access();  // log the fact they loaded a login attempt

?>
<h2>Login</h2>
<hr>
<h3><div id="s_lofm" class="login_status"></div></h3>
<form id="f_lofm" name="f_lofm">
<table class="logins">
  <tr>
    <th>E-mail / Username:</th>
    <td><input type="text" name="user" size="24" class="input_text" tabindex="10"> <span id="lofm_user"></span></td>
  </tr>
  <tr>
    <th>Password:</th>
    <td><input type="password" name="pwd" size="24" class="input_text" tabindex="12"> <span id="lofm_pwd"></td> 
  </tr>
</table>
<div class="buttons">
<button type="button" name="login_std" onClick="loginMember('f_lofm','s_lofm');" tabindex="13">Login</button>
<button type="button" name="cancel" onClick="loginCancel();" tabindex="99">Cancel</button>
</div>
<!--
<hr>
Connect with Facebook: <div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false"></div>
<hr> -->
<p>Not a member yet? <a href="/Membership" target="_members"><b>Sign-up Now for Free!</b></a></p>
</form>
