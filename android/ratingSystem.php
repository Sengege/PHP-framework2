<?php

function calculateRating($groupID){

    //echo $groupID;
    
    global $student;
    global $db;
    
    //php fetch with groupID
    $getGroupMembers = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID");
    $getGroupMembers->bindParam(":groupID", $groupID);
    if (!$getGroupMembers->execute()) { echo "fail"; }
    
    $noGroupMembers = $getGroupMembers->rowCount();
    //echo $noGroupMembers;
    
    $groupMembers = $getGroupMembers->fetchAll();
    
    //get array of meetings in last 3 weeks
    $getMeetings = $db->prepare("SELECT * FROM `meetings` WHERE `groupID` = :groupID AND `time` BETWEEN DATE_SUB(NOW(), INTERVAL 3 WEEK) AND DATE_ADD(NOW(), INTERVAL 1 DAY)");
    $getMeetings->bindParam(":groupID", $groupID);
    $getMeetings->execute();
    $meetingArray = $getMeetings->fetchAll();

    $numberMeetings = $getMeetings->rowCount();
    if($numberMeetings == 0){
    $numberMeetings = 1;
    }
    
    $sumOfMeetingPercentage = 0;
    
     $getAttendees = $db->prepare("SELECT * FROM `meeting_attending` WHERE `meetingID` = :meetingID");
    
    foreach($meetingArray as $meeting){
   
    $getAttendees->bindParam(":meetingID", $meeting['meetingID']);
    $getAttendees->execute();
    
    $noAttendees = $getAttendees->rowCount();
    
    $percentageAttending = $noAttendees / $noGroupMembers;
    $sumOfMeetingPercentage += $percentageAttending;
    }
    
    $meetingsRating = $sumOfMeetingPercentage / $numberMeetings;
    //echo "Meetings Rating: ".$meetingsRating;
    
    
    //love = all group endorsements/ total amount of endorsements possible
    
    $endorsementsPossible = $noGroupMembers*($noGroupMembers - 1);
    
    $getEndorsements = $db->prepare("SELECT * FROM `endorsements` WHERE `groupID` = :groupID");
    $getEndorsements->bindParam(":groupID", $groupID);
    $getEndorsements->execute();
    
    $noEndorsements = $getEndorsements->rowCount();
    
    $loveRating = $noEndorsements / $endorsementsPossible;
    //echo "Love Rating: ".$loveRating;
    
    //check that each member has posted in the group
    
    /*
    original
    $membersList = '';
    foreach($groupMembers as $member){
    $membersList .= $member['studentID'] .', ';
    }
    $membersList = substr($membersList,0,strlen($membersList)-2);
    //echo $membersList;
    $getMembersWithMessages = $db->prepare("SELECT * from `group_message` WHERE `groupID` = :groupID AND `studentID` IN (:membersList) AND `post_date` BETWEEN DATE_SUB(NOW(), INTERVAL 3 WEEK) AND DATE_ADD(NOW(), INTERVAL 1 DAY)");
    $getMembersWithMessages->bindParam(":groupID", $groupID);
    $getMembersWithMessages->bindParam(":membersList", $membersList);
    $getMembersWithMessages->execute();
    $noMembersWithMessages = $getMembersWithMessages->rowCount();
    foreach($getMembersWithMessages->fetchAll() as $loop){
    echo $loop;
    }*/
    
    $noMembersWithMessages = 0;
    foreach($groupMembers as $member){
    $getMembersWithMessages = $db->prepare("SELECT * from `group_message` WHERE `groupID` = :groupID AND `studentID` = :studentID AND `post_date` BETWEEN DATE_SUB(NOW(), INTERVAL 3 WEEK) AND DATE_ADD(NOW(), INTERVAL 1 DAY)");
    $getMembersWithMessages->bindParam(":groupID", $groupID);
    $getMembersWithMessages->bindParam(":studentID", $member['studentID']);
    //I just wiped my arse with a sheet of a4 paper. lol
    $getMembersWithMessages->execute();
    if($getMembersWithMessages->rowCount() > 0){
    $noMembersWithMessages += 1;
    }
    }
    
    $messageRating = $noMembersWithMessages / $noGroupMembers;
    //echo "Message Rating: ".$messageRating;
    
    $groupRating = (($loveRating*2) + $messageRating + ($meetingsRating*3))*100/6;
    $groupRating = round($groupRating);
    return /*"Group Rating: ".*/$groupRating;
    
}

?>