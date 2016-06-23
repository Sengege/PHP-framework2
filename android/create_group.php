<?php
	require_once('../scripts/databaseConnect.php');
	//require_once('class/studentClass.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	$groupModule=1;//$_GET['moduleID'];
	$adminID=$_GET['adminID'];
	$groupName=$_GET['groupName'];
	$groupDescription=$_GET['groupDescription'];
	$oneOff=" ";
	$groupType="public"        ;//$_GET['type'];
	//new Function 
	/*
	$tag=$_GET['tag'];
	
	$tagID=get_tagID($tag,$db);
	
	function get_tagID($tag,$db){
		$tag_categoryID=7;
		$tagsql="select tagID from tag where name_CN=? or name_EN=?";
		$q = $db->prepare($tagsql);
		$q->execute(array($tag,$tag));
		$rs=$q->fetch(PDO::FETCH_ASSOC);
		if($rs){
			$tagID=$rs['tagID'];
		}else{
				$taginsert="insert into`tag`(`name_CN`, `name_EN`, `tag_categoryID`) VALUES (:name_CN, :name_EN, :tag_categoryID)";
				$insert = $db->prepare($taginsert);
				$insert->bindParam(':name_CN', $tag);
				$insert->bindParam(':name_EN', $tag);
				$insert->bindParam(':tag_categoryID',$tag_categoryID);
				if($insert->execute())
				{
					$tagID = $db->lastInsertId();
					
				}else{
					$tagID=0;
				
				}
		}
		
		return $tagID;
	}
	

	if($tagID==0){}

*/
	$errors = array();
	if($groupName == '') { $errors[] = "Group Name Required";  } 
	if($groupModule == '') { $errors[] = "Group Module Required";  }
	if($groupDescription == '') { $errors[] = "Group Description Required";  }
//	if($oneOff == '') { $errors[] = "Group oneoff Required";  }
	if($groupType == '') { $errors[] = "Group Type Required";  }
		// If validation errors exist - display errors
	if(COUNT($errors)>0)
	{
		$errorMessage = array();
		$errorMessage["result"] = "failed";//COUNT($errors);
		$errorMessage["message"] = array();
		foreach($errors AS $error)
		{
			$errorMessage["errors"][] = $error;
		}
		echo json_encode($errorMessage);		
		return;
	}
	$registrationDate = date("Y-m-d H:i:s");
	//echo $registrationDate;
	
	// INSERT group SQL STATEMENT
	$insertQuery = "INSERT INTO `groups` (`moduleID`, `adminID`, `groupName`, `groupDescription`, `type`, `createdDate`, `one_off`,`tagID`) VALUES (:moduleID, :adminID, :groupName, :groupDescription, :groupType, :createdDate, :oneOff , :tagID)";
	$q = $db->prepare($insertQuery);
	$q->bindParam(':moduleID', $groupModule);
	$q->bindParam(':adminID', $adminID);
	$q->bindParam(':groupName', $groupName);
	$q->bindParam(':groupDescription', $groupDescription);
	$q->bindParam(':groupType', $groupType);
	$q->bindParam(':oneOff', $oneOff);
	$q->bindParam(':createdDate', $registrationDate);
	$q->bindParam(':tagID', $tagID);



	// JSON Message Array
	$jsonMessage = array();
	
	// If student insert execute is successful
	if($q->execute())
	{
		
		// Get Last Insert ID
		$groupID = $db->lastInsertId();
		$adminID =$adminID;
		
		// Add Group Membership row
		$s = $db->prepare("INSERT INTO `group_membership`(`studentID`, `groupID`,`dateJoined`) VALUES (:adminID,:groupID,:dateJoined)");
		
		
		$s->bindParam(':adminID',$adminID);
		$s->bindParam(':groupID', $groupID );
		$s->bindParam(':dateJoined', $registrationDate);
		$s->execute(); 
		
		
		// Return Success Message
		$jsonMessage["result"] = "successful";
		
	}
	else
	{
			
		// Return unsuccessful json message
		$jsonMessage["result"] = "failed";
		
	}
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
	

	
?>