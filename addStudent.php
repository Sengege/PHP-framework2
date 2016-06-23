<?php
//include database connection
require_once('scripts/databaseConnect.php');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Test</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- jquery UI -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<style>
		.error { color:#f00; }
	</style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	
		<div class="container">
		
		<a href="index.php">Home</a>
		<?php if($noerrors) { ?>
			<div class="alert alert-danger" role="alert">Connection to database failed</div>
			<?php	} ?>
			
			<h1>Add Student</h1>

			
			
			<div class="row">
				<div id="stepOne">
					<h3>Step 1</h3>
							<form id="newStudent" type="post" action="#">
							
							<label>First Name</label>		
							<div class="input-group">
								<input type="text" name="fname" class="form-control" placeholder="First name here" value="nick">
							</div>
							<label>Last Name</label>		
							<div class="input-group">
								<input type="text" name="lname" class="form-control" placeholder="Last name here" value="hunt">
							</div>
							<label>Date of Birth</label>		
							<div class="input-group">
								<input type="text" name="DOB" id="DOB" class="form-control" value="25/02/2015">
							</div>
							
							<label>University</label>
							<br>
						  <!-- Example row of columns -->
						  
							<select name="university" style="padding:5px;display:block;">
								<option value="">Select University</option>
								<?php
									foreach($db->query("SELECT * FROM `university`") AS $university)
									{
										echo '<option value="'.$university['universityID'].'">'.$university['name'].'</option>';
									}
								?>
							</select>
								
								
							<br>
								
								
								<label>Email</label>		
								<div class="input-group">
									<input type="text" name="email" class="form-control" value="nick@strive.me.uk" >
								</div>
								
								<label>Username</label>		
								<div class="input-group">
									<input type="text" name="username" class="form-control" value="nick0488" >
								</div>
								
								<label>Password</label>		
								<div class="input-group">
									<input type="password" name="password" class="form-control" value="password">
								</div>
								
								<button type="submit">Next</button>
								
							</form>
				</div>
				<div id="stepTwo" style="display:none">
					<h3>Step 2</h3>
					<p>Please what subjects you are studying</p>
					<div id="moduleOptions">
					</div>
					<button type="button" id="submitAll" style="display:none;">Finish</button>
				</div>
			</div>
			
			
		</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/validation/jquery.validate.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script>
	
		var newStudent = [];
		
	
	// Date picker for DOB
			$( "#DOB" ).datepicker({ dateFormat: "dd/mm/yy", changeMonth: true, changeYear: true });
			
			// Adds alphanumeric rule
			jQuery.validator.addMethod("alphanumeric", function(value, element) {
				return this.optional(element) || /^\w+$/i.test(value);
			}, "Only letters, numbers and underscores are allowed");
			
			// Adds british time date format rule
			$.validator.addMethod(
				"britishDate",
				function(value, element) {
					// put your own logic here, this is just a (crappy) example
					return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
				},
				"Please enter a date in the format dd/mm/yyyy."
			);
			
	$("#newStudent").validate({
				rules: {
					fname: "required",
					lname: "required",
					university: "required",
					DOB : {
						required: true,
						britishDate: true
						
					},
					email: {
						required: true,
						email: true,
						/*remote: "/ajax/validation/check-email.ajax.php"*/
					},
					
					username: {
						required: true,
						minlength: 5,
						alphanumeric: true,
						/*remote: "/ajax/validation/check-username.ajax.php"*/
					},
					password: {
						required: true,
						minlength: 6
					}
					
					
				},
				
				messages: {
				university: {
					required: "Please select a university"
				},
					DOB: {
						required: "Please provide your Date of Birth"
					},
					
					password: {
						required: "Please provide a password",
						minlength: "Your password must be at least 6 characters long"
					},
					
					email: {
						required: "Please provide a email",
						
					},
					
					username: {
						required: "Please provide a username",
						minlength: "Your username must be at least 5 characters long"
						},
					
				},
				submitHandler: function(form) {
				
						$("#submit").attr('disabled','disabled');
						console.log($( "#newStudent" ).serializeArray());
						newStudent["studentData"] = $( "#newStudent" ).serializeArray();
						newStudent["university"] = $("#newStudent [name='university']").val();
						console.log(newStudent["university"]);
						changePage()
						
				}
				
				
		});

		function changePage()
		{
			$("#stepOne").hide();
			$("#stepTwo").show();
			/* collect module data depending on university selected */
			$.get( "scripts/modules/"+newStudent["university"], function( data ) {
							
							var jData = jQuery.parseJSON(data)
							var numberOfModules = jData.module_number;
							if(numberOfModules)
							{
								
								newStudent["modules"] = jData;
								addSelectFields();
							}
							
							else {
								alert( "something went wrong"); 
								}
			}) 
		}
		var displayedModules = 1;
		newStudent["selectedModules"] = [];
		
		function addSelectFields(){
			// get module data from local variable
			var modules = newStudent["modules"].modules;
		
			var html = '';
			html +='<p>Module <b>'+displayedModules+'</b> </p><select class="moduleSelected" style="padding:5px; display:block;">';
			html +='<option value="">Please Choose</option>';
			$.each( modules, function( key, value ) {
				html +='<option value="'+value.ID+'">'+value.module_name+' '+value.module_code+'</option>';
			});
			html +='</select><p></p>';
			$("#moduleOptions").append(html)
			displayedModules++;
		}
		
		$("#moduleOptions").on('change',".moduleSelected:last",function(e){
			var moduleSelectedValue = $(this).val();
			
			if (moduleSelectedValue != '')
			{
				// Add new field
				addSelectFields();
				if(displayedModules > 1 )
				{
					$("#submitAll").show();
				}
			}
		})
		
		
		$("#submitAll").click(function(e){
		
			//console.log($("#moduleOptions .moduleSelected").val());
			$( "#moduleOptions .moduleSelected" ).each(function( key, value ) {
				// Add selected modules to array
				if($(this).val() != '') {
					newStudent["selectedModules"].push($(this).val());
				}
				
				console.log(newStudent["selectedModules"]);
			});	
			
		})
	
		
	</script>
  </body>
</html>




