<?php


  global $db;
  global $student;
  $menu = '<nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/" style="height:auto;">
		  <img src="/img/logo3.png" class="img-responsive">
		  </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          
          <ul id="myTab" class="nav navbar-nav navbar-right">
		  <li role="presentation" ><a href="/dashboard.php" ><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li><a href="#" id="logOut"><i class="fa fa-sign-out"></i> Log Out</a></li>
                
          </ul>
            </li>
		  
		</ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>';
	
  $getGroupData = $db->prepare("SELECT * FROM `groups` a INNER JOIN `module` b ON a.moduleID = b.moduleID WHERE a.groupID = :groupID");
  $getGroupData->bindParam(":groupID",$groupID);
  $getGroupData->execute();



if($getGroupData->rowCount() == 0)
{
  // Start HTML
  startHTML("Group Not Found",false);?>
  
  <?php
  echo $menu;
  echo '<div class="section white"><div class="container"><h2>Sorry this group does not exist</h2></div></div>';
  footerHTML();
  return;
}

// Group Exists
$groupData = $getGroupData->fetch();

// Check student is a member if group is private
if($groupData['type'] == 'private' && !isGroupMember($groupID))
{
    // Start HTML
    startHTML("Group Not Found",false);
	echo $menu;
    echo '<div class="section white"><div class="container"><h2>This is a private group and you do not have permission to view.</h2></div></div>';
    footerHTML();
    return;
}
else if($groupData['type'] != 'private' && !isGroupMember($groupID))
{
	// Start HTML
    startHTML($groupData['groupName'],false);
	echo $menu;
	?>
	<div class="col-md-12">
	<div id="groupDescriptionContainer" class="panel panel-default " data-group="<?php echo $groupID; ?>">
      
            <div class="panel-heading"><h2><?php echo $groupData['groupName']; ?></h2></div>
				<ul class="list-group">
					<li class="list-group-item"><p><strong>Description</strong></p><p><?php echo $groupData['groupDescription']; ?></p></li>
					<li class="list-group-item"><p><strong>Module Details</strong></p><p><?php echo $groupData['module_name']; ?></p><p><?php echo $groupData['module_code']; ?></p></li>
					<li class="list-group-item">
                    <button class="btn btn-danger" id="joinGroup">Join group</button>                         
                    </li>
				</ul>
        
    </div>
    </div>
    <script>
	$( document ).ready(function() {
		$("#joinGroup").click(function(e) {
			e.preventDefault();
			$(this).prop("disabled",true);
			$(this).html('<i class="fa fa-spinner fa-pulse"></i> Please Wait');
		
			var groupID = $("#groupDescriptionContainer").data("group");
			$.get( "/scripts/groups/join/"+groupID, function( data ) {
				  console.log(data);      
				  var jData = jQuery.parseJSON(data)
				  var result = jData.result;
				  if(result == 'successful')
				  {
					bootbox.alert("You have successfully joined this group!", function() {  location.reload();});  
				  }
				  else
				  {
					bootbox.alert("Something went wrong");
					$("#joinGroup").prop("disabled",false);
					$("#joinGroup").text('Join group');
				  }
				  
			});
        })
	})
	</script>
    <?php
	footerHTML();
    return;
}

