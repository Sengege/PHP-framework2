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
	
	// Find purchased tickets 
	$findModules = $db->prepare("SELECT * FROM `modules` WHERE `universityID` = :universityID");
	$findModules->bindParam("universityID", $universityID); 
	$findModules->execute();

	// JSON Message array
	$jsonArray = array();

	
	if ($findModules->rowCount() > 0)
	{
		$jsonArray["module_number"]=$findModules->rowCount();
		
		$modules = $findModules->fetchAll();
		foreach($modules AS $module)
		{
			$jsonArray["modules"][] = array("ID"=>$module['moduleID'],"module_code"=>$module['module_code'],"module_name"=>$module['module_name']);
		}
	} 
	else 
	{
		$jsonArray["module_number"]=$findModules->rowCount();	
	}

	// Output JSON Message
	echo json_encode($jsonArray);

}
?>

