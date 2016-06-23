<?php
error_reporting(E_ALL);

require_once('scripts/prepend.php');
require_once('scripts/functions/functions.php');

// Start HTML
startHTML("Add Student",false);

?>

 
<div class="section white">
		<div class="container">
		
		<?php if($noerrors) { ?>
			<div class="alert alert-danger" role="alert">Connection to database failed</div>
		<?php	} ?>
		<div class="col-md-12">
			<h1>Add Student</h1>

			
			
				<div id="stepOne">
					
					<form id="newStudent" type="post" action="#">
					<input type="hidden" name="language"value="EN">
					<h3>Step 1</h3>
					<div class="col-md-6">
							
							
							<label>First Name</label>		
							<div class="input-group">
								<input type="text" name="first_name" class="form-control" placeholder="First name here" >
							</div>
							<label>Last Name</label>		
							<div class="input-group">
								<input type="text" name="last_name" class="form-control" placeholder="Last name here" >
							</div>
							<label>Date of Birth</label>		
							<div class="input-group">
								<input type="text" name="DOB" id="DOB" class="form-control">
							</div>
							
							<label>University</label>
							<br>
						  <!-- Example row of columns -->
						  
							<select name="university" id="university" style="padding:5px;display:block;">
								<option value="">Select University</option>
								<?php
									foreach($db->query("SELECT * FROM `university`") AS $university)
									{
										echo '<option value="'.$university['universityID'].'">'.$university['name'].'</option>';
									}
								?>
								<option value="notListed">Not Listed?</option>
							</select>
								
								
					</div>
					<div class="col-md-6">	
								
								<label>Email</label>		
								<div class="input-group">
									<input type="text" name="email" class="form-control"  >
								</div>
								
								<label>Username</label>		
								<div class="input-group">
									<input type="text" name="username" class="form-control"  >
								</div>
								
								<label>Password</label>		
								<div class="input-group">
									<input type="password" name="password" class="form-control" >
								</div>
								
								<button type="submit">Next</button>
								
							
					</div>
					</form>
				</div>
				
				<div id="stepTwo" style="display:none" >
					
					<h3>Step 2</h3>
					<div class="col-md-12">
						<div id="stepTwoContent" style="display:none;">
							<div id="schoolSection"  >
								<p>Choose your school</p>
								<select id="schoolOptions" name="school" data-placeholder="Select your school">
								</select>
							</div>
						
							<p></p>
						
							<div id="moduleSection"  style="display:none;">
								<p>Choose your Modules</p>
								<select id="moduleOptions" class="chosen-select"  multiple data-placeholder="Select your modules"></select>					
							</div>
							<p></p>
							<button type="button" onclick="changePage(1);" class="" id="back">Back</button>
						<button type="button" disabled="disabled" class="btn btn-primary" id="submitAll">Select at least 3 modules</button>
						</div>
					</div>
					
				</div>
			</div>

			
			
			
		</div>