// if group exists and student is member
startHTML($groupData['groupName'],false);
echo $menu;
?>
 

  <div class="container">
    
    
    <div class="col-md-5">
  <div id="groupDescriptionContainer" class="panel panel-default input-profile" data-group="<?php echo $groupID; /* Essential for edit group to work */?>">
      
    <?php if(isGroupAdmin($groupID)) { ?>
          <div class="pull-right" style="padding:3px">
            <button type="button" class="btn btn-default btn-sm" id="editGroup" ><i class="fa fa-edit fa-lg"></i> Edit</button>
            <button type="button" class="btn btn-default btn-sm" id="saveGroup" style="display:none" ><i class="fa fa-save"></i> Save</button>
            <button type="button" class="btn btn-default btn-sm" id="cancelSaveGroup" style="display:none" ><i class="fa fa-close"></i> Cancel</button>
          </div>
          <?php } ?>
        <div class="panel-heading"><h2><input type="text" disabled="disabled" style="width:100%"id="groupName" value="<?php echo $groupData['groupName']; ?>"> </h2></div>
        <ul class="list-group">
            <li class="list-group-item"><p><strong>Description</strong></p><p><div id="groupDescription" ><?php echo $groupData['groupDescription']; ?></div></p></li>
            <li class="list-group-item"><p><strong>Module Details</strong></p><p><?php echo $groupData['module_name']; ?></p><p><?php echo $groupData['module_code']; ?></p></li>
            <li class="list-group-item"><p><strong>Members</strong></p>
              <div id="groupMembers" data-group="<?php echo $groupID; ?>" style="height:100px; overflow-y:auto">
                <?php
                  foreach($db->query("SELECT * FROM `group_membership` g inner join `students` s on g.studentID = s.studentID Where g.groupID = $groupID"  ) AS $groupmembership)
                  {
                    echo '<a href="/dashboard/user/'.$groupmembership['username'].'">'.$groupmembership['first_name'].' '.$groupmembership['last_name'].'</a>&nbsp;<a class="btn-endorse" data-endorse="'.$groupmembership['studentID'].'"><i id="i'.$groupmembership['studentID'].'" class="fa fa-heart-o fa-lg" style="cursor:pointer;"></i></a>&nbsp;<a id="'.$groupmembership['studentID'].'" class="countEndorsement">0</a><br>';
                  }
                ?>
              </div>
            </li>
             <li class="list-group-item">
              <?php if(isGroupMember($groupID) && !(isGroupAdmin($groupID)) && !(isDefunct($groupID))) { ?>
                    <button id="leaveGroup" class="btn btn-sm btn-danger">Leave Group</button>
                <?php } ?>
                <?php if(isGroupAdmin($groupID) && !(isDefunct($groupID))) { ?>
                       <button id="closeGroup" class="btn btn-sm btn-danger">Close Group</button>               
                       <?php } ?>
                <?php if(isGroupAdmin($groupID) && !(isDefunct($groupID))) { ?>
                    <button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#addMeetingModal">Add Meeting</button>
                <?php } ?>
                <?php if(isGroupAdmin($groupID) && !(isDefunct($groupID))) { ?>
                    <button id="editUser" class="btn btn-default btn-md" >Edit Members</button>
                <?php } ?> 
                  <?php if(isGroupAdmin($groupID) && isDefunct($groupID)) { ?>
                    <button id="openGroup" class="btn btn-sm btn-danger">Reopen Group</button>                  
                <?php } ?>
            </li>
            <li id="slide" style="display:none" class="list-group-item">
              <br>
              <label>Edit Users </label>
                <br>
                <select type="text" id="studentOptions" class="form-control" name="students" multiple placeholder="Add students">
                  
              <?php
                  
                  $s = $db->prepare("SELECT * FROM `students` WHERE  `universityID` = :universityID AND `studentID` != :studentID");
                  $s->bindParam(':universityID',$student->universityID);
                  $s->bindParam(':studentID',$student->userID);
                  $s->execute();
                  $counter = 0;
                   $selectedUsers = array();
                    foreach($s->fetchAll() AS $students)
                    {
                    echo '<option value="'.$students['studentID'].'">'.$students['first_name'].' '.$students['last_name'].' ('.$students['username'].')</option>';
                    $counter++;
                     if(isPartOfGroup($groupID, $students['studentID'])) {
                     $selectedUsers[] = $counter;
                     }
                    }
                    ?>
                    
        
                  
                </select>
              <br>
                <button id="closeDiv"type="button" class="btn btn-default" >Close</button>
                <button id="changeMembers" type="submit" class="btn btn-primary">Save Members</button>
              <br>
            </li>
            
        </ul>
        
      </div>
   
    </div>
    <div class="col-md-7">
     <div id="meetingsContainer" data-group="<?php echo $groupID; /* Essential for meetings to work */?>">
        <div class="panel panel-default">
          <!-- Default panel contents -->
          <div class="panel-heading"><i class="fa fa-calendar fa-lg"></i> Meetings</div>
            <div class="table-responsive" style="height:350px;">
            <table class="table table-striped" id="meetingTable">
              <thead>
                <tr>
                <th>Name</th>
                <th>Time</th>
                <th>Date</th>
                <th>Room</th>
                <th></th>
                </tr>
              </thead>
              <tbody >
                
              </tbody>
          </table>
          </div>
        </div>
      </div>
   <div id="chatContainer">
        <!-- Keep everything in this container (chatContainer) together including script -->
        <div class="chat-panel panel panel-default" id="chat" data-group="<?php echo $groupID; /* Essential for chat to work */?>">
          <audio id="beepSound" src="/img/beep.mp3" preload="auto"></audio>
                        <div class="panel-heading"><i class="fa fa-comments fa-lg"></i>
                        Chat
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat" id="chatBody">
                
                            </ul>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
              <form id="addMessageForm">
                            <div class="input-group">
                                <input id="btn-input" type="text" class="form-control input-sm" name="message" placeholder="Type your message here...">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-warning btn-sm" id="btn-chat">
                                        Send
                                    </button>
                                </span>
                <div id="chatErrorMessage"></div>
                            </div>
              </form>
                        </div>
                        <!-- /.panel-footer -->
        </div>
        
      
      </div><!-- end of chatContainer -->
    </div><!-- end of col-md-6 container -->
        
  </div>


<!-- Include all compiled plugins (below), or include individual files as needed -->

  <script src="/js/datetime-picker/jquery.datepair.js"></script>
  <script src="/js/datetime-picker/jquery.timepicker.js"></script>
  
  <script src="/js/datetime-picker/datepair.js"></script>
  <script src="/js/validation/jquery.validate.js"></script>

   <script src="/js/chosen.jquery.js"></script>
  <script type="text/javascript"> 
  
    $( document ).ready(function() {
      $("#studentOptions").chosen({
        no_results_text: "Oops, nothing found!",
        width:"95%",
        //max_selected_options: 5
        });
 $.get('/scripts/groups/' + <?php echo $groupID; ?> + '/rating', function(rating){
        var stars = determineStars(rating);
        var starHTML = "";
        var remainingStars = 5;
        
        for(var i=0; i<stars; i++){
          starHTML += '<i class="fa fa-star fa-lg"></i>';
          remainingStars--;
        }

        for (var i=0; i<remainingStars; i++){
          starHTML += '<i class="fa fa-star-o fa-lg"></i>';
        }

        $('.list-group li:eq(1)').after('<li class="list-group-item"><p><strong>Group Rating</strong></p><p>' + starHTML + '</p></li>');
      });
      
      function determineStars(rating){
        if (rating < 10){
          return 0;
        }
        else if (rating >= 10 && rating < 20) {
          return 1;
        }
        else if (rating >= 20 && rating < 40) {
          return 2;
        }
        else if (rating >= 40 && rating < 60) {
          return 3;
        }
        else if (rating >= 60 && rating < 90) {
          return 4;
        }
        else if (rating > 90) {
          return 5;
        }
      }
    });

  </script>

