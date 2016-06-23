<?php
class Student
{
    // property declaration
	public $userExists = false;
	public $userID;
    public $firstName;
	public $lastName;
	public $DOB;
	public $universityID;
	public $schoolID;
	public $universityName;
	public $schoolName;
	public $bio;
	public $email;
	public $username;
	public $active;
	public $language;
	
	function __construct($ID = 0) 
    { 
        global $db; // database connection
		$getUser = $db->prepare("SELECT *,b.name AS universityName,c.name AS schoolName FROM `students` a INNER JOIN `university` b ON a.universityID = b.universityID INNER JOIN `school` c ON a.schoolID = c.schoolID WHERE a.studentID = :ID");
		$getUser->bindParam(":ID",$ID);
		$getUser->execute();
		($getUser->rowCount() == 1 ? $this->userExists = true :  $this->userExists = false );
		if($this->userExists)
		{
			$data = $getUser->fetch();
			$this->userID =  $data['studentID'];
			$this->firstName = $data['first_name'];
			$this->lastName = $data['last_name'];
			$this->DOB = $data['DOB'];
			$this->universityID = $data['universityID'];
			$this->schoolID = $data['schoolID'];
			$this->bio = $data['bio'];
			$this->email = $data['email'];
			$this->username = $data['username'];
			$this->universityName = $data['universityName'];
			$this->schoolName = $data['schoolName'];
			$this->language = $data['type'];
			if($data['active'] == 1) { $this->active = true; } else { $this->active = false; }
		}
    }

	public function userExists()
	{
		return $this->userExists;
	}
	
    function suggestedGroups()
	{
		if($this->userExists)
		{
		    /* Returns group data related to currently studying */
    		global $db;
    		$suggestedArray = array();
		
    		foreach($this->currentlyStudying() AS $module)
    		{
    			$query = "SELECT *,(SELECT COUNT(*) FROM group_membership b WHERE b.studentID = :studentID AND b.groupID = a.groupID) AS joined  FROM `groups` a INNER JOIN `module` c ON c.moduleID = a.moduleID WHERE a.moduleID = :moduleID HAVING `joined` = '0'";
    			$q = $db->prepare($query);
    			$q->bindParam("studentID", $this->userID); 
    			$q->bindParam("moduleID", $module);
    			$q->execute();
    			
    			if($q->rowCount() == 1)
    			{
    				$suggestedData = $q->fetch();
    				$suggestedArray[] = $suggestedData;
    			}
    			
    		}
    		
    		return $suggestedArray;
    	}
    }
	
	public function assignedGroups()
	{
		/* Returns assigned group data from database ONLY if exists */
		if($this->userExists)
		{
			global $db;
			// Find Groups assigned to
			$query = "SELECT * 
FROM  `group_membership` a
INNER JOIN  `groups` b ON a.groupID = b.groupID
INNER JOIN  `module` c ON c.moduleID = b.moduleID
WHERE a.studentID =?";
			
			$q = $db->prepare($query);
			$q->execute(array($this->userID));
			$groupData = $q->fetchAll();
			
			for($index=0; $index< count($groupData); $index++){
			$groupData[$index]['tags'] = array();
			//Query for tags and put in array
			$groupTags = $db->prepare("SELECT `name_en` FROM `Tag_Group` d INNER JOIN  `Tag` e ON e.TagID = d.TagID WHERE d.groupID = :groupID");
            $groupTags->bindParam(":groupID", $groupData[$index]['groupID']);
            $groupTags->execute();
			$groupData[$index]['tags'] = $groupTags->fetchAll();
			}
			return $groupData;
		}
	}
	
	public function currentlyStudying()
	{
		/* Returns modules currently studying ONLY if user exists */
		if($this->userExists)
		{
			global $db;
			$studying = array();
			
			// Store modules that student studies
			$query = "SELECT `moduleID` FROM `studying` WHERE `studentID` = ?";
			$q = $db->prepare($query);
			$q->execute(array($this->userID));
			
			foreach($q->fetchAll() AS $s)
			{
				$studying[] = $s['moduleID'];
			}
			return $studying;
		}
	}
	
	public function currentlyStudyingFull()
	{
		/* Returns modules currently studying ONLY if user exists */
		if($this->userExists)
		{
			global $db;
			$studying = array();
			
			// Store modules that student studies
			$query = "SELECT a.moduleID, b.module_code, b.module_name FROM `studying` a INNER JOIN `module` b ON a.moduleID = b.moduleID WHERE a.studentID = ?";
			$q = $db->prepare($query);
			$q->execute(array($this->userID));
			$studying = array();
			foreach($q->fetchAll() AS $s)
			{
				 $row = array();
				 $row["moduleID"] = $s['moduleID'];
				 $row["moduleName"] = $s['module_name'];
				 $row["moduleCode"] = $s['module_code'];
				 
				 $studying[] = $row;
			}
			return $studying;
		}
	}
	
	public function changeEmail($email)
	{
		/* Changes users email if valid and not in use by another */
		$returnValue = false; // by default
		
		if($this->userExists)
		{
			global $db;
			if (filter_var($email, FILTER_VALIDATE_EMAIL))
			{
			    $query = $db->prepare("SELECT * FROM `students` WHERE `email` = :email");
			    $query->bindParam(':email',$email);
			    $query->execute();
			    
			    if($query->rowCount() == 0)
			    {
			        $update = $db->prepare("UPDATE `students` SET `email` = :email WHERE `studentID` = :studentID");
			        $update->bindParam(':email',$email);
			        $update->bindParam(':studentID',$this->userID);
			        if($update->execute())
			        {
			            $this->email = $email;
			            $returnValue = true;
			        }
			    }
            }
        }
        return $returnValue;
    }
	
}
?>