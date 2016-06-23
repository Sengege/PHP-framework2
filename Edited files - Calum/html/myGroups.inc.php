<script>
var allMyGroups = <?php echo json_encode($student->assignedGroups()); ?>;
console.log(allMyGroups);


function searchMyGroups(source, name) {

    if (name.length ==0){
    $("#adminGroups").show();
    $("#membershipGroups").show();
    $("#defunctGroups").show();
    $("#searchMyGroupsContainer").hide();
    
    }
    else{
    
    $("#adminGroups").hide();
    $("#membershipGroups").hide();
    $("#defunctGroups").hide();
    $("#searchMyGroupsContainer").show();
    
    var results = [];
    var index;
    var entry;

    //empty search results
    $('#searchMyGroups').empty();

    name = name.toUpperCase();
    for (index = 0; index < source.length; ++index) {
        entry = source[index];
        if (entry && entry[6] && entry[6].toUpperCase().indexOf(name) !== -1) {
            results.push(entry);
        }
    }
//show searchMyGroups panel
//hide other panels
    if(results.length == 0){
    $("#searchMyGroups").append("<p>Sorry none of your groups match that search!</p>"); 
    }
    else{
    
    //start table append here
    $("#searchMyGroups").append("<div class='table-responsive'>\
			<table class='table'>\
				<thead>\
					<tr>\
						<th>Group Name</th>\
						<th>Module</th>\
						<th>Description</th>\
					</tr>\
				</thead>\
				<tbody id='searchMyGroupDetails'>");

    $.each(results,function(s){
    //append the entries in here, note that you have to append to tbody
    $("#searchMyGroupDetails").append("<tr>\
		<td><a href='dashboard/group/" + results[s][0] + "'>" + results[s][6] + "</a></td>\
		</a></td>\
		<td>" + results[s][15] + "</td>\
		<td>" + results[s][7] + "</td>\
		</tr>");
    });
    //end table append here
    $("#searchMyGroups").append("</tbody>\
			</table>\
		</div>");
    }
}
}
    </script>

<div class="container">


<div id="custom-search-input" class="col-md-6">
                <div class="input-group col-md-12">
                    <input type="text" class="form-control input-lg" placeholder="Search My Groups" onkeyup="searchMyGroups(allMyGroups,this.value)" />
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
                </div>
<button type="button" class="btn btn-default btn-md pull-right" data-toggle="modal" data-target="#addGroupModal">Add Group</button>
<div class="clearfix"></div>
<?php

// Get all groups assigned to
$adminGroups = $db->prepare("SELECT *,(SELECT COUNT(*) FROM `group_membership` b WHERE b.groupID = a.groupID) AS members FROM `groups` a INNER JOIN `module` c ON a.moduleID = c.moduleID WHERE a.adminID = :studentID AND a.active='1'");
$joinedGroups = $db->prepare("SELECT * FROM `group_membership` a INNER JOIN `groups` b ON a.groupID = b.groupID INNER JOIN `module` c ON b.moduleID = c.moduleID WHERE a.studentID = :studentID AND b.adminID != :studentID AND b.active='1'");
$previousGroups = $db->prepare("SELECT * FROM `group_membership` a INNER JOIN `groups` b ON a.groupID = b.groupID INNER JOIN `module` c ON b.moduleID = c.moduleID WHERE a.studentID = :studentID AND b.active='0'");

$adminGroups->bindParam(":studentID",$student->userID);
$joinedGroups->bindParam(":studentID",$student->userID);
$previousGroups->bindParam(":studentID",$student->userID);
$adminGroups->execute();
$joinedGroups->execute();
$previousGroups->execute();



if($adminGroups->rowCount() > 0)
{
?>
	<div id="adminGroups" class="panel panel-default">
		<div class="panel-heading">Group Admin</div><!-- /.panel-heading -->
		<div class="panel-body">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Module</th>
						<th>Created</th>
						<th>Members</th>
					</tr>
				</thead>
				<tbody>
					
<?php
	foreach($adminGroups->fetchAll() AS $group)
	{
?>
	<tr>
		<td><a href="dashboard/group/<?php echo $group['groupID']; ?>"> <?php echo $group['groupName']; ?></a></td>
		<td><?php echo $group['module_name']; ?></td>
		<td><?php echo date("l jS M Y",strtotime($group['createdDate'])); ?></td>
		<td><?php echo $group['members']; ?></td>
	</tr>
	
<?php	
	
	}
	?>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<!-- /.panel-body -->
	
</div>
<?php
}
?>
<!-- Groups Joined -->
	<div id="membershipGroups" class="panel panel-default">
		<div class="panel-heading">Group Membership</div><!-- /.panel-heading -->
		<div class="panel-body">

