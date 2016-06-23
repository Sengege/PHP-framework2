<?php
error_reporting(E_ALL);

require_once('scripts/prepend.php');
require_once('scripts/functions/functions.php');

// Start HTML
startHTML("Add Student",false);

$groupID = $_GET['group'];
echo $groupID;
?>

	
		<div class="container">
		<?php if($noerrors) { ?>
			<div class="alert alert-danger" role="alert">Connection to database failed</div>
			<?php	} ?>
			
			<h1>Add User to Group</h1>
			
			<div class="row">
					
							<form id="addToGroup" type="post" action="#">
							<div id="studentToAdd">
								<p>Choose Students to Add to Group</p>
								<select id="studentOptions" class="chosen-select"  multiple data-placeholder="Select Students">	

									<?php
								
									foreach($db->query("SELECT * FROM `students` ") AS $students)
									{
										echo '<option value="'.$students['studentID'].'">'.$students['first_name'].' '.$students['last_name'].'</option>';
									}
								?>
								</select>
							</div>

							<br>
								<button type="submitall">Add to Group</button>
								
							</form>
			</div>
			
			
		</div>
		
		<!-- Choosen jQuery -->
	<script src="js/chosen.jquery.js"></script>
	<script type="text/javascript"> 
		$( document ).ready(function() {
			$("#studentOptions").chosen({
				
				no_results_text: "Oops, nothing found!",
				width:"95%",
				min_selected_options: 1
			  });
		});
	</script>



    
	<script src="js/validation/jquery.validate.js"></script>
	<script>			
	$("#addToGroup").validate({
				rules: {
					studentToAdd: "required",
					
				},
				
				messages: {
				groupName: {
					studentToAdd: "Please select at least one Student"
				},					
				submitHandler: function(form) {
				// fill the json array
						$("#submitall").attr('disabled','disabled');
						console.log($( "#newGroup" ).serializeArray());
						
				//sort from down here
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
							bootbox.alert("<p>New Student(s) Added</p>",function() {
								location.reload();
							});
							
							}
						}
						
						
						
						
					})

			

				
				
				
		}
				}});
		
	</script>
<?php footerHTML(); ?>
