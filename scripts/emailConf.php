<?php

require '../email/PHPMailerAutoload.php';
		
function sendEmail($to,$subject,$body)
{
	$mail = new PHPMailer;
	$return = false;
	#$mail->SMTPDebug = 3;
	$mail->isSMTP();
	$mail->Host = 'smtp.163.com'; // Specify main and backup SMTP servers
	$mail->SMTPAuth = true; // Enable SMTP authentication
	$mail->SMTPSecure = 'tls';
	$mail->Username = 'journey001@163.com';
	$mail->Password = '123456hou';
	$mail->From = 'journey001@163.com';
	$mail->FromName = 'Study With Me';
	
	// Add new user recipient
    $mail->addAddress($to); 

	// Copy Email
	$mail->addAddress('89942437@qq.com'); 

	$mail->addReplyTo('journey001@163.com', 'Study With Me');
	$mail->isHTML(true);                                
	$mail->Subject = $subject;

	/* wrap body with email template */
	$mail->Body    = $body;
	if($mail->send()) { $return = true;}

	return $return;
}

?>

