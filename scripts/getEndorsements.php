<?php
  function getEndorsements($groupID){
    // Globals:
    global $db;
    global $noerrors;
    global $student;

    // Errors:
    if($noerrors <> 0) {
        echo json_encode(array("result"=>"failed","message"=>"Database Connection"));
        return;
    }
     
    // Validation:
    if(!$student->userExists()) {
        echo json_encode(array("result"=>"failed","message"=>"User not logged in"));
        return;
    }
    
    // Query:
    $jsonArray = array(); // Return this
    $prep = $db->prepare("SELECT * FROM `endorsements` WHERE `groupID` = :groupID");
    
    $prep->bindParam('groupID', $groupID); // Current group ID
    $prep->execute(); // Hammertime
    $jsonArray = $prep->fetchAll(PDO::FETCH_ASSOC); // Fetch assoc for JSON
    echo json_encode($jsonArray); // Return
  }
?>