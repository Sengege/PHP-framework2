<?php
  function getNotifications(){
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
    $prep = $db->prepare(
    	"SELECT a.studentID, a.NotificationID, a.notificationRead, b.notificationDate, b.groupID, b.meetingID, b.notificationType, b.notifyingStudent, c.first_name, c.username, d.groupName
        FROM`user_notifications` a
        INNER JOIN`notifications` b ON a.notificationID = b.notificationID
        INNER JOIN`students` c ON b.notifyingStudent = c.studentID
        INNER JOIN`groups` d ON b.groupID = d.groupID
        WHERE a.studentID = :id
          AND b.notificationDate BETWEEN DATE_SUB(NOW(), INTERVAL 3 WEEK) AND DATE_ADD(NOW(), INTERVAL 1 DAY)
        ORDER BY b.notificationDate DESC"
     );
    
    $prep->bindParam('id', $student->userID); // Current user's ID
    $prep->execute(); // Hammertime
    $jsonArray = $prep->fetchAll(PDO::FETCH_ASSOC); // Fetch assoc for JSON
    echo json_encode($jsonArray); // Return
  }
?>