<?php

function addUser() {
	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	
	$request = Slim\Slim::getInstance()->request();
	$body = $request->getBody();
	$jsonData = json_decode($request->getBody());

	// Server Side Validation
	$validationErrors = 0 ;
	if(strlen($jsonData->fname) == 0) { $validationErrors++; }
	if(strlen($jsonData->lname) == 0) { $validationErrors++; }
	if(strlen($jsonData->address1) == 0) { $validationErrors++; }
	if(strlen($jsonData->town) == 0) { $validationErrors++; }
	if(strlen($jsonData->postcode) == 0) { $validationErrors++; }
	if(strlen($jsonData->phone) == 0) { $validationErrors++; }
	if(strlen($jsonData->username) < 5) { $validationErrors++; }
	if(strlen($jsonData->email) == 0) { $validationErrors++; }
	if(strlen($jsonData->password) < 8) { $validationErrors++; }
	
	// Membership Type
	$membershipType = '';
	switch($jsonData->memcat)
	{
		case 1:
			$membershipType = 'Ordinary';
			break;
		case 2:
			$membershipType = 'Friend';
			break;
		case 3:
			$membershipType = 'Premier';
			break;
		default:
			$validationErrors++;
	}
	
	$jsonMessage = array();
	
	// If passed validation
	if($validationErrors == 0)
	{
	
		$sql = "INSERT INTO `members`(`username`, `fname`, `lname`, `address1`, `address2`, `town`, `postcode`, `phone`, `email`, `password`, `memcat`) VALUES (:username,:fname,:lname,:address1,:address2,:town,:postcode,:phone,:email,:password,:memcat)";
		try {
			$insert = $db->prepare($sql);  
			$insert->bindParam("username", $jsonData->username);
			$insert->bindParam("fname", $jsonData->fname);
			$insert->bindParam("lname", $jsonData->lname);
			$insert->bindParam("address1", $jsonData->address1);
			$insert->bindParam("address2", $jsonData->address2);
			$insert->bindParam("town", $jsonData->town);
			$insert->bindParam("phone", $jsonData->phone);
			$insert->bindParam("postcode", $jsonData->postcode);
			$insert->bindParam("email", $jsonData->email);
			$insert->bindParam("password", $jsonData->password);
			$insert->bindParam("memcat", $membershipType);
			$result = $insert->execute();
			
			if($result)
			{
				$jsonMessage["fname"] = $jsonData->fname;
				$jsonMessage["lname"] = $jsonData->lname;
				$jsonMessage["email"] = $jsonData->email;
				$jsonMessage["user"] = $jsonData->username;
				$jsonMessage['userID'] = $db->lastInsertId();
				$jsonMessage["memcat"] = $membershipType;
			}
		
		} catch(PDOException $e) {
			// Script Failed
			$jsonMessage["errorcode"] = 3;
		}
		
	}
	else
	{
		// Validation Error
		$jsonMessage["errorcode"] = 4;
	}
	
	echo json_encode($jsonMessage);
	
}

?>
