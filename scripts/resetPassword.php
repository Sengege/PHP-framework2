<?php
  function sendResetPassword(){
    // Globals:
    global $db;
    global $noerrors;
    global $student;

    // Errors:
    if($noerrors <> 0) {
        echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
        return;
    }
     
       
    $request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());

	$email = '';
	
	if(isset($jsonData->email)) { $email = $jsonData->email; } 
	// Is email valid

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		echo json_encode(array("result"=>"failed","message"=>"Invalid Email address"));
        return;
	}
	// Find account
	$findAccount = $db->prepare("SELECT * FROM `students` WHERE `email` = :email");
	$findAccount->bindParam(":email",$email);
	$findAccount->execute();
	// If account does not exist
	if($findAccount->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Account doesn't exist"));
        return;
	}
	// Create hash code
	$fetchAccount = $findAccount->fetch();
	$studentID = $fetchAccount['studentID'];
	$hashcode = bin2hex(openssl_random_pseudo_bytes(16, $cstrong));
		
	$h = $db->prepare("INSERT INTO `hash_codes`(`type`, `code`, `studentID`) VALUES ('password',:code,:studentID)");
	$h->bindParam(':studentID', $studentID);
	$h->bindParam(':code', $hashcode);
	if($h->execute())
	{
		
		if($fetchAccount['type'] == 'EN') { 
		    $subject = 'Reset Password';
    		/* Body of Email */
            $body = "<p>Dear ".$fetchAccount['first_name']."</p><p>You have requested to reset your password for account <strong>".$fetchAccount['username']."</strong> To do this click the link below.</p>";
            $body .= '<p><a href="http://dev.atux.co.uk/resetPassword/'.$hashcode.'">Reset Password</a></p>';
            $body .= '<p>All the best<br><br>Study With Me Team</p>';
		} else { 
		    $subject = '激活帐户';
		    /* Body of Email */
            $body = "<p>你好 ".$fetchAccount['first_name']."</p><p>您已要求重置密码的帐户 <strong>".$fetchAccount['username']."</strong> 为此，请单击下面的链接 </p>";
			$body .= '<p><a href="http://dev.atux.co.uk/resetPassword/'.$hashcode.'">激活帐户</a></p>';
			$body .= '<p>一切顺利<br><br>Study With Me 球队</p>';
		}
		// Send Activation Email
		$html = generateEmailHTML($subject,$subject,$body,$fetchAccount['type']);
		sendEmail($jsonData->email,$subject,$html);
		
		echo json_encode(array("result"=>"Successful"));
        return;
	}
	else
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Error"));
        return;
	}
	
	
}
function resetPassword()
{
	// Globals:
    global $db;
    global $noerrors;
    global $student;

    // Errors:
    if($noerrors <> 0) {
        echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
        return;
    }
     
       
    $request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());

	$hashcode = '';
	$newPassword = '';
	
	if(isset($jsonData->hashcode)) { $hashcode = $jsonData->hashcode; } 
	if(isset($jsonData->newPassword)) { $newPassword = $jsonData->newPassword; } 
	// Is email valid
	
	// Server Side Validation
	$errors = array();
	if($hashcode == '') { $errors[] = "Hashcode Required";  } 
	if($newPassword == '') { $errors[] = "Password Required";  }
	if(strlen($newPassword) < 6) { $errors[] = "Password must be at least 6 characters";  }
	

	// If validation errors exist - display errors
	if(COUNT($errors)>0)
	{
		$errorMessage = array();
		$errorMessage["result"] = 'Failed';
		$errorMessage["message"] = 'validation';
		$errorMessage["errorsFound"] = COUNT($errors);
		$errorMessage["errors"] = array();
		foreach($errors AS $error)
		{
			$errorMessage["errors"][] = $error;
		}
		echo json_encode($errorMessage);		
		return;
	}
	
	// Find account
	$findAccount = $db->prepare("SELECT * FROM `students` a INNER JOIN `hash_codes` b ON b.studentID = a.studentID WHERE b.code = :hashcode AND b.type= 'password'");
	$findAccount->bindParam(":hashcode",$hashcode);
	$findAccount->execute();
	
	// If account does not exist
	if($findAccount->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Account doesn't exist"));
        return;
	}
	// Create hash code
	$fetchAccount = $findAccount->fetch();
	$studentID = $fetchAccount['studentID'];
	
	$password = $newPassword;
	$salt   = bin2hex(openssl_random_pseudo_bytes(24, $cstrong));
	$securePassword = hash('sha256', $salt.$password, false);
	
	// Update account and delete hashcodes
	$db->beginTransaction();
	$sqlErrors = 0;
	// Update Student record
	$updateAccount = $db->prepare("UPDATE `students` SET `password`= :password, `salt` = :salt, `secure_password` = :securePassword WHERE `studentID` = :studentID");
	$updateAccount->bindParam(":password",$password);
	$updateAccount->bindParam(":salt",$salt);
	$updateAccount->bindParam(":securePassword",$securePassword);
	$updateAccount->bindParam(":studentID",$studentID);
	if(!$updateAccount->execute()) { $sqlErrors++; }
	//Remove hashcodes password only
	$removeCodes = $db->prepare("DELETE FROM `hash_codes` WHERE `studentID` = :studentID AND `type` ='password'");
	$removeCodes->bindParam(":studentID",$studentID);
	if(!$removeCodes->execute()) { $sqlErrors++; }
	
	if($sqlErrors == 0)
	{
		$db->commit();
		echo json_encode(array("result"=>"Successful"));
		return;
	}
	else
	{
		$db->rollBack();
		echo json_encode(array("result"=>"Failed","message"=>"Database Error"));
		return;
	}
	
	
}
?>