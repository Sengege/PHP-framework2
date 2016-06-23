<?php
	require_once('../scripts/databaseConnect.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	$groupID=$_GET['groupID'];
	if($groupID==""){
		echo json_encode(array("result"=>"failed","message"=>"Not such group!"));
		return;
	}
		// Find Group
	$findGroup = $db->prepare("SELECT * FROM `groups` WHERE `groupID` = :groupID");
	$findGroup->bindParam(':groupID',$groupID);
	$findGroup->execute();
	
	// Group does not exist
	if($findGroup->rowCount() != 1)
	{
		echo json_encode(array("result"=>"failed","message"=>"Group does not exist"));
		return;
	}
		// Find Group
		$query = "SELECT studentID,first_name,last_name,email,profile_url FROM `students` where studentID in ("
		. " select studentID from group_membership where groupID = ? ) ";
   
		$q = $db->prepare($query);
		$q->execute(array($groupID));
		
		$rs=$q->fetchAll(PDO::FETCH_ASSOC);
		$result=array();
		if($rs){
			
			$result['result']="successful!";
			$result['data']=$rs;
			
			
			
		}else{
		echo json_encode(array("result"=>"failed","message"=>"Not members!"));
		return;
			
		}
			
		
		
		
		
		
		echo json_encode($result);
