<?php

require_once('../../libs/Core/mail.class.php');

$MAIL = new NinjaMailer();

$subject = 'Outspoken Ninja -- Account Verification';
$text    = $subject;
$html    = '<h2>'.$subject.'</h2>Thank you for joining The Outspoken Ninja!';

$html   .= '<p>Follow the link below to complete verification of your acount.</p>';

$verifylink = 'https://www.outspoken.ninja/Profile/vfx.php?9399jr9092099390j0j00j2';
$html   .= '<a href="'.$verifylink.'">VERIFY NOW</a>';

$html   .= '<hr><p style="font-size: 90%;">If you are having trouble following the link above, copy and past this link into your browser:  <u>'.$verifylink.'</u></p>';

$MAIL->send('ddemartini@ingeniigroup.com',$subject,$text,$html);

?>

