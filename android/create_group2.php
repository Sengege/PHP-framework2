<?php
	require_once('../scripts/databaseConnect.php');
	//require_once('databaseConnect.php');
	//require_once('class/studentClass.php');
	if($noerrors <> 0)
	{
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
	}
	$groupModule=$_GET['moduleID'];
	$adminID=$_GET['adminID'];
	$groupName=$_GET['groupName'];
	$groupDescription=$_GET['groupDescription'];
	$oneOff=" ";
	$groupType="public";//$_GET['type'];
	//new Function 
	$gettags=$_GET['tags'];
	$tagCat=2;// 默认为Computer 因为Android没有实现选择分类的功能

	$tags = substr($gettags,1,strlen($gettags)-2); //去除[]
	$tagsarray=explode(",",$tags);
	$TagsID=array();
	foreach($tagsarray AS $tagName){
		
		$TagsID[]=newTag($tagCat,$tagName);
}
	
	function newTag($tagCat,$tagName) {
        global $db;
    
        $stmt = $db->prepare("SELECT * FROM `Tag` t JOIN `Tag_category` tc ON (t.Tag_categoryID = tc.Tag_categoryID) WHERE t.Tag_categoryID = :category AND t.name_EN = :tagName");
		$stmt->bindParam(":category", $tagCat);
		$stmt->bindParam(":tagName", $tagName);
        $stmt->execute();
        $returned = $stmt->rowCount();
        
        if ($returned == 0) {
          $TagID=insertNewTag($tagCat, $tagName);
		  return $TagID;
        } else {
          $row=$stmt->fetch(PDO::FETCH_ASSOC);
		  return $row['TagID'];
        }
    }
	
	function insertNewTag($tagCat, $tagName) {
        global $db;
        
        $stmt = $db->prepare("INSERT INTO `Tag` (`Tag_categoryID`,`name_CN`,`name_EN`) VALUES (:category, :nameCN, :nameEN)");
        $stmt->bindParam(':category', $tagCat);
        $stmt->bindParam(':nameCN', $tagName);
        $stmt->bindParam(':nameEN', $tagName);
        
        if ($stmt->execute()) {
          $TagID=$db->lastInsertId();
		  return  $TagID;
        } else {
          echo "unsuccessful";
        }
    }
	

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
		$errorMessage["errors"] = array();
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
	$insertQuery = "INSERT INTO `groups` (`moduleID`, `adminID`, `groupName`, `groupDescription`, `type`, `createdDate`, `one_off`) VALUES (:moduleID, :adminID, :groupName, :groupDescription, :groupType, :createdDate, :oneOff)";
	$q = $db->prepare($insertQuery);
	$q->bindParam(':moduleID', $groupModule);
	$q->bindParam(':adminID', $adminID);
	$q->bindParam(':groupName', $groupName);
	$q->bindParam(':groupDescription', $groupDescription);
	$q->bindParam(':groupType', $groupType);
	$q->bindParam(':oneOff', $oneOff);
	$q->bindParam(':createdDate', $registrationDate);
	



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
		
		
		//sql for add tags
		$t = $db->prepare("INSERT INTO `Tag_Group`(`TagID`,`groupID`) VALUES (:tagID, :groupID)");
		foreach($TagsID AS $tag){
		
		  $t->bindParam(':tagID', $tag);
		  $t->bindParam(':groupID', $groupID);
		  $t->execute();
		  
		
		
		}
		// Return Success Message
		$jsonMessage["result"] = "successful";
		
	}
	else
	{
			echo var_dump($q->errorInfo());
		// Return unsuccessful json message
		$jsonMessage["result"] = "failed";
		
	}
	
	
	
	
	
	
	
	// echo JSON Message
	echo json_encode($jsonMessage);
	
	

	
?>