<!-- Modal -->
<div class="modal fade" id="addMeetingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  <form id="newMeetingForm" class="form-horizontal" type="post" action="#">
  <input type="hidden" name="groupID" value="<?php echo $groupID; ?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Meeting</h4>
      </div>
      <div class="modal-body">
        
                <label for="meetingName" class="control-label">Meeting Name</label>
                <input type="text" name="meetingName" class="form-control" placeholder="Meeting name here" value="Meeting" >
                              
              <br>
              <label>Agenda</label><br>   
              
              <textarea id="agenda" name="agenda" class="form-control" placeholder="Meeting agenda here" style="resize: none;"></textarea>
              
              <br>
              <label for="meetingDate" class="control-label">Meeting Date</label>
              <br>
              <input type="text" name="meetingDate" class="form-control meetingDate"  data-date-format="dd-mm-yyyy" placeholder="Date" /> 
              <br>
              
              
              <div class="control-group">
                <label class="control-label">Meeting Time</label>
                <br>
                <div class="controls form-inline" id="datesFrom">
                  <span>From</span>
                  <input type="text" name="meetingTime" class="form-control time start" placeholder="Start Time" />
                  <span>To</span>
                  <input type="text" class="form-control time end" placeholder="End Time"/>
                </div>
              </div>
              <br>
              <label>Room Booking (optional)</label>
              <br><button class="btn btn-default" id="loadRooms" type="button">Book Room</button><br><br>
                <div id="roomBookContainer" style="display:none">
                  <select class="form-control " id="roomSelect" name="roomID">
                  </select>
                </div>
        
      </div>
      <div class="modal-footer">
      <span class="error" id="meetingError"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="addMeetingSubmit">Save changes</button>
      </div>
    </form>
    </div>
  </div>
  
</div>

<div class="modal fade" id="editMeetingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelEdit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  <form id="editMeetingForm" class="form-horizontal" type="post" action="#">
    <input type="hidden" id="editMeetingID" name="meetingID" value="">
  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabelEdit">Edit Meeting</h4>
      </div>
      <div class="modal-body">
        
                <label for="meetingName" class="control-label">Meeting Name</label>
                <input type="text" name="meetingName" id="editMeetingName" class="form-control" placeholder="Meeting name here" value="Meeting" >
                              
              <br>
              <label>Agenda</label><br>   
              
              <textarea id="editAgenda" name="agenda" class="form-control" placeholder="Meeting agenda here" style="resize: none;"></textarea>
              
              <br>
              <label for="meetingDate" class="control-label">Meeting Date</label>
              <br>
              <input type="text" name="meetingDate" id="editMeetingDate"  class="form-control meetingDate"  data-date-format="dd-mm-yyyy" placeholder="Date" /> 
              <br>
              
              
              <div class="control-group">
                <label class="control-label">Meeting Time</label>
                <br>
                <div class="controls form-inline" id="editDatesFrom">
                  <span>From</span>
                  <input type="text" name="meetingTime" class="form-control time start" placeholder="Start Time" id="editStartTime" />
                  <span>To</span>
                  <input type="text" class="form-control time end" placeholder="End Time" id="editEndTime"/>
                </div>
                <br>
                  <input type="hidden" name="currentRoomID">
                  <div style="display:none" id="roomIDContainer"></div>
              </div>
              <br>
              
              <br><button class="btn btn-default" id="editBookRoom" type="button">Book Room</button><br><br>
                <div id="editRoomBookContainer" style="display:none">
                  <select class="form-control " id="editRoomSelect" name="roomID">
                  </select>
                </div>
        
      </div>
      <div class="modal-footer">
      <span class="error" id="editmeetingError"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="editMeetingSubmit">Save changes</button>
      </div>
    </form>
    </div>
  </div>
  
</div>
<!-- end of modals -->
<script src="/ckeditor/ckeditor.js"></script>
<script>
    // Javascript to edit group - DO NOT TOUCH
    // We need to turn off the automatic editor creation first.
    //CKEDITOR.disableAutoInline = true;
    var editor;
    
    $("#editGroup").click(function(e){
      e.preventDefault();
      $(this).hide();
      $("#saveGroup").show();
      $("#cancelSaveGroup").show();
      $("#groupName").prop("disabled", false);
      editor = CKEDITOR.inline( 'groupDescription' );
      $("#groupDescription").prop("contenteditable", true);
    })
    $("#cancelSaveGroup").click(function(e){ 
      e.preventDefault();
      $("#groupName").prop("disabled", true); 
      $("#groupDescription").prop("contenteditable", false);
      editor.destroy();
      $("#editGroup").show();
      $("#saveGroup").hide();
      $("#cancelSaveGroup").hide();
    })
    $("#saveGroup").click(function(e){
      e.preventDefault();
      $(this).html('<i class="fa fa-spinner fa-pulse"></i>');
      $(this).prop("disabled", true);
      var name = $("#groupName").val();
      var description = editor.getData();
      var group = $( "#groupDescriptionContainer" ).data( "group" );
      $("#groupName").prop("disabled", true); 
      $("#groupDescription").prop("contenteditable", false);
      editor.destroy();
      // Pass data to save group function
      saveGroup(group,name,description);
    })
    function saveGroup(group,name,description)
    {
      var json = JSON.stringify({groupID : group, groupName : name, groupDescription : description});
      console.log(json);
      $.post( "/scripts/groups/edit", json, function( data ) {
              console.log(data);      
              try { var jData = jQuery.parseJSON(data) } catch (e) { bootbox.alert("<p>Something went wrong</p>"); $("#saveGroup").text('Save Group'); $("#saveGroup").prop("disabled", false); }
              var result = jData.result;
              if(result == 'Successful')
              {
                $("#saveGroup").hide();
                $("#cancelSaveGroup").hide();
                $("#editGroup").show();
                $("#saveGroup").text('Save Group');
                $("#saveGroup").prop("disabled", false);
                          
                bootbox.alert("Group has been edited"); 
              }
              else
              {
                var message = jData.message;
                bootbox.alert("<p>Something went wrong</p><p>"+jData.message+"</p>");
                $("#saveGroup").text('Save Group');
                $("#saveGroup").prop("disabled", false);
              }
            
            })
    }
    </script>
