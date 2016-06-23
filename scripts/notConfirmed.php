<?php 

function nc_resendActivation()
{
	global $student;
	global $db;
	global $noerrors;
		
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	if(!$student->userExists())
	{
		echo json_encode(array("result"=>"failed","message"=>"User not logged in"));
		return;
	}
	
	if($student->active)
	{
		echo json_encode(array("result"=>"failed","message"=>"User is already active"));
		return;
	}
	
	

	// JSON Message array
	$jsonArray = array();
	
	// Start transaction
	$db->beginTransaction();
	$sqlErrors = 0;
		
	// Remove Exisiting Activation Code
	$remove = $db->prepare("DELETE FROM `hash_codes` WHERE `type` = 'activation' AND `studentID` = :studentID");
	$remove->bindParam(":studentID", $student->userID);
	if(!$remove->execute()) { $sqlErrors++;}
	
	// Create New Activation Code
	$hashcode = bin2hex(openssl_random_pseudo_bytes(16, $cstrong));	
	$h = $db->prepare("INSERT INTO `hash_codes`(`type`, `code`, `studentID`) VALUES ('activation',:code,:studentID)");
	$h->bindParam(':studentID', $student->userID);
	$h->bindParam(':code', $hashcode);
	if(!$h->execute()) { $sqlErrors++;}
	
	if($student->language == 'EN') { 
		    $subject = 'Activate your account';
    		/* Body of Email */
            $body = "<p>Dear ".$student->firstName."</p><p>Thank you for signing up with Study With Me. Before you get started you will need to confirm your email. To do this click the link below.</p>";
            $body .= '<p><a href="https://web.igp.noel.me.uk/scripts/activate/'.$hashcode.'">Activate My Account</a></p>';
            $body .= '<p>All the best<br><br>Study With Me Team</p>';
    } else { 
		    $subject = '激活帐户';
		    /* Body of Email */
            $body = "<p>你好 ".$student->firstName."</p><p>谢谢您注册为 Study With Me. 在你开始之前，你需要确认你的电子邮件。为此，请单击下面的链接。</p>";
            $body .= '<p><a href="https://web.igp.noel.me.uk/scripts/activate/'.$hashcode.'">激活帐户</a></p>';
            $body .= '<p>一切顺利<br><br>Study With Me 球队</p>';
	}
	
	// Send Activation Email
	$html = generateEmailHTML($subject,$subject,$body,$student->language);
	sendEmail($student->email,$subject,$html); 

	if($sqlErrors == 0)
	{
		$db->commit();
		$jsonArray["result"] = "successful";
	}
	else
	{
		$db->rollBack();
		$jsonArray["result"] = "failed";
		$jsonArray["message"] = "Database Failure";
	}
	// Output JSON Message
	echo json_encode($jsonArray);
}



function nc_changeEmail($email)
{
	global $student;
	global $db;
	global $noerrors;
		
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	if(!$student->userExists())
	{
		echo json_encode(array("result"=>"failed","message"=>"User not logged in"));
		return;
	}
	
	if($student->active)
	{
		echo json_encode(array("result"=>"failed","message"=>"User is already active"));
		return;
	}
	
	// Email validation 
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo json_encode(array("result"=>"failed","message"=>"Invalid Email"));
		return;
	}
	
	$checkEmail = $db->prepare("SELECT * FROM `students` WHERE `email` = :email");
	$checkEmail->bindParam(':email',$email);
	$checkEmail->execute();
	if($checkEmail->rowCount() > 0) {
		echo json_encode(array("result"=>"failed","message"=>"Email already in use"));
		return;
	}
	
	// Change Email
	if($student->changeEmail($email))
	{
	    // Use nc_resendActivation() to resent activation email
		nc_resendActivation();
	}
	else
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Failure"));
	}

}

function nc_deleteAccount()
{
	global $student;
	global $db;
	global $noerrors;
		
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	if(!$student->userExists())
	{
		echo json_encode(array("result"=>"failed","message"=>"User not logged in"));
		return;
	}
	
	if($student->active)
	{
		echo json_encode(array("result"=>"failed","message"=>"User is already active"));
		return;
	}
	
	// JSON Message array
	$jsonArray = array();
	
	// Start transaction
	$db->beginTransaction();
	$sqlErrors = 0;
	
	// Delete All Hash Codes
	$deleteHash = $db->prepare("DELETE FROM `hash_codes` WHERE `studentID` = :studentID");
	$deleteHash->bindParam(':studentID',$student->userID);
	if(!$deleteHash->execute()) { $sqlErrors++; }
	// Delete All studying subjects
	$deleteStudying = $db->prepare("DELETE FROM `studying` WHERE `studentID` = :studentID");
	$deleteStudying->bindParam(':studentID',$student->userID);
	if(!$deleteStudying->execute()) { $sqlErrors++; }
	// Delete Student
	$deleteStudent = $db->prepare("DELETE FROM `students` WHERE `studentID` = :studentID");
	$deleteStudent->bindParam(':studentID',$student->userID);
	if(!$deleteStudent->execute()) { $sqlErrors++; }
	
	if($sqlErrors == 0)
	{
		$db->commit();
		$jsonArray["result"] = "successful";
		session_destroy();
	}
	else
	{
		$db->rollBack();
		$jsonArray["result"] = "failed";
		$jsonArray["message"] = "Database Failure";
	}
	// Output JSON Message
	echo json_encode($jsonArray);
	
}
?>