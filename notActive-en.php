<?php 

error_reporting(E_ALL);/*include database connection*/
require_once('scripts/prepend.php');

// If user is not logged in redirect to index.php
if(!$student->userExists()) { header('Location: index.php'); die(); } 
if($student->active) { header('Location: dashboard.php'); die(); }

require_once('scripts/functions/functions.php');

/* Start HTML*/
startHTML("Account Not Active",false);

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
          
          <ul id="myTab" class="nav navbar-nav navbar-right">
		 
          <li><a href="#" id="logOut"><i class="fa fa-sign-out"></i> Log Out</a></li>
                
          </ul>
            </li>
		  
		</ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
	<div class="container">
		<div>
			<p>Hi <?php echo $student->username; ?>, your account isn't active yet! Check your email (<?php echo $student->email; ?>) for the activation email we sent you. Make sure to check your spam/junk folder just incase.</p>
			<p>See the options below</p>
			<p>
				<a href="#" id="resend">Resend activation email</a><br>
				<a href="#" id="change">Ooops! I signed up with the wrong email!!</a><br>
				<div id="changeEmailForm" style="display:none; padding:10px;">
					<form id="changeEmail">
						<input id="newEmail" type="text" placeholder="New email here" style="width:100%; padding:10px;">
						<button type="submit">Change</button>
					</form>
				</div>
				<a href="#" id="delete">Actually I want to delete my account</a>
			</p>
		</div>

	</div>

<script>
$(document).ready(function() {
   $("#resend").click(function(e){
		e.preventDefault();
		$.get( "/scripts/notConfirmed/resend" ,function( data ) {
			var jData = jQuery.parseJSON(data);
			var result = jData.result;
			if(result == 'successful')
			{
				bootbox.alert("A new activation email has been sent to your email");
			}
			else
			{
				var message = jData.message;
				bootbox.alert("Activation Email Resend Failed.<br>Reason: "+message);
			}
		}); 
   })
   $("#change").click(function(e){
	e.preventDefault();
	$("#changeEmailForm").toggle();
   })
   $("#changeEmail").submit(function(e){
		e.preventDefault();
		var newEmail = $("#newEmail").val();
		$.get( "/scripts/notConfirmed/change/"+newEmail ,function( data ) {
			var jData = jQuery.parseJSON(data);
			var result = jData.result;
			if(result == 'successful')
			{
				bootbox.alert("Your email has been changed. Check <strong>"+newEmail+"</strong> for your activation email.", function(){
					location.reload();
				});
				
			}
			else
			{
				var message = jData.message;
				bootbox.alert("Email Change Failed.<br>Reason: "+message);
			}
		});
   })
   
   $("#delete").click(function(e){
	e.preventDefault();
	bootbox.confirm("Are you sure you want to delete your account?<br><br>This action is permanent and cannot be undone", function(result){
		if(result)
		{
			$.get( "/scripts/notConfirmed/delete",function( data ) {
				var jData = jQuery.parseJSON(data);
				var result = jData.result;
				if(result == 'successful')
				{
					bootbox.alert("Your account has been deleted",function(){
						location.reload();
					});
				}
				else
				{
					var message = jData.message;
					bootbox.alert("Account Delete Failed.<br>Reason: "+message);
				}
			})
					
		}
	});
   })
	
});

</script>
<?php
footerHTML();
?>