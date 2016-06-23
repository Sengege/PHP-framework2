<?php
function removePurchasedTickets() {
	
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

	$userID = $jsonData->userID;
	$ticketID = $jsonData->ticketID;
	
	$checkTicket = $db->prepare("SELECT * FROM `purchased` WHERE `memberID` = ? AND `ID` = ?");
	$checkTicket->execute(array($userID,$ticketID));
	
	if($checkTicket->rowCount() == 1)
	{
		$fetchedTicket = $checkTicket->fetch();
		$seatID = $fetchedTicket['seatID'];
		
		// Remove Purchased Row
		$deletePurchased = $db->prepare("DELETE FROM `purchased` WHERE `ID` = :ticketID"); 
		$deletePurchased->bindParam("ticketID", $ticketID); 
		$deleteResult = $deletePurchased->execute();
		
		// Update seat
		$updateSeat = $db->prepare("UPDATE `seats` SET `status` = '1' WHERE `ID` = :seatID");
		$updateSeat->bindParam('seatID',$seatID);
		$updateResult = $updateSeat->execute();
		
		if(($deleteResult)&&($updateResult))
		{
			$jsonMessage["removed"] = 'true'; 
		}
		else
		{
			$jsonMessage["errorcode"] = '4';
		}
	}
	else
	{
		$jsonMessage = array("test"=>'test',"user"=>$userID);
	}
	echo json_encode($jsonMessage);
	
}

?>