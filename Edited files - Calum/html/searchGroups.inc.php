<script>
var allGroups = <?php 

$groups = $db->prepare("SELECT * FROM `groups` g INNER JOIN `module` c ON c.moduleID = g.moduleID");
$groups->execute(array($groups->groupID));

$allgroups = $groups->fetchAll();
echo json_encode($allgroups); ?>;
console.log(allGroups);


function searchGroups(source, name) {

    if (name.length ==0){
    $("#suggestedGroups").show();
    $("#searchGroupsContainer").hide();
    
    }
    else{
    $("#suggestedGroups").hide();
    $("#searchGroupsContainer").show();
    
    var results = [];
    var index;
    var entry;

    //empty search results
    $('#searchGroups').empty();

    name = name.toUpperCase();
    for (index = 0; index < source.length; ++index) {
        entry = source[index];
        if (entry && entry[3] && entry[3].toUpperCase().indexOf(name) !== -1) {
            results.push(entry);
        }
    }
//show searchMyGroups panel
//hide other panels
    if(results.length == 0){
    $("#searchGroups").append("<p>Sorry no groups match that search!</p>"); 
    }
    else{
    
    //start table append here
    $("#searchGroups").append("<div class='table-responsive'>\
			<table class='table'>\
				<thead>\
					<tr>\
						<th>Group Name</th>\
						<th>Module</th>\
						<th>Description</th>\
					</tr>\
				</thead>\
				<tbody id='searchGroupDetails'>");

    $.each(results,function(s){
    //append the entries in here
    $("#searchGroupDetails").append("<tr>\
		<td><a href='dashboard/group/" + results[s][0] + "'>" + results[s][3] + "</a></td>\
		</a></td>\
		<td>" + results[s][12] + "</td>\
		<td>" + results[s][4] + "</td>\
		</tr>");
    });
    //end table append here
    $("#searchGroups").append("</tbody>\
			</table>\
		</div>");
    }
}
}
    </script>

<div class="container">

<div id="custom-search-input" class="col-md-6">
                <div class="input-group col-md-12">
                    <input type="text" class="form-control input-lg" placeholder="Search Groups" onkeyup="searchGroups(allGroups, this.value)" />
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
            </div>
            <button type="button" class="btn btn-default btn-md pull-right" data-toggle="modal" data-target="#addGroupModal">Add Group</button>
            <div class="clearfix"></div>
            <div id="suggestedGroups" class="panel panel-default" style="display:true;">
            <div class="panel-heading">Suggested Groups</div>
            <div class="panel-body">
            <?php
            $suggestedGroups = $student->suggestedGroups();

	        if($suggestedGroups == '')
	        {	echo '<p>There are currently no suggested Groups suitable for you!</p>'; }
        	else
        	{
            ?>


		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Module</th>
					</tr>
				</thead>
				<tbody>
					
<?php
		foreach($suggestedGroups as $suggestedGroup)
		{
?>
	<tr>
		<td><a href="dashboard/group/<?php echo $suggestedGroup['groupID']; ?>"> <?php echo $suggestedGroup['groupName']; ?></a></td>
		<td><?php echo $suggestedGroup['module_name']; ?></td>
		
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
            
            <div id="searchGroupsContainer" class="panel panel-default" style="display:none;">
            <div class="panel-heading">Search Groups</div>
            <div id="searchGroups" class="panel-body">
            </div>
            </div>
</div>