<?php 

function updateEmail()
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
	
	// Get Email Object
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	
	// Email validation 
	if (!filter_var($jsonData->email, FILTER_VALIDATE_EMAIL)) {
		echo json_encode(array("result"=>"failed","message"=>"Invalid Email"));
		return;
	}
	
	$checkEmail = $db->prepare("SELECT * FROM `students` WHERE `email` = :email");
	$checkEmail->bindParam(':email',$jsonData->email);
	$checkEmail->execute();
	if($checkEmail->rowCount() > 0) {
		echo json_encode(array("result"=>"failed","message"=>"Email already in use"));
		return;
	}
	
	// Change Email
	if($student->changeEmail($jsonData->email))
	{
		// Use nc_resendActivation() to resent activation email
		echo json_encode(array("result"=>"successful"));
		
	}
	else
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Failure"));
	}

}

function updateUsername(){
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
	
	// Get Email Object
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	
		
	$checkUsername = $db->prepare("SELECT * FROM `students` WHERE `username` = :username");
	$checkUsername->bindParam(':username',$jsonData->username);
	$checkUsername->execute();
	if($checkUsername->rowCount() > 0) {
		echo json_encode(array("result"=>"failed","message"=>"Username already in use"));
		return;
	}
	
	// Change Email
	$updateUsername = $db->prepare("UPDATE `students` SET `username` = :username WHERE `studentID` = :studentID");
	$updateUsername->bindParam(':username',$jsonData->username);
	$updateUsername->bindParam(':studentID',$student->userID);
	if($updateUsername->execute())
	{
		// Use nc_resendActivation() to resent activation email
		echo json_encode(array("result"=>"successful"));	
	}
	else
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Failure"));
	}
}

function updatePassword(){

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
	
	// Get Email Object
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	
	// Email validation 
	if (strlen($jsonData->newPassword) < 6) {
		echo json_encode(array("result"=>"failed","message"=>"New Password too short"));
		return;
	}
	
	// GET SALT VALUE
	$getSalt = $db->prepare("SELECT `salt` FROM `students` WHERE `studentID` = :studentID");
	$getSalt->bindParam(':studentID',$student->userID);
	$getSalt->execute();
	$fetch = $getSalt->fetch();
	$salt = $fetch['salt'];
	$oldPassword = $jsonData->oldPassword;
	
	$secure_password = hash('sha256', $salt.$oldPassword, false);
	
	// Check if old password is correct
	$checkPassword = $db->prepare("SELECT * FROM `students` WHERE `studentID` = :studentID AND `secure_password` = :securePassword");
	$checkPassword->bindParam(':studentID',$student->userID);
	$checkPassword->bindParam(':securePassword',$secure_password);
	$checkPassword->execute();
	if($checkPassword->rowCount() != 1) {
		echo json_encode(array("result"=>"failed","message"=>"Old Password is incorrect"));
		return;
	}
	
	// Change Password
	$newPassword = $jsonData->newPassword;
	$newSalt   = bin2hex(openssl_random_pseudo_bytes(24, $cstrong));
	$newSecurePassword = hash('sha256', $newSalt.$newPassword, false);
	
	$updatePassword = $db->prepare("UPDATE `students` SET `password` = :password, `secure_password` = :securePassword, `salt` = :salt  WHERE `studentID` = :studentID");
	$updatePassword->bindParam(':studentID',$student->userID);
	$updatePassword->bindParam(':password',$newPassword);
	$updatePassword->bindParam(':salt',$newSalt);
	$updatePassword->bindParam(':securePassword',$newSecurePassword);
	if($updatePassword->execute())
	{
		// Use nc_resendActivation() to resent activation email
		echo json_encode(array("result"=>"successful"));	
	}
	else
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Failure"));
	}
}

function updatePersonal(){
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
	
	// Get Email Object
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());
	
	if(isset($jsonData->first_name)) { $firstName = $jsonData->first_name; } else { $firstName = ''; }
	if(isset($jsonData->last_name)) { $lastName = $jsonData->last_name; } else { $lastName = ''; }
	if(isset($jsonData->DOB)) { $DOB = $jsonData->DOB; } else { $DOB = ''; }
	if(isset($jsonData->bio)) { $bio = $jsonData->bio; } else { $bio = ''; }
	
	// Server Side Validation
	$errors = array();
	if($firstName == '') { $errors[] = "First Name Required";  } 
	if($firstName == '' && $student->language == 'EN') { $errors[] = "Last Name Required";  } 
	if($DOB == '' ) { $errors[] = "Date of Birth required";  }
	
	
	// If validation errors exist - display errors
	if(COUNT($errors)>0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Failed validation"));
		return;
	}
	
	// Convert DOB date for database
	function convertToUSFormat($date)
	{
		// dd/mm/yyyy to mm/dd/yyyy
		$parts = explode("/",$date);
		$newFormat = $parts[1].'/'.$parts[0].'/'.$parts[2];
		return $newFormat;
	}
	$dateOfBirth = date("Y-m-d", strtotime(convertToUSFormat($DOB)));
	
	$updateQuery = "UPDATE `students` SET `first_name`= :firstName, `last_name` = :lastName, `DOB` = :dateOfBirth , `bio` = :bio  WHERE `studentID` = :studentID";
	$q = $db->prepare($updateQuery);
	$q->bindParam(':firstName', $firstName);
	$q->bindParam(':lastName', $lastName);
	$q->bindParam(':dateOfBirth', $dateOfBirth);
	$q->bindParam(':bio', $bio);
	$q->bindParam(':studentID', $student->userID );
	
	// Change Email
	if($q->execute()) 
	{
		// Use nc_resendActivation() to resent activation email
		echo json_encode(array("result"=>"successful"));
		
	}
	else
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Failure"));
	}
}

?>