</div>


	
	<!-- Choosen jQuery -->
	<script src="js/chosen.jquery.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

	<script type="text/javascript"> 
		$( document ).ready(function() {
			$("#schoolOptions").chosen({
				
				no_results_text: "Oops, nothing found!",
				width:"95%"
				
			  });
			$("#moduleOptions").chosen({
				
				no_results_text: "Oops, nothing found!",
				width:"95%",
				max_selected_options: 10
			  });
		});
	</script>
	
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    	
	<script>
	
		$( document ).ready(function() {
	
			var newStudent = [];
			newStudent["schools"];
			newStudent["schoolSelected"];
			newStudent["schoolModules"];
			newStudent["selectedModules"] = [];
			newStudent["universityChosen"] = false;
		
			// Date picker for DOB
			//$( "#DOB" ).datepicker({ dateFormat: "dd/mm/yy", changeMonth: true, changeYear: true });
			$( "#DOB" ).datepicker({ dateFormat: "dd/mm/yy", changeMonth: true, changeYear: true, minDate: "-80Y", maxDate: "-12Y", yearRange:"-80:+0" });
			
			// Adds alphanumeric rule
			$.validator.addMethod("alphanumeric", function(value, element) {
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
						first_name: "required",
						last_name: "required",
						university: "required",
						DOB : {
							required: true,
							britishDate: true
							
						},
						email: {
							required: true,
							email: true,
							remote: "/scripts/student/register/validate/email"
						},
						
						username: {
							required: true,
							minlength: 5,
							alphanumeric: true,
							remote: "scripts/student/register/validate/username"
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
							// Store Student Data in local variable
							newStudent["studentData"] = $( "#newStudent" ).serializeArray();
							var choosenUniversityID = $("#newStudent [name='university']").val();
							
							// If first time selecting university or changing university
							if(newStudent["universityChosen"] == false || (newStudent["universityChosen"] == true && newStudent["university"] != choosenUniversityID ))
							{
								console.log(choosenUniversityID)
								// Store university ID
								newStudent["university"] = choosenUniversityID;
								newStudent["universityChosen"] = true;
								storeModuleData(newStudent["university"]);
								changePage(2);
							}
							else
							{
								changePage(2);
							}
													
							
							
							
					}
					
					
			});
			
			
			
					
			/** CODE TO GET DATA **/
			function storeModuleData(universityID)
			{
				console.log(universityID);
				/* Download and store module JSON */
				$.get( "scripts/modules/"+universityID, function( data ) {
								
					var jData = jQuery.parseJSON(data)
					var validUniversity = jData.valid_university;
					var numberOfSchools = jData.school_number;
					if(validUniversity)
					{
						if(numberOfSchools == 0)
						{
							var universityName = jData.university_name;
							bootbox.alert("<p><strong>Notice!</strong><br/>"+universityName+" has no schools.</p>");
						}
						newStudent["schools"] = jData.schools;
						populateSchools();
					}
					else 
					{
						bootbox.alert("Something went wrong");
					}
				})
			}
			/**  --  **/
			
			
			/** Populates school field for chosen university **/
			function populateSchools()
			{
				resetStep2();
				
				// Clear School Options and repopulate
				$("#schoolOptions").empty();
				$("#schoolOptions").append('<option value="">Select School</option>');
				
				$.each( newStudent["schools"], function( key, value ) {
					console.log(value.name);
					html = '<option value="'+key+'">'+value.name+'</option>';
					$("#schoolOptions").append(html)
				})
				// Update chosen JS 
				$('#schoolOptions').trigger('chosen:updated');
				//Display stepTwoContent content when school form has loaded
				$("#stepTwoContent").show();
			}
			/**  --  **/
			
			function resetStep2()
			{
				// Resets step 2 defaults (if changing university)
				$("#stepTwoContent").hide();
				newStudent["selectedModules"] = [];
				newStudent["schoolModules"] = [];
				$("#moduleOptions").empty();
				$("#moduleOptions").trigger('chosen:updated');
				$("#moduleSection").hide();
				$("#submitAll").text('Select at least 3 modules');
				$("#submitAll").attr("disabled", true);
			}
			
			/** When school option has been chosen **/
			$("#schoolOptions").on('change',function(e){
				var schoolSelectedValue = $(this).val();
				
				if (schoolSelectedValue != '')
				{
					newStudent["schoolSelected"] = schoolSelectedValue;
					newStudent["schoolModules"] = newStudent["schools"][newStudent["schoolSelected"]].modules;
					// Reset and load modules
					$("#moduleSection").hide();
					loadModules();
				}
			})
			
			function loadModules()
			{
				$("#moduleOptions").empty();
				$.each( newStudent["schoolModules"], function( key, value ) {
					var html ='<option value="'+value.ID+'">'+value.module_name+' '+value.module_code+'</option>';
					$("#moduleOptions").append(html)
				});
				$('#moduleOptions').trigger('chosen:updated');
				$("#moduleSection").show()
			}
			
			// Display warning if changing university whilst having modules selected
			$("#university").on('change',function(e){
				if($(this).val() == 'notListed')
				{
					// Reset value to nothing
					$(this).val('');
					// Alert message
					bootbox.dialog({
					  message: "<p><b>STUDY WITH ME</b> is only available at selected universities.<br><br>Want to enroll your university? Click Enroll my university to find out how.</p>",
					  title: "<strong>University Not Listed?</strong>",
					  buttons: {
						success: {
						  label: "Enroll My University",
						  className: "btn-success",
						  callback: function() {
							
						  }
						},
						
						close: {
						  label: "OK",
						  className: "btn-primary",
						  callback: function() {
							
						  }
						}
					  }
					});
					
				}
				if(newStudent["universityChosen"] == true && $(this).val() != 'notListed' && newStudent["university"] != $(this).val() && newStudent["selectedModules"].length > 0 )
				{
					var previousUniversity = $("#university option[value='"+newStudent["university"]+"']").text();
					var newUniversity = $("#university option[value='"+$(this).val()+"']").text();
					bootbox.dialog({
						  message: "<p><strong>Warning</strong><br>You have already choosen modules for <strong>"+previousUniversity+"</strong>. If you proceed to the next page with <strong>"+newUniversity+"</strong> your selected modules will be lost.</p>",
						  title: "<strong>University Change</strong>",
						  buttons: {
							success: {
							  label: "Revert back to "+previousUniversity,
							  className: "btn-primary",
							  callback: function() {
								$("#university").val(newStudent["university"]);
							  }
							},
							
							close: { label: "Continue",  className: "btn-danger", }
						  }
						});
					}
			})
			
			$("#moduleSection").on('change',"#moduleOptions",function(e){
				// Get selected modules
				var moduleSelectedValue = $(this).val();
				console.log(moduleSelectedValue);
				// Add select modules to array
				newStudent["selectedModules"] = moduleSelectedValue;
				
				if(moduleSelectedValue != null)
				{
					if(moduleSelectedValue.length > 2 )
					{
						$("#submitAll").text('Finish');
						$("#submitAll").attr("disabled", false);
					}
				
					else
					{
						$("#submitAll").text('Select at least 3 modules');
						$("#submitAll").attr("disabled", true);
					}
				}
				
			})
			
			function convertToObject()
			{
				var json = {};
				console.log(newStudent["studentData"]);
				$.each(newStudent["studentData"], function( key, value ) {
					json[value.name] = value.value;
				})
				// Gets school ID from selected school
				json["school"] = newStudent["schools"][newStudent["schoolSelected"]].ID;
				json["studying"] = newStudent["selectedModules"];
				
				return JSON.stringify(json);
				
			} 
			
			
			$("#submitAll").click(function(e){
				
				var jsonObject = convertToObject();
				console.log(jsonObject);
				$.post( "scripts/student/register/", jsonObject, function( data ) {
						console.log(data);
						var msg = '';
						var jData = jQuery.parseJSON(data)
						var result = jData.result;
						if(result)
						{
							if(result == 'successful')
							{
							bootbox.alert("<p>Thank you for signing up!</p>",function() {
								window.location.href = "/dashboard.php";
							});
							
							}
						}
						
						
						
						
					})

			})
	
	});	
	// Used to change page
			function changePage(x)
			{
				if (x == 2){
					$("#stepOne").hide(); 
					$("#stepTwo").show();
				}
				else if (x == 1)
				{
					$("#stepOne").show(); 
					$("#stepTwo").hide();
				}
			}
	</script>

 <?php footerHTML(); ?>