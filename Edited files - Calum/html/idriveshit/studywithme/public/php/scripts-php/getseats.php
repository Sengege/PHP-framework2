<?php

function getSeats()
{
	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}

	// Query seats with status = 1 
	$query = "SELECT * FROM `seats`";
	$link = $db->query($query);

	// JSON Message array
	$jsonArray = array();

	if (!$link) {
		// If SQL statement is invalid return error
		$jsonArray["errorcode"] = 2;
	} 
	else {
		foreach($link AS $l) {
			$object = array($l['seatnum'],$l['status']);
			$jsonArray["seats"][] = $object;
		}	
	}

	// Output JSON Message
	echo json_encode($jsonArray);
}
?>