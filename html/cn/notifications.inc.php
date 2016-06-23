<div class="container">
  <div class="col-md-6">
      <div class="table-responsive">
            <table class="table table-bordered table-hover col-md-6 notifications-table">
              <thead>
              </thead>
              <tbody>
                <!-- Notifications go here -->
              </tbody>
            </table>
      </div>
  </div>
</div>

<script>
  // jQuery element cache:
  var $notificationStatus = $('span.notification-count');
  var $notificationTable = $('.notifications-table tbody');
  var $title = $('title');
  var TITLE = "Shall we Study?";
  
  // Template depending on type of notification:
  var notificationTemplate = {
    "New Message" : "<student> posted a message in <group>",
    "New Meeting" : "<student> created a new meeting for <group>",
    "Invitation" : "<student> added you to <group>",
    "Group Defunct" : "Unfortunately, <group> has become defunctional",
    "Meeting Change" : "Arrangements for <meeting> in <group> have changed",
    "Group Reactivated" : "Hooray! <group> has become active again",
    "Meeting Cancelled" : "Unfortunately, the meeting for <group> has been cancelled",
    "Group Admin Changed" : "<student> has become the admin of <group>",
    "New Members" : "New members have been added to <group>"
  };
  
  // Icon depending on type of notification:
  var notificationIcon = {
    "New Message" : '<i class="fa fa-comments fa-lg"></i>&nbsp;&nbsp;',
    "New Meeting" : '<i class="fa fa-calendar-o fa-lg"></i>&nbsp;&nbsp;',
    "Invitation" : '<i class="fa fa-flag fa-lg"></i>&nbsp;&nbsp;',
    "Group Defunct" : '<i class="fa fa-ban fa-lg"></i>&nbsp;&nbsp;',
    "Meeting Change" : '<i class="fa fa-bookmark fa-lg"></i>&nbsp;&nbsp;',
    "Group Reactivated" : '<i class="fa fa-users fa-lg"></i>&nbsp;&nbsp;',
    "Meeting Cancelled" : '<i class="fa fa-frown-o fa-lg"></i>&nbsp;&nbsp;',
    "Group Admin Changed" : '<i class="fa fa-mortar-board fa-lg"></i>&nbsp;&nbsp;',
    "New Members" : '<i class="fa fa-users fa-lg"></i>&nbsp;&nbsp;'
  };
  
  // Polls notifications every 5 seconds (runs on page load):
  (function pollNotifications() {
    $.getJSON('../scripts/notifications', function(notifications){      console.log(notifications);
      var unread = getNoOfUnreadNotifications(notifications);
      if (unread > 0) {
        $notificationStatus
          .show()
          .text(unread);
        $title.html("(" + unread + ") " + TITLE);
      } else {
        $title.html(TITLE);
      }
      populateTable(notifications);
      setTimeout(pollNotifications, 5000);
    });
  })();
  
  // Updates the badge on notification tab after each poll:
  function getNoOfUnreadNotifications(allNotifications) {
    var unread = 0;
    allNotifications.forEach(function(notification) {
      if (notification.notificationRead == false) {
        unread++;
      }
    });
    return unread;
  }
  
  // Populates the notification table after each poll:
  function populateTable(notifications) {
    // Locals:
    var rowClass;
    var rowIcon;
    var template;
    var withGroup;
    var withStudent;
    var withMeeting;
    var timeSince;
    
    $notificationTable.empty();
    notifications.forEach(function(notification) {
      timeSince = getTimeSince(notification);
      rowClass = (notification.notificationRead == true) ? "read-notification" : "unread-notification info";
      rowIcon = notificationIcon[notification.notificationType];
      
      template = notificationTemplate[notification.notificationType];
      withGroup = template.replace("<group>", '<a href="/dashboard/group/' + notification.groupID + '">' + notification.groupName + '</a>');      
      withStudent = withGroup.replace("<student>", '<a href="/dashboard/user/' + notification.username + '">' + notification.first_name + '</a>');
      withMeeting = withStudent.replace("<meeting>", notification.meetingID);
      
      $notificationTable.append('<tr id="notif-' + notification.NotificationID + '" class="' + rowClass + '"><td>' + rowIcon + '<span class="text-muted">' + timeSince + '</span>' + withMeeting + '</td></tr>');
    });
    bindNotifications();
  }
  
  // Rebinds the click event to the notification:
  function bindNotifications() {
    $('.unread-notification td a').click(function(ev) {
      var notificationID = ev.target.parentElement.parentElement.id.split("-")[1];
      var groupLink = ev.target.href;
      
      ev.preventDefault();
      readNotification(notificationID, groupLink);
    });
  }
  
  // Marks the notification as read when it's clicked and redirects to the relevant group's page:
  function readNotification(notificationID, groupLink) {
    var postData = {
      "notificationID": parseInt(notificationID)
    };
    
    $.post('../scripts/readNotification.php', postData, function(response) {
      console.log(response);
    },"JSON").done(function() {
      window.location = groupLink;
    });
  }
  
  // Attributed to Darin Dimitrov @ StackOverflow.com
  function getTimeSince(notification) {
    var today = new Date();
    var notificationDate = new Date(notification.notificationDate).addHours(8);
    //if (window.location.href.indexOf('/CN/') != -1){
      // Add 8 hours if in China
      //notificationDate.addHours(8);
    //}
    var diffMs = (notificationDate - today) * - 1; 
    var diffDays = Math.round(diffMs / 86400000); 
    var diffHrs = Math.round((diffMs % 86400000) / 3600000); 
    var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); 
    
    if (diffHrs < 1) {
      return diffMins + 'm: ';
    } else if (diffDays < 1) {
      return diffHrs + 'h: ';
    } else {
      return diffDays + 'd: ';
    }
  }
  
  // Attributed to Jason Harwig @ StackOverflow.com
  Date.prototype.addHours = function(h) {    
   this.setTime(this.getTime() + (h * 60 * 60 * 1000)); 
   return this;   
  }
</script>