<script>
/* JAVASCRIPT FOR CLOSE AND LEAVE GROUPS*/
 //Leave group and close group functions 
$('#closeGroup').click( function() {closeGroup(<?php echo $groupID; ?>); });
$('#leaveGroup').click( function() {removeStudent(<?php echo $groupID; ?>, <?php echo $student->userID; ?>); });
$('#openGroup').click( function() {adminOpenGroup(<?php echo $groupID; ?>); });
$('#editUser').click( function() {
 $('#slide').toggle('slow');

         var selectedUsers = <?php echo json_encode($selectedUsers); ?>;
         jQuery.each(selectedUsers, function(index, selectid) {
         //$('selectedUsers').forEach(function(selectid){
         $('select option:nth-child('+selectedUsers[index]+')').attr('selected', 'selected'); 
         $('select').trigger('chosen:updated'); 
         });

                });
$('#closeDiv').click( function() {
  $("#slide").toggle('slow');
});

var userToAdd;

  $("#slide").on('change',"#studentOptions",function(e){
      // Get selected student
      var studentValue = $(this).val();
      // Add select student to array
      usersToAdd = studentValue;
      console.log(userToAdd);
      });
         

$('#changeMembers').click ( function() {
    $('#studentOptions').trigger('chosen:updated');
      var groupID = <?php echo $groupID; ?>;
     //Change animation
      $('#changeMembers').html('<i class="fa fa-spinner fa-pulse"></i>');
      // Load values into form
        var jsonObject = JSON.stringify({usersToAdd : usersToAdd, group: groupID});
      console.log(jsonObject);
      $.post("/scripts/groups/action/userAdd", jsonObject, function(data){
       try { var jData = jQuery.parseJSON(data) } catch (e) { bootbox.alert("<p>Something went wrong</p>"); }
        console.log(jData);
        var result = jData.result;
        if(result == 'Successful')
        {
          bootbox.alert("The Memebers List has been updated",function(){
            $(".bootbox.modal").remove();
            window.location = window.location.href;
          })
        }
        else
        {
          bootbox.alert("Something went wrong");
        }
       });
  });    


console.log(<?php echo $groupID; ?>);
console.log(<?php echo $student->userID; ?>);

function removeStudent(groupID, studentID){

     bootbox.confirm("Are you sure you want to leave group", function(result){
 
             var postData = {
                  "groupID": groupID,
                  "studentID": studentID
                 };
             //console.log(postData);
             $.post('/scripts/groups/action/remove', postData, function(data){

               window.location = '/dashboard.php';

             },'JSON');

     })
 
   }

   function closeGroup(groupID){

     bootbox.confirm("Are you sure you want to close down this group? Other Memebers of the group will no longer be able to use it"
    , function(result){
             var postData = {
   
                  "groupID": groupID,
 
                 }; 
             $.post('/scripts/groups/action/close', postData, function(data){
               window.location = '/dashboard.php';

             },'JSON');
        
     })
   
   }
  
   function  adminOpenGroup(groupID){
   
     bootbox.confirm("Are you sure you want to reopen the group?"
   
                     , function(result){
   
             var postData = { 
                  "groupID": groupID,
                };
             $.post('/scripts/groups/action/reopen', postData, function(data){
               window.location = '/dashboard.php';
             },'JSON');
     })
   }
   
</script>


