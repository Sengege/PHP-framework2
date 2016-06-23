<?php

//include 'databaseConnect.php';
//$userID = '1';

/*
if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	
	// Find Groups
	$groups = $db->prepare("SELECT * FROM `group_connect` a INNER JOIN `groups` b ON a.groupID = b.groupID WHERE a.`studentID` = ?");
	//$findTickets->bindParam("userID", $userID); 
	$groups->execute(array($userID));

	// JSON Message array
	$jsonArray = array();


	if ($groups->rowCount() > 0)
	{
		$jsonArray["groupNumber"]=$groups->rowCount();
		
		$userGroups = $groups->fetchAll();
		foreach($userGroups AS $group)
		{
			$jsonArray["group"][] = array("ID"=>$groups['groupID'],"name"=>$groups['groups']);
		}
	} 
	else 
	{
		$jsonArray["groupNumber"]=$groups->rowCount();	
	}

	// Output JSON Message
	echo json_encode($jsonArray);
	
	*/
	echo 'hi';
?>
