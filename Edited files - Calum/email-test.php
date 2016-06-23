<?php


phpinfo();
exit;

error_reporting(E_ALL);
require 'email/PHPMailerAutoload.php';
require 'scripts/functions/email-template.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;    
$mail->isSMTP();         
$mail->Host = 'git.igp.noel.me.uk'; // Specify main and backup SMTP servers
$mail->SMTPAuth = false; // Enable SMTP authentication

$mail->From = 'noreply@noel.me.uk';
$mail->FromName = 'Study With Me';

// Add a recipient
$mail->addAddress('nh_2005@hotmail.co.uk'); 
$mail->addAddress('40092121@live.napier.ac.uk'); 
//$mail->addAddress('noel.antoine05@gmail.com'); 

$mail->addReplyTo('noreply@noel.me.uk', 'Study With Me');

$mail->isHTML(true);                                

$mail->Subject = 'Activate your account';
$hashcode = bin2hex(openssl_random_pseudo_bytes(16, $cstrong));

$body = "<p>Thank you for signing up with Study With Me. Before you get started you will need to confirm your email. To do this click the link below.</p>";
$body .= '<p><a href="https://web.igp.noel.me.uk/scripts/activate/'.$hashcode.'">Activate My Account</a></p>';
$body .= '<p>All the best<br><br>Study With Me Team</p>';


$mail->Body    = generateEmailHTML("Activate your account","Activate your account",$body,'en');


if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}


?>