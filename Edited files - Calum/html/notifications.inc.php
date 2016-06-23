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
  
  // Template depending on type of notification:
  var notificationTemplate = {
    "New Message" : "<student> posted a message in <group>",
    "New Meeting" : "<student> created a new meeting for <group>",
    "Invitation" : "<student> invited you to <group>",
    "Group Defunct" : "Unfortunately, <group> has become defunctional",
    "Meeting Change" : "Arrangements for <meeting> in <group> have changed",
    "Group Reactivated" : "Hooray! <group> has become active again",
    "Meeting Cancelled" : "Unfortunately, the meeting for <group> has been cancelled",
    "Group Admin Changed" : "<student> has become the admin of <group>"
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
    "Group Admin Changed" : '<i class="fa fa-mortar-board fa-lg"></i>&nbsp;&nbsp;'
  };
  
  // Polls notifications every 5 seconds (run on page load):
  (function pollNotifications() {
    $.getJSON('../scripts/notifications', function(notifications){
      console.log(notifications);
      var unread = getNoOfUnreadNotifications(notifications);
      if (unread > 0) {
        $notificationStatus
          .show()
          .text(unread);
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
  
  // Populates the notification table after each poll
  function populateTable(notifications) {
    $notificationTable.empty();
    
    notifications.forEach(function(notification) {
      var rowClass = (notification.notificationRead == true) ? "read-notification" : "unread-notification info";
      var rowIcon = notificationIcon[notification.notificationType];
      
      var template = notificationTemplate[notification.notificationType];
      var withGroup = template.replace("<group>", '<a href="https://web.igp.noel.me.uk/dashboard/group/' + notification.groupID + '">' + notification.groupName + '</a>');
      var withStudent = withGroup.replace("<student>", notification.first_name);
      var final = withStudent.replace("<meeting>", notification.meetingID);
      
      //var notificationLink = href="/dashboard/group/<group>?notification=<notificationID>#<meetingID>";
      $notificationTable.append('<tr id="notif-' + notification.NotificationID + '" class="' + rowClass + '"><td>' + rowIcon + final + '</td></tr>');
    });
    bindNotifications();
  }
  
  // Rebinds the click event to the notification:
  function bindNotifications() {
    $('.unread-notification td a').click(function(ev) {
      ev.preventDefault();
      
      var notificationID = ev.target.parentElement.parentElement.id.split("-")[1];
      var groupLink = ev.target.href;
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

</script>