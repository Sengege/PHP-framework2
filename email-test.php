<?php

//test
//Last Try 
//Would it works? Neinenen 
//phpinfo();
//exit;
//coucou Naianaan
error_reporting(E_ALL);
require 'email/PHPMailerAutoload.php';
require 'scripts/functions/email-template.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;    
$mail->isSMTP();         
$mail->Host = 'email-smtp.eu-west-1.amazonaws.com'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->SMTPSecure = 'tls';
$mail->Username = 'AKIAIYRWDKZ6JGCRC3PQ';
$mail->Password = 'ApF6hCq86HJp5qRzW3GSsWs/TNZVUOeTb/xCpFfQOsY8';
$mail->From = 'noreply@atux.co.uk';
$mail->FromName = 'Study With Me';

// Add a recipient
$mail->addAddress('sigsauer@hotmail.fr'); 
//$mail->addAddress('40092121@live.napier.ac.uk'); 
$mail->addAddress('noel.antoine05@gmail.com'); 

$mail->addReplyTo('noreply@atux.co.uk', 'Study With Me');

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
