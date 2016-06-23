<?php

function addUser() {
	global $db;
	global $noerrors;
	
	// If database connect details are incorrect
	if($noerrors <> 0)
	{	
		echo json_encode(array("result"=>"Failed","error"=>"No connection"));		
		return;	
	}	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	
	if(!isset($jsonData->language))
	{
		echo json_encode(array("result"=>"Failed","message"=>"No language set"));		
		return;	
	}
	// Allowed language codes 
	$allowedLanguages = array('EN','CN');
	// Store language code
	$language = $jsonData->language;
	// If code is invalid show error
	if(!in_array($language, $allowedLanguages))
	{
		echo json_encode(array("result"=>"Failed","message"=>"Invalid language code"));		
		return;	
	}
	
	
	// Store values from JSON OBject
	
	if(isset($jsonData->first_name)) { $firstName = $jsonData->first_name; } else { $firstName = ''; }
	if(isset($jsonData->last_name)) { $lastName = $jsonData->last_name; } else { $lastName = ''; }
	if(isset($jsonData->email)) { $email = $jsonData->email; } else { $email = ''; }
	if(isset($jsonData->username)) { $username = $jsonData->username; } else { $username = ''; }
	if(isset($jsonData->password)) { $password = $jsonData->password; } else { $password = ''; }
	if(isset($jsonData->university)) { $university = $jsonData->university; } else { $university = ''; }
	if(isset($jsonData->DOB)) { $DOB = $jsonData->DOB; } else { $DOB = ''; }
	if(isset($jsonData->school)) { $school = $jsonData->school; } else { $school = ''; }
	
	// Server Side Validation
	$errors = array();
	
	if($language == 'EN')
	{
		if($firstName == '') { $errors[] = "First Name Required";  } 
		if($lastName == '') { $errors[] = "Last Name Required";  } 
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Valid Email Required";  } 
		if($username == '') { $errors[] = "Username Required";  } 
		if($password == '') { $errors[] = "Password Required";  } 
		if($university == 0) { $errors[] = "Invalid University";  }
		if($DOB == '') { $errors[] = "Date of Birth required";  }
		if($school == 0) { $errors[] = "School ID required";}
	}
	else if($language == 'CN')
	{
		if($firstName == '') { $errors[] = "名称必需";  } 
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "有效的电子邮件必填";  } 
		if($username == '') { $errors[] = "昵称必填";  } 
		if($password == '') { $errors[] = "需要密码";  } 
		if($university == 0) { $errors[] = "大学无效";  }
		if($DOB == '') { $errors[] = "出生日期要求";  }
		if($school == 0) { $errors[] = "学校无效";}
	}
	
	// If validation errors exist - display errors
	if(COUNT($errors)>0)
	{
		$errorMessage = array();
		$errorMessage["result"] = "Failed";
		$errorMessage["message"] = "Failed validation";
		$errorMessage["errorsFound"] = COUNT($errors);
		$errorMessage["errors"] = array();
		foreach($errors AS $error)
		{
			$errorMessage["errors"][] = $error;
		}
		echo json_encode($errorMessage);		
		return;
	}
	
	// Check if email already exists
	$emailQ = $db->prepare("SELECT * FROM `students` WHERE `email` = :email");
	$emailQ->bindParam(':email', $email);
	$emailQ->execute();
	if($emailQ->rowCount() > 0)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Email already exists"));		
		return;	
	}
	
	// Check if username already exists
	$usernameQ = $db->prepare("SELECT * FROM `students` WHERE `username` = :username");
	$usernameQ->bindParam(':username', $username);
	$usernameQ->execute();
	if($usernameQ->rowCount() > 0)
	{
		echo json_encode(array("result"=>"Failed","message"=>"Username already exists"));		
		return;	
	}
	
	/* ALL VALIDATION HAS PASSED*/
	
	// Convert DOB date for database
	function convertToUSFormat($date)
	{
		// dd/mm/yyyy to mm/dd/yyyy
		$parts = explode("/",$date);
		$newFormat = $parts[1].'/'.$parts[0].'/'.$parts[2];
		return $newFormat;
	}
	$dateOfBirth = date("Y-m-d", strtotime(convertToUSFormat($jsonData->DOB)));
	
		
	
	// Generate Salt and Hash Password
	$password = $jsonData->password;
	$salt   = bin2hex(openssl_random_pseudo_bytes(24, $cstrong));
	$securePassword = hash('sha256', $salt.$password, false);
	
	// INSERT STUDENT SQL STATEMENT
	$insertQuery = "INSERT INTO `students`(`dateJoined`,`universityID`, `schoolID`, `first_name`, `last_name`, `DOB`, `email`, `username`, `password`, `salt`,`secure_password`,`type`) VALUES (CURDATE(), :universityID, :schoolID, :first_name, :last_name, :DOB, :email, :username, :password, :salt,:secure_password,:language)";
	$q = $db->prepare($insertQuery);
	$q->bindParam(':universityID', $university);
	$q->bindParam(':schoolID', $school);
	$q->bindParam(':first_name', $firstName);
	$q->bindParam(':last_name', $lastName);
	$q->bindParam(':DOB', $dateOfBirth);
	$q->bindParam(':email', $email);
	$q->bindParam(':username', $username);
	$q->bindParam(':password', $password);
	$q->bindParam(':salt', $salt);
	$q->bindParam(':secure_password', $securePassword);
	$q->bindParam(':language', $language);
	
	// JSON Message Array
	$jsonMessage = array();
	
	// If student insert execute is successful
	if($q->execute())
	{
		
		// Get Last Insert ID
		$studentID = $db->lastInsertId();
		// Declare studying array
		$studying= array();
		// if studying from JSON is set and is an array - add to $student variable
		if(isset($jsonData->studying)) 
		{ 
			if(is_array($studying)) { $studying = $jsonData->studying; }
		}
		
		$s = $db->prepare("INSERT INTO `studying`(`studentID`, `moduleID`) VALUES (:studentID,:moduleID)");
		$assignedCounter = 0;
		// Assign to modules if any
		for($i=0;$i<COUNT($studying);$i++)
		{
			$s->bindParam(':studentID', $studentID);
			$s->bindParam(':moduleID', $studying[$i]);
			if($s->execute()) { $assignedCounter++; }
		}
		
		/* Create and store hash code and email activation link to new user*/
		// Create Hash code and store in hash_codes Table
		$hashcode = bin2hex(openssl_random_pseudo_bytes(16, $cstrong));
		
		$h = $db->prepare("INSERT INTO `hash_codes`(`type`, `code`, `studentID`) VALUES ('activation',:code,:studentID)");
		$h->bindParam(':studentID', $studentID);
		$h->bindParam(':code', $hashcode);
		$h->execute(); 
		
		
		if($language == 'EN') { 
		    $subject = 'Activate your account';
    		/* Body of Email */
            $body = "<p>Dear ".$firstName."</p><p>Thank you for signing up with Study With Me. Before you get started you will need to confirm your email. To do this click the link below.</p>";
            $body .= '<p><a href="https://web.igp.noel.me.uk/scripts/activate/'.$hashcode.'">Activate My Account</a></p>';
            $body .= '<p>All the best<br><br>Study With Me Team</p>';
		} else { 
		    $subject = '激活帐户';
		    /* Body of Email */
            $body = "<p>你好 ".$firstName."</p><p>谢谢您注册为 Study With Me. 在你开始之前，你需要确认你的电子邮件。为此，请单击下面的链接。</p>";
            $body .= '<p><a href="https://web.igp.noel.me.uk/scripts/activate/'.$hashcode.'">激活帐户</a></p>';
            $body .= '<p>一切顺利<br><br>Study With Me 球队</p>';
		}
		// Send Activation Email
		require '../email/PHPMailerAutoload.php';
		require 'functions/email-template.php';

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();         
        $mail->Host = 'git.igp.noel.me.uk'; /* SMTP HOST */
        $mail->SMTPAuth = false;

        $mail->From = 'noreply@noel.me.uk';
        $mail->FromName = 'Study With Me';

        // Add new user recipient
        $mail->addAddress($jsonData->email); 

        // Test Email
        $mail->addAddress('40092121@live.napier.ac.uk'); 

        $mail->addReplyTo('noreply@noel.me.uk', 'Study With Me');
        $mail->isHTML(true);                                
        $mail->Subject = $subject;

        /* wrap body with email template */
 	    $mail->Body    = generateEmailHTML($subject,$subject,$body,$language);
 	    $mail->send();
		
		// Return Success Message
		$jsonMessage["result"] = "successful";
		$jsonMessage["studentID"] = $studentID;
		
		// SET SESSION STORAGE
		$_SESSION['studentID'] = $studentID;
	}
	else
	{
		// Return unsuccessful json message
		$jsonMessage["result"] = "Failed";
		$jsonMessage["message"] = "Database Failed";		
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
}

function validateEmail($email)
{
	global $db;
	$jsonMessage = true;
	$emailQ = $db->prepare("SELECT * FROM `students` WHERE `email` = :email");
	$emailQ->bindParam(':email', $email);
	$emailQ->execute();
	if($emailQ->rowCount() > 0)
	{
		$jsonMessage = "This email is already in use";
	}
	echo json_encode($jsonMessage);
	
}

function validateUsername($username)
{
	global $db;
	$jsonMessage = true;
	$usernameQ = $db->prepare("SELECT * FROM `students` WHERE `username` = :username");
	$usernameQ->bindParam(':username', $username);
	$usernameQ->execute();
	if($usernameQ->rowCount() > 0)
	{
		$jsonMessage = "This username is already taken";
	}
	echo json_encode($jsonMessage);
}

?>