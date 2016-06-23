<?php

//include('databaseConnect.php');

// SLIM function to retrieve modules for a certain university
function getModules($universityID){

	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	// Find University
	$findUniversity = $db->prepare("SELECT * FROM `university` WHERE `universityID` = :universityID");
	$findUniversity->bindParam("universityID", $universityID); 
	$findUniversity->execute();
	
	// JSON Message array
	$jsonArray = array();
	
	if($findUniversity->rowCount() == 1)
	{
		// Fetch Uni data 
		$uniData = $findUniversity->fetch();
		$universityName = $uniData['name'];
		
		$jsonArray["valid_university"] = true;
		$jsonArray["university_name"] = $universityName;
		
		// Find purchased tickets 
		$findSchools = $db->prepare("SELECT * FROM `school` WHERE `universityID` = :universityID");
		$findModules = $db->prepare("SELECT * FROM `module` WHERE `schoolID` = :schoolID");
		
		$findSchools->bindParam("universityID", $universityID); 
		$findSchools->execute();

		

		
		if ($findSchools->rowCount() > 0)
		{
			$jsonArray["school_number"]=$findSchools->rowCount();
			
			$schools = $findSchools->fetchAll();
			$jsonArray["schools"] = array();
			foreach($schools AS $school)
			{
				$findModules->bindParam("schoolID",$school['schoolID']);
				$findModules->execute();
				$schoolID = $school['schoolID'];
				$schoolname = $school['name'];
				$modules = array();
				
				foreach($findModules->fetchAll() AS $module)
				{
					$modules[] = array("ID"=>$module['moduleID'],"module_code"=>$module['module_code'],"module_name"=>$module['module_name']);
				}
				$school = array("ID" => $schoolID ,"name" => $schoolname, "modules" => $modules);
				$jsonArray["schools"][] = $school;
			}
			
		} 
		else 
		{
			$jsonArray["school_number"]=$findModules->rowCount();
			$jsonArray["schools"] ="";
		}
	}
	else
	{
		$jsonArray["valid_university"] = false;
	}
	// Output JSON Message
	echo json_encode($jsonArray);

}
?>

