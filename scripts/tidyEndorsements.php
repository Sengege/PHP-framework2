<?php 

  //$newUserIdList = json_decode($_POST['userList']); // [1, 2, 3] -> array(1, 2, 3)
  $tidyGroupID = $_POST['groupID']; // 1 -> 1, doesn't need decode 
  
  function tidyEndorsements($groupID){
    // tidy m8
    
    global $db;
    $list = "";
    $prefix = "";
    $jsonMessage = array();
    
    $getGroupMembers = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID");
    $getGroupMembers->bindParam(':groupID', $groupID);
    $getGroupMembers->execute();
    
    foreach ($getGroupMembers->fetchAll() as $user) {
      $list .= $prefix . $user;
      $prefix = ", ";
    }
    
    $stmt = $db->prepare("DELETE FROM endorsements WHERE studentID NOT IN (:list) AND groupID = :groupID");
    $stmt->bindParam(":list", $list);
    $stmt->bindParam(":groupID", $groupID);
    if ($stmt->execute()) {
      $jsonMessage['result'] = 'successful';
    } else {
      $jsonMessage['result'] = 'unsuccessful';
    }
    
    echo $jsonMessage;
  }
  
  tidyEndorsements($newUserIdList, $tidyGroupID);
?>