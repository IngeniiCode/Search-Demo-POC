<?php

$key = 'abc123DEF';

$md5 = md5($key);

$base1 = base64_encode(md5($key,true));
$base2 = str_replace('=','',base64_encode(md5($key)));


printf("md5: %s\n",$md5);
printf("base64-1: %s\n",$base1);
printf("base64-2: %s\n",$base2);
printf("base64-2: len: %d\n",strlen($base2));

$to = 'ddemartini@ingeniigroup.com,david.demartini@gmail.com';
$subject = 'Welcome to Outspoken Ninja -- Account Verification Required';
$message = 'Please verify your account';
$headers = 'From: media@outspokenninja.com' . "\r\n" .
    'X-Mailer: NinjaMailer' . phpversion();

mail ($to,$subject,$message,$headers);

?>
