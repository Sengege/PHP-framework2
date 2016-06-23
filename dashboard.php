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


<nav class="navbar navbar-default navbar-static-top">
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
          
          <ul id="myTab" class="nav navbar-nav navbar-right navbar-normal">
		  <li role="presentation" class=""><a href="#notifications" id="notifications-tab" role="tab" data-toggle="tab" aria-controls="notifications" aria-expanded="false"><i class="fa fa-flag"></i> Notifications <span class="badge notification-count"></span></a></li>
		  <li role="presentation" class="active"><a href="#myGroup" id="mygroups-tab" role="tab" data-toggle="tab" aria-controls="myGroup" aria-expanded="false"><i class="fa fa-group"></i>  My Groups</a></li>
		  <li role="presentation" class=""><a href="#searchGroup" id="searchGroup-tab" role="tab" data-toggle="tab" aria-controls="searchGroup" aria-expanded="false"><i class="fa fa-search"></i> Search Groups</a></li>
		  <li role="presentation" class=""><a href="#myMeetings" id="myMeetings-tab" role="tab" data-toggle="tab" aria-controls="myMeetings" aria-expanded="false"><i class="fa fa-calendar fa-lg"></i> Meetings</a></li>
		  <li role="presentation" ><a href="#myProfile" id="myProfile-tab" role="tab" data-toggle="tab" aria-controls="myProfile" aria-expanded="true"><i class="fa fa-user"></i> My Profile</a></li>
          <li><a href="#" id="logOut"><i class="fa fa-sign-out"></i> Log Out</a></li>
                
              </ul>
            </li>
		  
		</ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>
	

	<div class="container">
	<?php include('html/addgroupModal.inc.php'); /* includes html and modal for add group*/?>
		
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
		  
		<div role="tabpanel" class="tab-pane fade" id="myMeetings" aria-labelledby="myMeetings-tab">
			<?php include('html/myMeetings.inc.php'); /* Includes HTML for group data */?>
		  </div>
		  
		  <div role="tabpanel" class="tab-pane fade" id="myProfile" aria-labelledby="myProfile-tab">
			<?php include('html/studentDetails.inc.php'); /* Includes HTML for student profile info*/?>
			
		  </div>
		</div>
	

 
  
  
	</div>

<?php
footerHTML();
?>