<?php
/*
   L O G I N   F O R   S I G N - U P 
*/

// load MySQL connector to check login
require_once($_SERVER['NLIBS'].'/k0re.php');
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');
require_once($_SERVER['NLIBS'].'/Core/mail.class.php');

/*
   P E R F O R M   T H E   W O R K
*/
$DATA   = $_POST;   // use ONLY posted data 
$status = 'Testing';
$closer = '';

/* create user profiles connector */
$SQL = new ProfileSQL($DATA);
$SQL->log_login_attempt();
$SQL->log_access();

/* create mail handler */
$MAIL = new NinjaMailer();

/* check for account conflict -- make sure username is unique */
if(!$SQL->user_already_registered()){
	// register the user
	if($SQL->create_new_user()){
		$code   = $SQL->get('code');
		$email  = $SQL->get('uname');
		$status = "Successful Signup!<br>Don't forget to check your e-mail for the verification link.";	
		$closer = "setTimeout(function(){ loginDestroy(); }, 5000); updateMenu('loggedin'); refreshPage();";

		$verifylink = 'https://www.outspoken.ninja/Profile/vfx.php?c='.urlencode($code);
		
		// send the verification message
		$subject = 'Outspoken Ninja -- Account Verification';
		/* TEXT */
		$text    = "Thank you for joining The Outspoken Ninja!\n\n";
		$text   .= "Follow the link below to complete verification of your account.\n\n";
		$text   .= $verifylink."\n\n";
		
		/* HTML */
		$html    = '<h2>'.$subject.'</h2>Thank you for joining The Outspoken Ninja!';
		$html   .= '<p>Follow the link below to complete verification of your account.</p>';
		$html   .= '<a href="'.$verifylink.'">VERIFY NOW</a>';
		$html   .= '<hr><p style="font-size: 90%;">If you are having trouble following the link above, copy and past this link into your browser:  <u>'.$verifylink.'</u></p>';

		// Send the message 
		$MAIL->send($email,$subject,$text,$html);
	}
	else {
		$status = $SQL->get_signin_error(); 
	}
}
else {
	// user is already registered.
	$status = "User already registered.";	
}

/*
   W R I T E   O U T   J A V A S C R I P T   H E A D E R S
*/
header('Content-type: text/javascript; charset=utf-8');
?>
loginStatus("s_losu","<?= $status ?>");
<?= $closer ?>