<?php 
	if($joinedGroups->rowCount() == 0)
	{	echo '<p>You are not a member of any group</p>'; }
	else
	{
?>


		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Module</th>
						<th>Joined</th>
					</tr>
				</thead>
				<tbody>
					
<?php
		foreach($joinedGroups->fetchAll() AS $group)
		{
?>
	<tr>
		<td><a href="dashboard/group/<?php echo $group['groupID']; ?>"> <?php echo $group['groupName']; ?></a></td>
		<td><?php echo $group['module_name']; ?></td>
		<td><?php echo date("l jS M Y",strtotime($group['dateJoined'])); ?></td>
		
	</tr>
	
<?php	
	
		}
		?>
		</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
<?php
	}
	?>
				
	</div>
	<!-- /.panel-body -->
</div>


<!-- Old groups -->
	<div id="defunctGroups" class="panel panel-default">
		<div class="panel-heading">Defunct Groups</div><!-- /.panel-heading -->
		<div class="panel-body">

<?php 
	if($previousGroups->rowCount() == 0)
	{	echo '<p>All your groups are still active!</p>'; }
	else
	{
?>


		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Module</th>
						<th>Joined</th>
					</tr>
				</thead>
				<tbody>
					
<?php
		foreach($previousGroups->fetchAll() AS $group)
		{
?>
	<tr>
		<td><a href="dashboard/group/<?php echo $group['groupID']; ?>"> <?php echo $group['groupName']; ?></a></td>
		<td><?php echo $group['module_name']; ?></td>
		<td><?php echo date("l jS M Y",strtotime($group['dateJoined'])); ?></td>
		
	</tr>
	
<?php	
	
		}
		?>
		</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
<?php
	}
	?>
			
</div>			
	</div>
	<!-- /.panel-body -->
	<div id="searchMyGroupsContainer" class="panel panel-default" style="display:none;">
	<div class="panel-heading">Search My Groups Results</div><!-- /.panel-heading -->
		<div class="panel-body" id="searchMyGroups">
		
		</div>
	</div>

   
    <!-- Include all compiled plugins (below), or include individual files as needed --> 
	<script src="js/validation/jquery.validate.js"></script>
	<script src="/ckeditor/ckeditor.js"></script>
	<script src="/ckeditor/adapters/jquery.js"></script>
	<script>
	/* script for update personal details */
	$( document ).ready(function() {
		var editor = $( 'textarea#groupDescription' ).ckeditor();
	})
	</script>
	<script src="/js/chosen.jquery.js"></script>
	<script type="text/javascript"> 
		$( document ).ready(function() {
			$("#moduleOptions").chosen({
				
				no_results_text: "Oops, nothing found!",
				width:"95%"
				
			  });
			  $("#studentOptions").chosen({
				
				no_results_text: "Oops, nothing found!",
				width:"95%"
				
			  });
		});
	</script>
	
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
	
	$("#newGroupForm").validate({
				ignore: [],
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
				errorPlacement: function(error, element) 
				{ 
					if (element.attr("name") == "groupModule")  { error.insertAfter("#groupModuleMessage"); } 
					else if (element.attr("name") == "groupDescription")  { error.insertAfter("#groupDescriptionMessage"); } 
					else { error.insertAfter(element); }
				},
				submitHandler: function(form) {
					// fill the json array
					$("#submitall").attr('disabled','disabled');
					var jsonObject = JSON.stringify($( "#newGroupForm" ).serializeObject());					
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
								bootbox.alert("<p>New group added</p>",function() {
									location.reload();
								});
								
								}
							}

						})
						
			

				
				
				
					}
	});
		
	</script>
</div><!-- end of container -->