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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootbox.min.js"></script>
  </head>
  <body>
		<div class="container">
			<h1>Study with Me</h1>

			<?php if(!$noerrors) { ?>
			<div class="alert alert-success" role="alert">Connected to database</div>
			<?php	} else { ?>
			<div class="alert alert-danger" role="alert">Connection to database failed</div>
			<?php } ?>
			
			<div class="row">
				<div class="col-md-6">
					<h2>Modules</h2>
					<select multiple="multiple" style="height:200px; width:100%;">
						<?php
						$query = $db->query("SELECT * FROM `modules`");
						foreach($query AS $row)
						{
							echo '<option value="'.$row['moduleID'].'">'.$row['module_code'].' - '.$row['module_name'].'</option>';
						}
						?>
					</select>
				</div>
				<div class="col-md-6">
					<h2>Rooms</h2>
					<select multiple="multiple" style="height:200px; width:100%;">
						<?php
						$query = $db->query("SELECT * FROM `rooms`");
						foreach($query AS $row)
						{
							echo '<option value="'.$row['roomID'].'">'.$row['room_number'].' - ('.$row['seat_capacity'].' seats)</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="row">
				<h2>Examples</h2>
				<a href="addStudent.php">Add New Student</a>
			</div>
<div class="row">
	<h2>JSON Object Examples</h2>
	
	<div class="col-md-6">
	<p><b>Student Registration Object</b></p>
	<p>Modify the data object below and click <b>Send data to server</b>. </p>

	<pre id="studentRegistrationObject" contentEditable="true">
	{  
		"first_name":"Susan",
		"last_name":"Boyle",
		"DOB":"1992-01-04 00:00:00",
		"email":"s.boyle@hotmail.co.uk",
		"username":"SBoyle",
		"password":"PzassWordA1",
		"university":"1",
		"matric_number":"40003434",
		"studying":[  
			"2",
			"5",
			"57"
		]
	}
	</pre>
	<button id="sendUserRegistration" class="btn btn-default">Send Data to Server</button>
	<script type="text/javascript">
		$(document).ready(function() {
			
			$("#sendUserRegistration").click(function(e){
				var object = $("#studentRegistrationObject").text();
				$.post( "scripts/student/register/", object, function( data ) {
					var msg = '';
					console.log(data);
					var jData = jQuery.parseJSON(data)
					var result = jData.result;
					if(result)
					{
						if(result == 'successful')
						{
						 msg = "<p>Student has been added. Refresh page to see student in list of students</p>";
						}
					}
					
					bootbox.alert("<p><b>Data returned by server</b></p><p>"+data+"</p>"+msg);
					
					
				})
			})
		})
	</script>
	</div>
	
	<div class="col-md-6">
	<p>Group Registration Object with pre-set members<br>Will be sent to server to be processed</p>			
	<pre>
	{  
	   "group_name":"Parallel Systems Study",
	   "module_ID":"5",
	   "type":"public",
	   "description":"Study group to help with coursework etc",
	   "preset_members":[  
		  "1",
		  "5"
		]
	}
	
	
	</pre>
	</div>
	
</div>

<div class="row">
	<h2>List of students</h2>
	<table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Username</th>
		  <th>Email</th>
        </tr>
      </thead>
      <tbody>
		<?php
		$query = $db->query("SELECT * FROM `students`");
		foreach($query AS $row)
		{ ?>
		<tr>
          <th scope="row"><?php echo $row['studentID']; ?></th>
          <td><?php echo $row['first_name']; ?></td>
		  <td><?php echo $row['last_name']; ?></td>
		  <td><?php echo $row['username']; ?></td>
		  <td><?php echo $row['email']; ?></td>
      
        </tr>
		<?php } ?>
        
        
      </tbody>
    </table>
</div>
		</div>


    
  </body>
</html>




