<?php

function login($username,$password)
{
	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	
	$jsonArray = array();
	
	$findUser = $db->prepare("SELECT * FROM `members` WHERE `username` = :username AND `password` = :password");
	$findUser->bindParam("username", $username); 
	$findUser->bindParam("password", $password);
	$result = $findUser->execute();
	
	
	if($result)
	{
		if($findUser->rowCount() == 1)
		{
			$user = $findUser->fetch();
			$jsonArray["ID"] = $user['ID'];
			$jsonArray["username"] = $user['username'];
			$jsonArray["fname"] = $user['fname'];
			$jsonArray["lname"] = $user['lname'];
			$jsonArray["email"] = $user['email'];
			$jsonArray["memcat"] = $user['memcat'];
		}
		else{
			// No user found error
			$jsonArray["errorcode"] = 3;
		}
	}
	else
	{
		// SQL query error
		$jsonArray["error"] = 2;
	}
	// Output JSON Message
	echo json_encode($jsonArray);
}

?>