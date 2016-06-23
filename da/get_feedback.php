<?php
	require_once('../scripts/databaseConnect.php');
		if($noerrors <> 0)
	{	
		echo json_encode(array("result"=>"Failed","error"=>"No connection"));		
		return;	
	}	
	
	
	$sql="select * from feedback";
	$r=$db->prepare($sql);
	$r->execute();
	
	$result=$r->fetchALL(PDO::FETCH_ASSOC);
	
	echo json_encode($result);