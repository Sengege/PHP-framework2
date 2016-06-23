<?php

//include('databaseConnect.php');

function getPurchasedTickets($userID){

	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	
	// Find purchased tickets 
	$findTickets = $db->prepare("SELECT a.ID, b.seatnum FROM `purchased` a INNER JOIN `seats` b ON a.seatID = b.ID WHERE a.memberID = :userID");
	$findTickets->bindParam("userID", $userID); 
	$findTickets->execute();

	// JSON Message array
	$jsonArray = array();

	
	if ($findTickets->rowCount() > 0)
	{
		$jsonArray["ticketNumber"]=$findTickets->rowCount();
		
		$tickets = $findTickets->fetchAll();
		foreach($tickets AS $ticket)
		{
			$jsonArray["tickets"][] = array("ID"=>$ticket['ID'],"seatnum"=>$ticket['seatnum']);
		}
	} 
	else 
	{
		$jsonArray["ticketNumber"]=$findTickets->rowCount();	
	}

	// Output JSON Message
	echo json_encode($jsonArray);

}
?>

