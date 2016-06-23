<?php 

error_reporting(E_ALL);/*include database connection*/
require_once('scripts/prepend.php');

// If user is not logged in redirect to index.php
if(!$student->userExists()) { header('Location: index.php'); die(); } 
if(!$student->active) { header('Location: notActive.php'); die(); }

require_once('scripts/functions/functions.php');



/* Start HTML*/
startHTML("Shall we Study?",false);

?>



<div class="page-section white">
	<div class="container">
	<?php include('html/addgroupModal.inc.php'); /* includes html and modal for add group*/?>
		<div><p>Welcome <?php echo $student->username; ?>, this is your dashboard</p></div>
		<ul id="myTab" class="nav nav-tabs">
		  <li role="presentation" ><a href="#notifications" id="notifications-tab" role="tab" data-toggle="tab" aria-controls="notifications" aria-expanded="true">Notifications <span class="badge notification-count"></span></a></li>
		  <li role="presentation" class="active"><a href="#myGroup" id="mygroups-tab" role="tab" data-toggle="tab" aria-controls="myGroup" aria-expanded="true">My Groups</a></li>
		  <li role="presentation"><a href="#searchGroup" id="searchGroup-tab" role="tab" data-toggle="tab" aria-controls="searchGroup" aria-expanded="true">Search Groups</a></li>
		  <li role="presentation"><a href="#myProfile" id="myProfile-tab" role="tab" data-toggle="tab" aria-controls="myProfile" aria-expanded="true">My Profile</a></li>
		</ul>
		<p>&nbsp;</p>
		<div id="myTabContent" class="tab-content">
		  <div role="tabpanel" class="tab-pane fade" id="notifications" aria-labelledby="notifications-tab">
			<?php include('html/notifications.inc.php'); /* includes html for nortifications */?>
		  </div>
		  
		  <div role="tabpanel" class="tab-pane fade in active" id="myGroup" aria-labelledby="mygroups-tab">
			<?php include('html/myGroups.inc.php'); /* includes html for groups */?>
		  </div>
		  
		  <div role="tabpanel" class="tab-pane fade" id="searchGroup" aria-labelledby="searchGroup-tab">
			<?php include('html/searchGroups.inc.php'); /* Includes HTML for group data */?>
		  </div>
		  
		
		  
		  <div role="tabpanel" class="tab-pane fade" id="myProfile" aria-labelledby="myProfile-tab">
			<?php include('html/studentDetails.inc.php'); /* Includes HTML for student profile info*/?>
			
		  </div>
		</div>
	

 
  
  
	</div>
</div>
<?php
footerHTML();
?>