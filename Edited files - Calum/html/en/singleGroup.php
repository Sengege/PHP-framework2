<?php

    global $db;
	global $student;
	
	$getGroupData = $db->prepare("SELECT * FROM `groups` a INNER JOIN `module` b ON a.moduleID = b.moduleID WHERE a.groupID = :groupID");
	$getGroupData->bindParam(":groupID",$groupID);
	$getGroupData->execute();



if($getGroupData->rowCount() == 0)
{
	// Start HTML
	startHTML("Group Not Found",false);
	echo '<div class="section white"><div class="container"><h2>Sorry this group does not exist</h2></div></div>';
	footerHTML();
	return;
}

// Group Exists
$groupData = $getGroupData->fetch();

// Check student is a member if group is private
if($groupData['type'] == 'private')
{
	$checkUser = $db->prepare("SELECT * FROM `group_membership` WHERE `groupID` = :groupID AND `studentID` = :studentID");
	$checkUser->bindParam(":groupID",$groupID);
	$checkUser->bindParam(":studentID",$student->userID);
	$checkUser->execute();
	
	if($checkUser->rowCount() != 1)
	{
		// Start HTML
		startHTML("Group Not Found",false);
		echo '<div class="section white"><div class="container"><h2>This is a private group and you do not have permission to view.</h2></div></div>';
		footerHTML();
		return;
	}
}

// if group exists and is public or student has access
startHTML($groupData['groupName'],false);
?>

 
<div class="section white">
	<div class="container">
		
		
		<div class="col-md-5">
			<div class="panel panel-default">
				<div class="panel-heading"><h2><?php echo $groupData['groupName']; ?> </h2></div>
				<ul class="list-group">
						<li class="list-group-item"><p><strong>Description</strong></p><p><?php echo $groupData['groupDescription']; ?></p></li>
						<li class="list-group-item"><p><strong>Module Details</strong></p><p><?php echo $groupData['module_name']; ?></p><p><?php echo $groupData['module_code']; ?></p></li>
						<li class="list-group-item"><p><strong>Members</strong></p>
							<div style="height:100px; overflow-y:auto">
								<?php
									foreach($db->query("SELECT * FROM `group_membership` g inner join `students` s on g.studentID = s.studentID Where g.groupID = $groupID"  ) AS $groupmembership)
									{
										echo $groupmembership['first_name'].' '.$groupmembership['last_name'].'<br>';
									}
								?>
							</div>
						</li>
						<li class="list-group-item">
						    <button class="btn btn-sm btn-danger">Leave Group</button>
						    <?php if(isGroupAdmin($groupID)) { ?>
						    <button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#addMeetingModal">Add Meeting</button>
						    <?php } ?>
						</li>
						
				</ul>
				
			</div>
		</div>
		<div class="col-md-7">
			<div id="meetingsContainer" data-group="<?php echo $groupID; /* Essential for meetings to work */?>">
				<script>
				$( document ).ready(function() {
					var meetings = {};
					meetings["meetingData"] = [];
					meetings["groupID"] = $("#meetingsContainer").data("group");
					getMeetings();
					
					// Display meeting details in modal
					$("#meetingTable").on('click','.meetingMoreInfo',function(e){
						e.preventDefault();
						var meetingData = meetings["meetingData"][$(this).data("meeting")];
						// HTML to display data
						var html = '';
						html += '<div class="col-sm-6"><p><strong>Agenda</strong><br>'+meetingData.agenda+'</p><p><strong>Date</strong><br>'+meetingData.meetingDay+' '+meetingData.meetingDateLong+'</p><p><strong>Time</strong><br>'+meetingData.startTime+' - '+meetingData.endTime+'</p>';
						if(meetingData.room.roomID !== null) { html += '<p><strong>Location</strong><br>'+meetingData.room.roomName+'</p>'; }
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
						
						if(meetingData.isAttending)
						{
							bootbox.dialog({
								size: 'large',
								message: html,
								title: meetingData.name,
								buttons: { 
									success: { label: '<i class="fa fa-close fa-lg"></i> Cancel Attendance', className: "btn-danger", callback: function() { removeAttendMeeting(meetingData.meetingID); } }, 
									danger: { label: "Ok", className: "btn-primary" }
								}
							});
						}
						else
						{
							bootbox.dialog({
								size: 'large',
								message: html,
								title: meetingData.name,
								buttons: { 
									success: { label: '<i class="fa fa-plus fa-lg"></i> Attend', className: "btn-success", callback: function() { attendMeeting(meetingData.meetingID); } }, 
									danger: { label: "Ok", className: "btn-primary" }
								}
							});
						}
					})
					
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
					
					function getMeetings()
					{
						$.get("/scripts/groups/meetings/get/"+meetings["groupID"], function(data) {
							console.log(data);			
							var jData = jQuery.parseJSON(data)
							var result = jData.result;
							if(result == 'Successful')
							{
								// Store meetings
								meetings["meetingData"] = jData.meetings;
								$("#meetingTable > tbody").empty();
								var html = '';
								$.each(meetings["meetingData"], function( key, value ) {
									html += '<tr><td>'+value.name+'</td><td>'+value.startTime+' - '+value.endTime+'</td><td>'+value.meetingDateShort+'</td>';
									if(value.room.roomID !== null) { html += '<td>'+value.room.roomName+'</td>'; } else { html += '<td> - </td>'; } 
									html += '<td><button type="button" class="btn btn-xs btn-warning meetingMoreInfo" data-meeting="'+key+'">More</button></td></tr>';
									console.log(value);
								})
								$("#meetingTable > tbody").append(html);
							}
						})
					}
				})
				</script>
				<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading"><i class="fa fa-calendar fa-lg"></i> Meetings</div>
						<div class="table-responsive">
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
				
			<script type="text/javascript"> 
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
			</div><!-- end of chatContainer -->
		</div><!-- end of col-md-6 container -->
				
	</div>
</div>

<!-- Include all compiled plugins (below), or include individual files as needed -->

	<script src="/js/datetime-picker/jquery.datepair.js"></script>
	<script src="/js/datetime-picker/jquery.timepicker.js"></script>
	
	<script src="/js/datetime-picker/datepair.js"></script>
	<script src="/js/validation/jquery.validate.js"></script>

	<script>
	/* script for update personal details */
	$( document ).ready(function() {
	
	  $('#meetingDate').datepicker({
		
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
						$("#loadRooms").text("Refresh");
						$("#roomBookContainer").show();
						$("#roomSelect").empty();
						$("#roomSelect").append(html);
						console.log(html);
					}
					else
					{
				
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
				    
					$.post( "/scripts/groups/meetings/add",jsonObject, function(data) 
					{ 
					    //console.log(data);			
							var jData = jQuery.parseJSON(data)
							var result = jData.result;
							if(result == 'Successful')
							{
								// Clear form
							    $("#meetingError").empty();
							    $('#addMeetingModal').modal('hide');
							    document.getElementById("newMeetingForm").reset();
								bootbox.alert("New Meeting Added");
								
							}
							else
							{
								var message = jData.message;
								$("#meetingError").empty();
								$("#meetingError").html(message);
							
							}
					});
					
			}
		})
		
	})
	$("#newMeetingForm").submit(function(e) { e.preventDefault(); });
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
							<input type="text" name="meetingDate" id="meetingDate" class="form-control"  data-date-format="dd-mm-yyyy" placeholder="Date" /> 
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
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
	  </form>
    </div>
  </div>
  
</div>

	
	

<?php footerHTML(); ?>