<?php

function activation($activation)
{
	global $student;
	global $db;
	global $noerrors;
	
	include ('functions/functions.php');
	
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	
	// JSON Message array
	$jsonArray = array();
	
	$findActivation = $db->prepare("SELECT * FROM `hash_codes` WHERE `type` = 'activation' AND `code` = :activationCode");
	$findActivation->bindParam(':activationCode',$activation);
	$findActivation->execute();
	
	if($findActivation->rowCount() == 1)
	{
	
		$codeData = $findActivation->fetch();
		$studentID = $codeData['studentID'];
		$hashID = $codeData['hashID'];
		
		// Start transaction
		$db->beginTransaction();
		$sqlErrors = 0;
		
		// Make User Active
		$userActive = $db->prepare("UPDATE `students` SET `active` = '1' WHERE `studentID` = :studentID");
		$userActive->bindParam(':studentID',$studentID);
		if(!$userActive->execute()) { $sqlErrors++; };
		
		// Remove Hash Code
		$removeCode = $db->prepare("DELETE FROM `hash_codes` WHERE `hashID` = :hashCode");
		$removeCode->bindParam(':hashCode',$hashID);
		
		if(!$removeCode->execute()) { $sqlErrors++; }
		
		if($sqlErrors == 0)
		{
			$db->commit();
			startHTML('Account Activated',false);
			echo '<div style="text-align:center;"><h2>Account Activated</h2></div>';
			footerHTML();
			return;
		}
		else
		{
			$db->rollBack();
			startHTML('Account Activated',false);
			echo '<div style="text-align:center;"><h2>Sorry an error occurred</h2></div>';
			footerHTML();
			return;
		}
	}
	else
	{
			startHTML('Account Activated',false);
			echo '<div style="text-align:center;"><h2>Sorry this activation code doesn\'t exist or has expired</h2></div>';
			footerHTML();
			return;
	}
	
}
?>