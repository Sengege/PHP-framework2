<?php

function purchase(){
	
	
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
	
	$jsonArray = array();
	
	$userID = $jsonData->userID;
	$seats = $jsonData->seats;
	
	$seatsPurchased = array();
	$purchased = 0;
	
	$findSeat = "SELECT * FROM `seats` WHERE `seatnum` = :seatnum";
	foreach($seats AS $s)
	{
		$find = $db->prepare($findSeat);
		$find->bindParam("seatnum", $s);
		$find->execute();
		$requestedSeat = $find->fetch();
		
		// Check seat is available
		if($requestedSeat["status"] == 1)
		{
			// Insert purchased row
			$insert = $db->prepare("INSERT INTO `purchased` (`memberID`,`seatID`) VALUES (:memberID,:seatID)");
			$insert->bindParam(":memberID", $userID);
			$insert->bindParam(":seatID", $requestedSeat["ID"]);
			$insertResult = $insert->execute();
			if($insertResult)
			{
				// Update seat to unavailable
				$update = $db->prepare("UPDATE `seats` SET `status` = '0' WHERE `ID` = ?");
				$updateResult = $update->execute(array($requestedSeat["ID"]));
				if($updateResult)
				{
					// Increment purchased
					$purchased++;
					$seatsPurchased[] = $s;
				}
			}
		}
	}
	
	if($purchased > 0)
	{
		$jsonArray["purchased"] = $seatsPurchased;
	}
	else
	{
		$jsonArray["errorcode"] = 3; 
	}
	
	// Output JSON Message
	echo json_encode($jsonArray);
	
}

?>