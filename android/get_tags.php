<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	//get_Modules
	$getModulesql = "SELECT * FROM `tag`  ";
	$q = $db->prepare($getModulesql);
	$q->execute();
	$result=$q->fetchAll(PDO::FETCH_ASSOC);
	$rs=array();


	
	$rs['result']="successful";
	$rs['data']=$result;
	

	
	echo json_encode($rs);
	




?>