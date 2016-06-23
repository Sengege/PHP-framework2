<?php

  function getCounts() {
    global $db;
  
    $stmt = $db->prepare("SELECT dateJoined FROM students ORDER BY dateJoined DESC"); 
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $db->prepare("SELECT createdDate FROM groups ORDER BY createdDate DESC");
    $stmt->execute();
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $db->prepare("SELECT time FROM meetings ORDER BY time DESC");
    $stmt->execute();
    $meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $allData = json_encode(
      array(
        'groups' => $groups,
        'students' => $students,
        'meetings' => $meetings
      )
    );
    
    echo $allData; 
  }

?>