<script>
 /* JAVASCRIPT FOR MEETINGS*/
  var meetings = {};
    
    meetings["meetingData"] = [];
    meetings["groupID"] = $("#meetingsContainer").data("group");
    meetings["editMeetingDuration"] = 0; 
    meetings["editMeetingAttending"] = 0;
  $( document ).ready(function() {
    
    
    getMeetings();
    
    // Display meeting details in modal
    $("#meetingTable").on('click','.meetingMoreInfo',function(e){
      e.preventDefault();
      var meetingIndex = $(this).data("meeting");
      var meetingData = meetings["meetingData"][meetingIndex];
      // HTML to display data
      var html = '';
      html += '<div class="col-sm-6"><p><strong>Agenda</strong><br>'+meetingData.agenda+'</p><p><strong>Date</strong><br>'+meetingData.meetingDay+' '+meetingData.meetingDateLong+'</p><p><strong>Time</strong><br>'+meetingData.startTime+' - '+meetingData.endTime+'</p>';
      if(meetingData.meetingFinished) { html += '<p class="error">This meeting has passed</p>'; }
      if(meetingData.room.roomID !== null) { 
        var remainingSeats = meetingData.room.seat_capacity - meetingData.attendingNumber;
        html += '<p><strong>Location</strong><br>'+meetingData.room.roomName;
        if(remainingSeats > 5) { html += ' <br>'+remainingSeats+' seats left</p>'; }
        else if (remainingSeats > 0) { html += ' <br><span class="error">'+remainingSeats+' seats left</span></p>'; }
        else { html += ' <br><span class="error">Room fully booked</span></p>'; }
        
      }
      html += '</div>';
      html += '<div class="col-sm-6"><p><strong>Facilitator</strong><br>'+meetingData.facilitator.firstName+' '+meetingData.facilitator.lastName+'</p>';
      html += '<p><strong>'+meetingData.attendingNumber+' Attendees</strong><p>';
      html += '<div style="height:100px; overflow-y:auto">';
      $.each(meetingData.attendees, function(key,value){
        html += value.firstName+' '+value.lastName+'<br>';
      })
      html += '</div>';
      
      html += '</div>';
      html += '<div class="clearfix"></div>';
      console.log(meetings["isAdmin"]);
      
      buttonsOptions = {}
      if(meetings["isAdmin"]) { buttonsOptions['warning'] = { label: '<i class="fa fa-edit fa-lg"></i> Edit', className: "btn-default", callback: function() {  editMeeting(meetingIndex);  }} }
      if(meetings["isAdmin"]) { buttonsOptions['delete'] = { label: '<i class="fa fa-edit fa-lg"></i> Delete', className: "btn-default", callback: function() {  bootbox.confirm("Are you sure you want to delete this meeting?",function(result) { if (result) { deleteMeeting(meetingIndex); } }); return false; }} }
      if(meetingData.isAttending ) { buttonsOptions['success'] = { label: '<i class="fa fa-close fa-lg"></i> Cancel Attendance', className: "btn-danger", callback: function() { removeAttendMeeting(meetingData.meetingID); }}}
      if((!(meetingData.isAttending) && remainingSeats > 0) && !(meetingData.meetingFinished) || (!(meetingData.isAttending) && meetingData.room.roomID === null && !(meetingData.meetingFinished) )) { buttonsOptions['success'] =  {label: '<i class="fa fa-plus fa-lg"></i> Attend', className: "btn-success", callback: function() { attendMeeting(meetingData.meetingID);}}}
      buttonsOptions['danger'] = { label: "Ok", className: "btn-primary" };
        
        
      bootbox.dialog({
        size: 'large',
        message: html,
        title: meetingData.name,
        buttons: buttonsOptions 
      });
                
      
    })
    function deleteMeeting(meetingKey)
    {
      console.log(meetingKey);
    
      var meetingData = meetings["meetingData"][meetingKey];
        console.log(meetingData);
      // Load values into form
      var jsonObject = JSON.stringify({ meetingID: meetingData.meetingID });
      console.log(jsonObject);
      $.post("/scripts/groups/meetings/delete", jsonObject, function(data){
        console.log(data);      
        var jData = jQuery.parseJSON(data)
        var result = jData.result;
        if(result == 'Successful')
        {
          bootbox.alert("This meeting has been deleted",function(){
            $(".bootbox.modal").remove();
            getMeetings();
          })
        }
        else
        {
          bootbox.alert("Something went wrong");
        }
      
      })
      
      
    }
    function editMeeting(meetingKey)
    {
      console.log(meetingKey);
    
      var meetingData = meetings["meetingData"][meetingKey];
        console.log(meetingData);
      // Load values into form
      $('#editMeetingID').val(meetingData.meetingID);
      $('#editMeetingName').val(meetingData.name);
      $('#editAgenda').val(meetingData.agenda);
      $('#editMeetingDate').val(meetingData.meetingDateShort);
      $('#editStartTime').val(meetingData.startTime24);
      $('#editEndTime').val(meetingData.endTime24);
      $("#editRoomBookContainer").hide(); // Reset 
      $("#editRoomSelect").empty(); // Reset
      if(meetingData.room.roomID != null)
      {
        $("#currentRoomID").val(meetingData.room.roomID);
        $("#roomIDContainer").html("<b>Room Booked: </b>"+meetingData.room.roomName+" <br><input type='checkbox' name='removeRoom'>Remove Room");
        $("#editBookRoom").text("Book New Room");
        $("#roomIDContainer").show();
      }
      else
      {
          $("#roomIDContainer").hide();
          $("#editBookRoom").text("Book Room");
      }
      
      $('#editMeetingForm .time').timepicker({
        'showDuration': true,
        'timeFormat': 'H:i'
      });
      var editDateTime = document.getElementById('editDatesFrom');
      var editDatePair = new Datepair(editDateTime);
      meetings["editMeetingDuration"] = (editDatePair.getTimeDiff()/1000)/60;
      meetings['editMeetingAttending'] = meetingData.attendingNumber;
      
      $('#editMeetingModal').modal('show');
    }
    
    function removeAttendMeeting(meetingID)
    {
      $.get("/scripts/groups/meetings/cancelAttend/"+meetingID, function(data){
        console.log(data);      
        var jData = jQuery.parseJSON(data)
        var result = jData.result;
        if(result == 'Successful')
        {
          bootbox.alert("You are no longer attending this meeting",function(){
            getMeetings();
          })
        }
        else
        {
          bootbox.alert("Something went wrong");
        }
      
      })
    }
    
    function attendMeeting(meetingID)
    {
      $.get("/scripts/groups/meetings/attend/"+meetingID, function(data){
        console.log(data);      
        var jData = jQuery.parseJSON(data)
        var result = jData.result;
        if(result == 'Successful')
        {
          bootbox.alert("You are now attending this meeting",function(){
            getMeetings();
          })
        }
        else
        {
          bootbox.alert("Something went wrong");
        }
      
      })
    }
    
    
  })
  function getMeetings()
    {
      $.get("/scripts/groups/meetings/get/"+meetings["groupID"], function(data) {
        console.log(data);      
        var jData = jQuery.parseJSON(data)
        var result = jData.result;
        if(result == 'Successful')
        {
          // Store meetings
          meetings["isAdmin"] = jData.isAdmin;
          meetings["meetingData"] = jData.meetings;
          $("#meetingTable > tbody").empty();
          var html = '';
          $.each(meetings["meetingData"], function( key, value ) {
            
            if(value.meetingFinished) {  html += '<tr class="passedMeeting">'; } else { html += '<tr>'; }
            
            html += '<td>'+value.name+'</td><td>'+value.startTime+' - '+value.endTime+'</td><td>'+value.meetingDateShort+'</td>';
            if(value.room.roomID !== null) { html += '<td>'+value.room.roomName+'</td>'; } else { html += '<td> - </td>'; } 
            if(value.meetingFinished) {  html+= '<strike>';}
            html += '<td><button type="button" class="btn btn-xs btn-warning meetingMoreInfo" data-meeting="'+key+'">More</button></td></tr>';
            console.log(value);
          })
          $("#meetingTable > tbody").append(html);
        }
      })
    }
    
  /* script for update personal details */
  $( document ).ready(function() {
  
  //Hide the LSider
   $("slide").hide();

    $('.meetingDate').datepicker({
    
      dateFormat: "dd/mm/yy"
      
    });

    
    $('#newMeetingForm .time').timepicker({
      'showDuration': true,
      'timeFormat': 'H:i'
      
    });
        
      
        

    var dateTime = document.getElementById('datesFrom');
    var datePair = new Datepair(dateTime);
    
    $("#loadRooms").click(function() { checkRooms(); })
    function checkRooms(){
      //Get date, time and duration
      var form = $("#newMeetingForm").serializeObject();
      checkObject = {};
      checkObject['meetingDate'] = form.meetingDate;
      checkObject['meetingTime'] = form.meetingTime;
      checkObject['duration'] = (datePair.getTimeDiff()/1000)/60;
      console.log(JSON.stringify(checkObject));
      $("#loadRooms").attr("disabled", true);
      $("#loadRooms").html('<i class="fa fa-spinner fa-pulse"></i>');
      $.post("/scripts/groups/meetings/freeRooms",JSON.stringify(checkObject),function(data){
        console.log(data);      
          var jData = jQuery.parseJSON(data)
          var result = jData.result;
          if(result == 'Successful')
          {
            
            var roomData = jData.roomData;
            var html = '<option value="">Pick a room</option>';
            $.each(roomData, function(key,value){
              html += '<optgroup label="'+key+'">';
              $.each(value, function(k,v){
                html += ' <option value="'+v.roomID+'">'+v.roomName+' ( '+v.capacity+' seats)</option>';
              })
              html +='</optgroup>';
            })
            $("#loadRooms").attr("disabled", false);
            $("#loadRooms").text("Refresh");
            $("#roomBookContainer").show();
            $("#roomSelect").empty();
            $("#roomSelect").append(html);
            console.log(html);
          }
          else
          {
            $("#loadRooms").html("Book Room");
            $("#loadRooms").attr("disabled", false);
          }
      })
    }
    //Show free rooms for edit meeting
    $("#editBookRoom").click(function() {  checkRoomsEdit();  })
    function checkRoomsEdit(){
      //Get date, time and duration
      var form = $("#editMeetingForm").serializeObject();
      checkObject = {};
      checkObject['meetingDate'] = form.meetingDate;
      checkObject['meetingTime'] = form.meetingTime;
      checkObject['duration'] = meetings["editMeetingDuration"] 
      checkObject['seats'] = meetings["editMeetingAttending"]
      console.log(JSON.stringify(checkObject));
      $("#editBookRoom").attr("disabled", true);
      $("#editBookRoom").html('<i class="fa fa-spinner fa-pulse"></i>');
      $.post("/scripts/groups/meetings/freeRooms",JSON.stringify(checkObject),function(data){
        console.log(data);      
          var jData = jQuery.parseJSON(data)
          var result = jData.result;
          if(result == 'Successful')
          {
            
            var roomData = jData.roomData;
            var html = '<option value="">Pick a room</option>';
            $.each(roomData, function(key,value){
              html += '<optgroup label="'+key+'">';
              $.each(value, function(k,v){
                html += ' <option value="'+v.roomID+'">'+v.roomName+' ( '+v.capacity+' seats)</option>';
              })
              html +='</optgroup>';
            })
            $("#editBookRoom").attr("disabled", false);
            $("#editBookRoom").text("Refresh");
            $("#editRoomBookContainer").show();
            $("#editRoomSelect").empty();
            $("#editRoomSelect").append(html);
            console.log(html);
          }
          else
          {
            $("#editBookRoom").html("Book Room");
            $("#editBookRoom").attr("disabled", false);
          }
          
      })
    }
    
    $("#newMeetingForm").validate({
      rules: { meetingName: "required", agenda: "required", meetingDate: "required", meetingTime: "required"  },
      messages: { 
          meetingName: { required: "Please provide a meeting name"}, 
          agenda: { required: "Please provide a agenda"},
          meetingDate:{ required: "Choose Date"},
          meetingTime: { required: "Choose Time"}
        },
        
      submitHandler: function(form) {
          var dataObject = $(form).serializeObject();
          if(dataObject.roomID != '') { dataObject["roomBooking"] = true;  }
          dataObject["duration"] = (datePair.getTimeDiff()/1000)/60;
          
          var jsonObject = JSON.stringify(dataObject);
            $("#addMeetingSubmit").attr("disabled", true);
          $("#addMeetingSubmit").html('<i class="fa fa-spinner fa-pulse"></i>');
          
          $.post( "/scripts/groups/meetings/add",jsonObject, function(data) 
          { 
              //console.log(data);      
              var jData = jQuery.parseJSON(data)
              var result = jData.result;
              if(result == 'Successful')
              {
                // Clear form
                $("#addMeetingSubmit").attr("disabled", false);
                  $("#meetingError").empty();
                  $('#addMeetingModal').modal('hide');
                  document.getElementById("newMeetingForm").reset();
                getMeetings();
                bootbox.alert("New Meeting Added");
                $("#addMeetingSubmit").text("Save Changes");
                
              }
              else
              {
                $("#addMeetingSubmit").text("Save Changes");
                $("#addMeetingSubmit").attr("disabled", false);
                var message = jData.message;
                $("#meetingError").empty();
                $("#meetingError").html(message);
                
              
              }
          });
          
      }
    })
    
    $("#editMeetingForm").validate({
      rules: { meetingName: "required", agenda: "required", meetingDate: "required", meetingTime: "required"  },
      messages: { 
          meetingName: { required: "Please provide a meeting name"}, 
          agenda: { required: "Please provide a agenda"},
          meetingDate:{ required: "Choose Date"},
          meetingTime: { required: "Choose Time"}
        },
        
      submitHandler: function(form) {
          var dataObject = $(form).serializeObject();
          console.log(dataObject.roomID);
          if(dataObject.roomID)
          { 
            if(dataObject.roomID !== null || dataObject.roomID !== "" ) { dataObject["roomChange"] = true;  } 
          } 
          if(dataObject.removeRoom == "on") { dataObject["roomCancel"] = true; }
          var editDateTime = document.getElementById('editDatesFrom');
          var editDatePair = new Datepair(editDateTime);
          dataObject["duration"] = (editDatePair.getTimeDiff()/1000)/60;
          
          var jsonObject = JSON.stringify(dataObject);
          console.log(jsonObject);
            
          $("#editMeetingSubmit").attr("disabled", true);
          $("#editMeetingSubmit").html('<i class="fa fa-spinner fa-pulse"></i>');
          $.post( "/scripts/groups/meetings/change",jsonObject, function(data) 
          { 
              console.log(data);      
              var jData = jQuery.parseJSON(data)
              var result = jData.result;
              if(result == 'Successful')
              {
                // Clear form
                  $('#editMeetingModal').modal('hide');
                getMeetings();
                $("#editMeetingSubmit").attr("disabled", false);
                $("#editMeetingSubmit").text("Save Changes");
                  bootbox.alert("Meeting Edited");
                
              }
              else
              {
                var message = jData.message;
                $("#editMeetingError").empty();
                $("#editMeetingError").html(message);
                $("#editMeetingSubmit").text("Save Changes");
                $("#editMeetingSubmit").attr("disabled", false);
              
              }
          });
          
      }
    })
    
  })
  $("#newMeetingForm").submit(function(e) { e.preventDefault(); });
  $("#editMeetingForm").submit(function(e) { e.preventDefault(); });
  
  
  
  $.fn.serializeObject = function()
  {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
      if (o[this.name] !== undefined) {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push(this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });
    return o;
  };
