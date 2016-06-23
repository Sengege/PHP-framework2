<?php error_reporting(E_ALL);/*include database connection*/

require_once('scripts/prepend.php');

// If user is not logged in redirect to index.php
if(!$student->userExists()) { header('Location: index.php'); die(); } 

require_once('scripts/functions/functions.php');

/* Start HTML*/startHTML("Shall we Study?",false);

?>

	
		<div class="container">
		<?php if($noerrors) { ?>
			<div class="alert alert-danger" role="alert">Connection to database failed</div>
			<?php	} ?>
			
			<h1>Create Group</h1>

			
			
			<div class="row">
							<form id="newGroup" type="post" action="#">
							
							<label>Group Name</label>		
							<div class="input-group">
								<input type="text" name="groupName" class="form-control" placeholder="Group name here" >
							</div>				
							<br>
							<label>Group Description</label>		
							<div class="input-group">
								<input type="text" name="groupDescription" class="form-control" placeholder="Group Description here" >
							</div>
							<br>
						  <label>Relevant Module</label>
							<div class="input-group">
								<select type="text" id="moduleOptions" class="form-control" name="groupModule" placeholder="Select relevant module">
								<option value="">Select Module</option>
								<?php
								$university = $student->universityID;
								$school = $student->schoolID;
								$q = $db->prepare("SELECT * FROM `module` WHERE `schoolID` = :schoolID AND `universityID` = :universityID");
								$q->bindParam(':schoolID',$school);
								$q->bindParam(':universityID',$university);
								$q->execute();
									foreach($q->fetchAll() AS $modules)
									{
										
										echo '<option value="'.$modules['moduleID'].'">'.$modules['module_name'].'</option>';
									
									}
								?>
								</select>					
							</div>	
							<br>
							<label>Group Type</label>		
							<div class="input-group">
								<select type="text" name="groupType" class="form-control" placeholder="Group Type" >
								<option value="private">Private</option>
								<option value="public">Public</option>
								</select>
							</div>
								<br>
							<label>One Time Group</label>		
							<div class="input-group">
								<select type="text" name="oneOff" class="form-control" placeholder="Group Type" >
								<option value="true">Yes</option>
								<option value="false">No</option>
								</select>
							</div>
							<br>
								<button type="submitall">Create Group</button>
								
							</form>
			</div>
			
			
		</div>


   
    <!-- Include all compiled plugins (below), or include individual files as needed --> 
	<script src="js/validation/jquery.validate.js"></script>
	<script>

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
	
	$("#newGroup").validate({
				rules: {
					groupName: "required",
					groupModule: "required",
					groupDescription: "required",
					oneOff: "required",
					groupType: "required"
					
				},
				
				messages: {
				groupName: {
					required: "Please provide a group name"
				},					
				groupModule: {
					required: "Please select a module",
						
				},
				groupDescription: {
						required: "Please provide a group description"
				},
				oneOff: {
						required: "Please select yes or no"
					},
				gname: {
						required: "Please provide a group name"
					},
				
				},
				submitHandler: function(form) {
					// fill the json array
					$("#submitall").attr('disabled','disabled');
					var jsonObject = JSON.stringify($( "#newGroup" ).serializeObject());					
					console.log(jsonObject);
					$.post( "scripts/groups/add", jsonObject, function( data ) {
							console.log(data);
							var msg = '';
							var jData = jQuery.parseJSON(data)
							var result = jData.result;
							if(result)
							{
								if(result == 'successful')
								{
								bootbox.alert("<p>Thank you for signing up!</p>",function() {
									location.reload();
								});
								
								}
							}

						})
						
			

				
				
				
					}
	});
		
	</script>
<?php footerHTML(); ?>
