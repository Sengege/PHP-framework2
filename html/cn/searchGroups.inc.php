<script>
var allGroups = <?php 

$groups = $db->prepare("SELECT * FROM `groups` g INNER JOIN `module` c ON c.moduleID = g.moduleID WHERE g.active = 1");
//$groups->execute(array($groups->groupID));
$groups->execute();
$allgroups = $groups->fetchAll();

//for each group get array of tags like I did in the assignedgroups function
//append to the object [`tags`]
    for($i = 0; $i<count($allgroups);$i++){
      $allgroups[$i]['tags'] = array();
      $groupTags = $db->prepare("SELECT * FROM `tag_group` d INNER JOIN  `tag` e ON e.TagID = d.tagID WHERE d.groupID = :groupID");
      $groupTags->bindParam(":groupID", $allgroups[$i]['groupID']);
      $groupTags->execute();
      $allTags = $groupTags->fetchAll();
      if(count($allTags) > 0){ 
        foreach($allTags as $tag){
            array_push($allgroups[$i]['tags'], $tag);
        }
      }
    }

echo json_encode($allgroups); ?>;
console.log(allGroups);

$(function(){
searchGroups(allGroups, '')
});

function searchGroups(source, name) {

    if (name.length >0){ $("#suggestedGroups").hide();} else { $("#suggestedGroups").show(); }
    
    var results = [];
    var entry;
    var TITLE = 3;
    var DESC = 4;
    
    //empty search results
    $('#searchGroups').empty();

    name = name.toUpperCase();
    for (var index = 0; index < source.length; ++index) {
        entry = source[index];
        try {
          if (entry[TITLE].toUpperCase().indexOf(name) !== -1 || entry[DESC].toUpperCase().indexOf(name) !== -1) {
            results.push(entry);
          }
        } catch (err) {}
    }
//show searchMyGroups panel
//hide other panels
    if(results.length == 0){
    $("#searchGroups").append("<p>抱歉，没有组与您搜索的组匹配</p>"); 
    }
    else{
    
    //start table append here
    $("#searchGroups").append("<div class='table-responsive'>\
			<table class='table'>\
				<thead>\
					<tr>\
						<th>小组名称</th>\
						<th>模块</th>\
						<th>标签</th>\
						<th>描述</th>\
					</tr>\
				</thead>\
				<tbody id='searchGroupDetails'>");

    $.each(results,function(s){
        var tagslist ="";
    if(	results[s]['tags'].length >0){
	    results[s]['tags'].forEach(function(tag){
            tagslist += tag['name_EN']; 
            tagslist += " ";
	    });
	    	}
		else{
		    tagslist += "没有标签分配给该组";
		}
    
    //append the entries in here
    $("#searchGroupDetails").append("<tr>\
		<td><a href='dashboard/group/" + results[s][0] + "'>" + results[s][3] + "</a></td>\
		</a></td>\
		<td>" + results[s][12] + "</td>\
		<td>" + tagslist + "</td>\
		<td>" + results[s][4] + "</td>\
		</tr>");
    });
    //end table append here
    $("#searchGroups").append("</tbody>\
			</table>\
		</div>");
    }

}
    </script>

<div class="container">

<div id="custom-search-input" class="col-md-6">
                <div class="input-group col-md-12">
                    <input type="text" class="form-control input-lg" placeholder="搜索小组" onkeyup="searchGroups(allGroups, this.value)" />
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
            </div>
            <button type="button" class="btn btn-default btn-md pull-right" data-toggle="modal" data-target="#addGroupModal">添加小组</button>
            <div class="clearfix"></div>
            <div id="suggestedGroups" class="panel panel-default" style="display:true;">
            <div class="panel-heading">建议小组</div>
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
						<th>小组名称</th>
						<th>模块</th>
						<th>标签</th>
						<th>描述</th>
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
		<td><?php
  $groupTags = $db->prepare("SELECT * FROM `Tag_Group` d INNER JOIN  `Tag` e ON e.TagID = d.TagID WHERE d.groupID = :groupID");
  $groupTags->bindParam(":groupID", $SuggestedGroup['groupID']);
  $groupTags->execute();
  if($groupTags->rowCount() > 0){ 
    foreach($groupTags->fetchAll(PDO::FETCH_ASSOC) as $tag){
      echo $tag['name_EN'];
      echo " ";
    }
  }
  else{
    echo "没有标签分配给该组";
  }
?></td>
		<td><?php echo $suggestedGroup['groupDescription']; ?></td>
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
            
            <div id="searchGroupsContainer" class="panel panel-default" >
            <div class="panel-heading">搜索小组</div>
            <div id="searchGroups" class="panel-body">
            </div>
            </div>
</div>