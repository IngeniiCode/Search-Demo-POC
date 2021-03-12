
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
alert('Response: ' + JSON.stringify(response));
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } 
    else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } 
    else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1520439241504815',
    cookie     : true,  // enable cookies to allow the server to access the session
    xfbml      : true,  // parse social plugins on this page
    oauth      : true,  // turn on the Oauth stuffs
    version    : 'v2.2' // use version 2.2
    //version    : 'v2.1' // use version 2.1
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
</script>

<hr>
<h4>Login</h4><div id="s_lofm"></div>
<form id="f_lofm" name="f_lofm">
<table class="logins">
  <tr>
    <th>E-mail / Username:</th>
    <td><input type="text" name="user" size="50" class="input_text"></td>
    <td rowspan="2" valign="bottom"><button type="button" name="login_std" onClick="loginMember('f_lofm','s_lofm');">Login</button></td>
  </tr>
  <tr>
    <th>Password:</th>
    <td><input type="password" name="pwd" size="30" class="input_text"></td> 
  </tr>
</table>
</form>

<hr>
<h4>Sign-up for Free!</h4><div id="s_losu"></div>
<form id="f_losu" name="f_losu">
<table class="logins">
  <tr>
    <th>E-Mail Address:</th>
    <td><input type="text" name="new_user" size="50" class="input_text"></td>
    <td rowspan="3" valign="bottom"><button type="button" name="signup_std" onClick="signupMember('f_losu','s_losu');">Sign Up!</button></td>
  </tr>
  <tr>
    <th>Password:</th>
    <td><input type="password" name="pwd" size="30" class="input_text"></td> 
  </tr>
  <tr>
    <th>Confirm Password:</th>
    <td><input type="password" name="pwdconf" size="30" class="input_text"></td> 
  </tr>
</table>
<!--
<hr>
<h4>Login / Register using Facebook Account</h4><div id="s_lofb"></div>

<form id="f_lofb" name="f_lofb">
<fb:login-button scope="public_profile,email,user_likes" onlogin="checkLoginState();">
</fb:login-button>
</form>
-->
<div id="status"></div>
<hr>
<div style="text-align: center; width: 100%;"><button type="button" name="cancel" onClick="loginCancel();">Cancel</button></div>
