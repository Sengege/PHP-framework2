<div class="container">
      <div id="adminGroups" class="panel panel-default">
		<div class="panel-heading">会议</div><!-- /.panel-heading -->
		<div class="panel-body">
		<div class="table-responsive">
			<table class="table">
              <thead>
              <th>会议名称</th>
              <th>小组</th>
              <th>日期</th>
              <th>持续时间</th>
              <th>会议内容</th>
              </thead>
              <tbody>
                <!-- Meetings go here -->
                <?php 
                global $student;
                global $db;
                
                $studentID = $student->userID;
                
                $getMeetings = $db->prepare("SELECT * FROM  `meetings` a INNER JOIN  `groups` b ON a.groupID = b.groupID INNER JOIN  `meeting_attending` c ON a.meetingID = c.meetingID WHERE c.studentID = :studentID AND `time` > now() ORDER BY `time` DESC");
                $getMeetings->bindParam(":studentID", $studentID);
                $getMeetings->execute();
                if($getMeetings->rowCount() ==0){
                echo "没有即将到来的会议！";
                }
                else{
                foreach($getMeetings->fetchAll() as $meeting){
                echo "<tr><td>".$meeting['meetingName']."</td><td>".$meeting['groupName']."</td><td>".date("d/m/Y H:i",strtotime($meeting['time']))."</td><td>".(intval($meeting['duration'])/60)." hr</td><td>".$meeting['agenda']."</td></tr>";
                }
                }
                ?>
              </tbody>
            </table>
      </div>
  </div>
</div>
</div>