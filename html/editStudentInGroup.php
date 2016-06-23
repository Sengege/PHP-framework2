<?php
 
 function adminAddUser() {
        global $db;
        global $student;
        global $noerros;
        
        $students = array();
        
        if($noerrors <> 0)
        
        {
		echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
		return;
    	}
    	
    	if(!$student->userExists())
    	{
    		echo json_encode(array("result"=>"failed","message"=>"User not logged in"));
    		return;
    	}
    	$request = Slim\Slim::getInstance()->request();
    	$jsonData = array (
            'studentID'=>$request->post('studentID'),
            'groupID'=>$request->post('groupID'),
            'dateJoined'=>$request->post('dateJoined')
        );
         
        if(isset($jsonData->students)) { 
	    	if (is_array($jsonData->students)) {$students = $jsonData->students; }
    	}
        
    	
    	$s = $db->prepare("INSERT INTO `group_membership`(`studentID`, `groupID`,`dateJoined`) VALUES (:studentID,:groupID,:dateJoined)");
    	// For students chosen - add them to group_membership
		foreach($students AS $eachStudent)
		{
			$s->bindParam(':studentID', $eachStudent);
			$s->bindParam(':groupID', $groupID);
			$s->bindParam(':dateJoined', $registrationDate);
			//$s->execute();
			if (!$s->execute()) {
			  $insert_success = false;
	        }
	    }
    }
    
function adminRemoveUsers() { x=0}

?>