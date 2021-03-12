<?
// store a cookie for the feedback

if(@$_COOKIE['x-f'] < time() + 60 * 30){
	// this looks like a legit feedback post
	require_once($_SERVER['NLIBS'].'/Core/feedback.class.php');
	$FBACK = new Feedback($_POST);
	$FText = print_r(array(
		'feedbackForm' => $_POST,
		'cookie'       => $_COOKIE,
		'agent'        => array(
					'IP_ADDR' => $_SERVER['REMOTE_ADDR'],
					'BROWSER' => $_SERVER['HTTP_USER_AGENT'],
					'REFERER' => $_SERVER['HTTP_REFERER'],
					'METHOD'  => $_SERVER['REQUEST_METHOD'],
					'QUERY'   => $_SERVER['QUERY_STRING'],
					'REQUEST' => $_SERVER['REQUEST_URI']
				)
	),true);
	$FBACK->record();
	
	// mail notification of feedback
	require_once($_SERVER['NLIBS'].'/Core/mail.class.php');
	$MAIL = new NinjaMailer();

	// send the verification message
	$subject = 'Outspoken Ninja Feedback';

	/* TEXT */
	$text    = "Feedback:\n\n";
	$text   .= $FText; 

	/* HTML */
	$html    = '<h2>User Feedback</h2>';
	$html   .= '<hr><pre>'.$FText.'</pre>';

	// Send the message
	$MAIL->send('Media@Outspoken.Ninja,ddemartini@ingeniigroup.com',$subject,$text,$html);
	
}

?>
SUCCESS
