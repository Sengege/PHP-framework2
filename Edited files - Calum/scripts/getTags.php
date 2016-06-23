<?php

//include('databaseConnect.php');

// SLIM function to retrieve tags for a tag category
function getTags($categoryID){

	global $db;
	global $noerrors;
	
	if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
	// Find University
	$findTags = $db->prepare("SELECT * FROM `Tag` WHERE `Tag_categoryID` = :categoryID");
	$findTags->bindParam(":categoryID", $categoryID); 
	
	
	// JSON Message array
	$jsonArray = array();
	if($findTags->execute()){
	
	$tags = array();
	
	foreach($findTags->fetchAll() AS $tag)
				{
					$tags[] = array("ID"=>$tag['TagID'],"Name"=>$tag['name_EN'],"Chinese"=>$tag['name_CN']);
				}
				$jsonArray = $tags;
	}
	else{
	$jsonArray = "fail";
	}

	// Output JSON Message
	echo json_encode($jsonArray);

}
?>