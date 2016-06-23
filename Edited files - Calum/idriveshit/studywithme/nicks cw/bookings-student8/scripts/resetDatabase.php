<?php

function resetDatabase()
{
	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	
	$jsonMessage = array();
	
	// Path to get SQL file
	$filename = '../SQL/reset.sql';
	if (file_exists($filename)) {
		$sql = file_get_contents($filename);
		
		$query = $db->query($sql);
		if($query)
		{
			$jsonMessage['reset'] = 'Done';
		}
		else
		{
			$jsonMessage['errorcode'] = 3;
		}
	}
	else
	{
		$jsonMessage['errorcode'] = 4;
	}

	echo json_encode($jsonMessage);
	

}
?>