</script>
  
  
<script type="text/javascript"> 
/* JAVASCRIPT FOR CHAT FUNCTION*/

        $( document ).ready(function() {
        
        // Validation for chat
        $("#addMessageForm").validate({
          rules: { message: "required" },
          messages: { message: { required: "Please provide a group name"}},
          submitHandler: function(form) {
              // fill the json array
              $("#btn-chat").prop("disabled", true);
              //console.log($(form.message).val());
              addMessage($(form.message).val());
          }
        });
        

          var groupID = $( "#chat" ).data( "group" );
          var messages = [];
          // Set Interval to check for new messages 
          
          loadMessages();
          setInterval(checkMessages, 5000);
          
          function addMessage(message)
          {
            var json = {};
            json["groupID"] = groupID;
            json["message"] = message;
            var messageObject = JSON.stringify(json);
          
            // Send new message to server
            $.post( "/scripts/groups/messages/add", messageObject, function( data ) {
              //console.log(data);      
              var jData = jQuery.parseJSON(data)
              var result = jData.result;
              if(result == 'Successful')
              {
                // Clear form
                $("#chatErrorMessage").empty();
                $('#addMessageForm').trigger("reset");
                $("#btn-chat").prop("disabled", false);
                
                $("#btn-input").focus();
                
                // Refresh New Messages
                checkMessages();
                
                // Add notification for the message
                addNotificationForMessage();
              }
              else
              {
                var message = jData.message;
                $("#chatErrorMessage").empty();
                $("#chatErrorMessage").append('<span class="error">'+message+'</span>')
                $("#btn-chat").prop("disabled", false);
              }
            
            })
            
          }
          function checkMessages()
          {
            try {
              var lastMessageID = messages[messages.length-1].ID;
            }
            catch(err) {
              return;
            }
            
            
            // Check for new messages
            $.get( "/scripts/groups/messages/check/"+groupID+"/"+lastMessageID, function( data ) {
              //console.log(data);      
              var jData = jQuery.parseJSON(data)
              var result = jData.result;
              if(result == 'Successful')
              {
                if(jData.newMessageNumber > 0)
                {
                  var newMessages = jData.messages;
                  // Add new messages to existing messages array
                  for(var i =0;i<newMessages.length;i++)
                  {
                    messages.push(newMessages[i]);
                  }
                                
                  // Append new messages
                  var html = '';
                  $.each( newMessages, function( key, value ) {
                    //console.log(value.name);
                    html += '<li><div class="chat-body"><div class="header"><strong class="primary-font">'+value.firstName+' '+value.lastName+'</strong>';
                    html += '<small class="pull-right text-muted"><i class="fa fa-clock-o fa-fw"></i>'+value.postDate+'</small>';
                    html += '</div><p>'+value.message+'</p></div></li>';
                  })
                  
                  // Add messages to UL
                  $("#chatBody").append(html);
                  
                  //Scroll to bottom of div
                  $(".panel-body").scrollTop(999999);
                  
                  // Ring sound
                  document.getElementById('beepSound').play();
                  
                }
                
              }
            })
          }
          
          function addNotificationForMessage() {
              var notifType = "New Message";
              var studentID = <?php echo $student->userID; ?>;
              
              var postData = {
                  "notificationType": notifType,
                  "studentID": studentID,
                  "meetingID": "",
                  "groupID": groupID
              };
              console.log(postData);
              
              $.post('/scripts/addNotification.php', postData, function(response) {
                  if (response.result == "successful") {
                      console.log("success");
                  } else if (response.result == "unsuccessful") {
                      console.log("failed");
                  }
              }, "JSON");
          }
          
          function loadMessages()
          {
            $.get( "/scripts/groups/messages/all/"+groupID, function( data ) {
              //console.log(data);      
              var jData = jQuery.parseJSON(data)
              var result = jData.result;
              if(result == 'Successful')
              {
                messages = jData.messages;
                var html = '';
                $.each( messages, function( key, value ) {
                  //console.log(value.name);
                  html += '<li><div class="chat-body"><div class="header"><strong class="primary-font">'+value.firstName+' '+value.lastName+'</strong>';
                  html += '<small class="pull-right text-muted"><i class="fa fa-clock-o fa-fw"></i>'+value.postDate+'</small>';
                  html += '</div><p>'+value.message+'</p></div></li>';
                })
                
                // Add messages to UL
                $("#chatBody").append(html);
                
                // Scroll to bottom
                $(".panel-body").scrollTop(999999);
                
                  
                }
              })
            }
          });
      
      
        </script>
        <script>
        $( document ).ready(function() {
        
        getEndorsements();
        
        var studentID = <?php echo $student->userID; ?>;
        
        
        // Validation for chat
              $("a.btn-endorse").click(function(e){
          e.preventDefault();
        var endorseID = $(this).data( "endorse" );
        if(endorseID != studentID){
            if( $("#i"+endorseID).attr('class')  != 'fa fa-heart fa-lg'){
                toggleEndorse(endorseID);
                getEndorsements();
                    }
                }
          })
        });
        

        
          function toggleEndorse(endorseID)
          {
        var groupID = $( "#groupMembers" ).data( "group" );
      
      if( groupID == ''){
      alert("Insufficient Data Found");
      return;
      }
      if( endorseID == ''){
      alert("Insufficient Data Found");
      return;
      }
      
            var json = {};
            json["groupID"] = groupID;
            json["endorseID"] = endorseID;
            var endorseObject = JSON.stringify(json);
          
            // Send new message to server
            $.post( "/scripts/groups/toggle/endorse", endorseObject, function( data ) {
              //console.log(data);      
              var jData = jQuery.parseJSON(data)
              var result = jData.result;
              if(result == 'Successful')
              {
                console.log("Toggled Endorsement");
        //toggleIcon();
              }
              else
              {
                bootbox.alert("Could not toggle endorsement at this time please try again later!");
              }
            
            })
      }
      
      function getEndorsements(){
      //fetch from the database using post on the function getendorsement
      var groupID = $( "#groupMembers" ).data( "group" );
      
      if( groupID == ''){
      alert("Insufficient Data Found");
      return;
            }
      

          
            // Send new message to server
            $.post( "/scripts/groups/endorsements/"+groupID, function( data ) {
              console.log(data);      
              var endorsements = jQuery.parseJSON(data)
              
              $(".countEndorsement").html("0");
              endorsements.forEach(function(index){
              var existingEndorsement = parseInt($("#"+index['studentID']).html());
              existingEndorsement += 1;
              $("#"+index['studentID']).html(existingEndorsement);
              
              if(index['endorsedBy'] = <?php echo $student->userID; ?>){
              $("#i"+index['studentID']).attr('class', 'fa fa-heart fa-lg');
              }
              
              
              })
      
      
      });
    }
      
        </script>

<?php footerHTML(); ?>