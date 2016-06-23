<?php
	require_once('databaseConnect.php');
	//require_once('databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	$insert="select * from rooms where campusID=6";
	$r = $db->prepare($insert);
	$r->execute();
	$result = $r->fetchALL(PDO::FETCH_ASSOC);
	
	echo json_encode($result);