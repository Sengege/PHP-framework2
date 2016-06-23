<?php
global $db;
// $hashcode comes from PHP SLIM function
$findAccount = $db->prepare("SELECT * FROM `students` a INNER JOIN `hash_codes` b ON b.studentID = a.studentID WHERE b.code = :hashcode AND b.type= 'password'");
$findAccount->bindParam(":hashcode",$hashcode);
$findAccount->execute();
if($findAccount->rowCount() != 1)
{
	startHTML('Invalid Code',false);?>
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
          
          
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	<?php
	echo '<div style="text-align:center;"><h2>Reset password code invalid</h2></div>';
	footerHTML();
	return;
}

startHTML('Reset Password',false);
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
          
          
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	<div class="center-block">
		<h2 class="text-center">Reset your password</h2>
	
	
			<form id="changePassword" class="form-horizontal col-xs-12">
				<input type="hidden" name="hashcode" value="<?php echo $hashcode; ?>">
				<div class="form-group"> 
					<label class="col-md-4 control-label" for="newPassword">New Password</label>
					<div class="col-md-4">
						<input type="password" id="newPassword" name="newPassword" class="form-control input-md" >
					</div>
				</div>
				<div class="form-group"> 
					<label class="col-md-4 control-label" for="newPasswordConfirm">Confirm New Password</label>
					<div class="col-md-4">
						<input type="password" name="newPasswordConfirm" class="form-control input-md" >
					</div>
					<button type="submit" id="submitReset" class="btn btn-default" >Change</button>
					
				</div>
				
				
			</form>
		
	</div>
	<script>
	// 
	$( document ).ready(function() {
		$("#changePassword").validate({
			rules: { newPassword: { required: true, minlength: 6} , newPasswordConfirm:{ required: true, equalTo: "#newPassword", minlength: 6 }  },
			messages: { },
			submitHandler: function(form) {
					// fill the json array
					var data = $("#changePassword").serializeObject();
					var hashcode = data.hashcode;
					var newPassword = data.newPassword;
					var json = JSON.stringify({"hashcode":hashcode,"newPassword":newPassword});
					console.log(json);
					$("#submitReset").prop("disabled", true);
					$("#submitReset").html('<i class="fa fa-spinner fa-pulse"></i>');
					$.post("/scripts/student/resetPassword/set",json,function(data){
						 console.log(data);			
							var jData = jQuery.parseJSON(data)
							var result = jData.result;
							if(result == 'Successful')
							{
								bootbox.alert("Your password has been changed",function() { window.location.href = "/"; });								
							}
							else
							{
								var message = data.message;
								$("#submitReset").prop("disabled", false);
								$("#submitReset").text('Change');
								bootbox.alert("<p>Something went wrong</p><p>"+message+"</p>");
							}
					})
			}
		})
	});
		
	
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

	<?php footerHTML(); ?>

 
