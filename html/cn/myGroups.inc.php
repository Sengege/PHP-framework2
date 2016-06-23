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
    $("#searchMyGroups").append("<p>抱歉，没有组与您搜索的组匹配!</p>"); 
    }
    else{
    
    //start table append here
    $("#searchMyGroups").append("<div class='table-responsive'>\
			<table class='table'>\
				<thead>\
					<tr>\
						<th>小组名</th>\
						<th>主题</th>\
						<th>标签</th>\
						<th>描述</th>\
					</tr>\
				</thead>\
				<tbody id='searchMyGroupDetails'>");

    $.each(results,function(s){
    //prepare tags field
    var tags ="";
    if(	results[s][`tags`].length >0){
	    results[s][`tags`].forEach(function(tag){
            tags += tag; 
            tags += " ";
	    });
	    	}
		else{
		    tags += "No tags assigned to this group";
		}
    
    //append the entries in here, note that you have to append to tbody
    $("#searchMyGroupDetails").append("<tr>\
		<td><a href='dashboard/group/" + results[s][0] + "'>" + results[s][6] + "</a></td>\
		</a></td>\
		<td>" + results[s][15] + "</td><td>" + tags + "</td><td>" + results[s][7] + "</td>\
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
                    <input type="text" class="form-control input-lg" placeholder="搜索我的小组" onkeyup="searchMyGroups(allMyGroups,this.value)" />
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
                </div>
<button type="button" class="btn btn-default btn-md pull-right" data-toggle="modal" data-target="#addGroupModal">添加小组</button>
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
		<div class="panel-heading">我创建的小组</div><!-- /.panel-heading -->
		<div class="panel-body">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>小组名</th>
						<th>主题</th>
						<th>标签</th>
						<th>创建时间</th>
						<th>成员数</th>
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
		<td>
<?php
  $groupTags = $db->prepare("SELECT * FROM `Tag_Group` d INNER JOIN  `Tag` e ON e.TagID = d.TagID WHERE d.groupID = :groupID");
  $groupTags->bindParam(":groupID", $group['groupID']);
  $groupTags->execute();
  if($groupTags->rowCount() > 0){ 
    foreach($groupTags->fetchAll(PDO::FETCH_ASSOC) as $tag){
      echo $tag['name_EN'];
      echo " ";
    }
  }
  else{
    echo "该小组没有指定任何标签";
  }
?></td>
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
		<div class="panel-heading">我加入的小组</div><!-- /.panel-heading -->
		<div class="panel-body">

<?php 
	if($joinedGroups->rowCount() == 0)
	{	echo '<p>你还没有加入任何小组</p>'; }
	else
	{
?>


		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>小组名</th>
						<th>主题</th>
						<th>标签</th>
						<th>加入时间</th>
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
		<td><?php
  $groupTags = $db->prepare("SELECT * FROM `Tag_Group` d INNER JOIN  `Tag` e ON e.TagID = d.TagID WHERE d.groupID = :groupID");
  $groupTags->bindParam(":groupID", $group['groupID']);
  $groupTags->execute();
  if($groupTags->rowCount() > 0){ 
    foreach($groupTags->fetchAll(PDO::FETCH_ASSOC) as $tag){
      echo $tag['name_EN'];
      echo " ";
    }
  }
  else{
    echo "No tags assigned to this group";
  }
?></td>
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
		<div class="panel-heading">结束的小组</div><!-- /.panel-heading -->
		<div class="panel-body">

<?php 
	if($previousGroups->rowCount() == 0)
	{	echo '<p>你所有的小组都是活跃状态!</p>'; }
	else
	{
?>


		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>小组名</th>
						<th>主题</th>
						<th>标签</th>
						<th>加入时间</th>
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
		<td>
		<?php
  $groupTags = $db->prepare("SELECT * FROM `Tag_Group` d INNER JOIN  `Tag` e ON e.TagID = d.TagID WHERE d.groupID = :groupID");
  $groupTags->bindParam(":groupID", $group['groupID']);
  $groupTags->execute();
  if($groupTags->rowCount() > 0){ 
    foreach($groupTags->fetchAll(PDO::FETCH_ASSOC) as $tag){
      echo $tag['name_EN'];
      echo " ";
    }
  }
  else{
    echo "No tags assigned to this group";
  }
?>
</td>
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
	<div class="panel-heading">搜索我的小组结果</div><!-- /.panel-heading -->
		<div class="panel-body" id="searchMyGroups">
		
		</div>
	</div>

<!-- Animated Notifications
<div id="notifications">
  
  <div id="notifications-bottom-center"></div>
  <div id="notifications-bottom-right"></div>

<div id="notifications-window">
    <div class="notifications-window-row" style="margin-top:85px;">
      <select class="dropdown" id="position">
        <option value="top">Top</option>
        <option value="center">Center</option>
        <option value="bottom">Bottom Center</option>
        <option value="botom_right">Bottom Right</option>
      </select>
</div>
    <div class="notifications-window-row">
      <select class="dropdown" id="effects">
       <optgroup label="Attention Seekers">
          <option value="bounce">bounce</option>
          <option value="flash">flash</option>
          <option value="pulse">pulse</option>
          <option value="rubberBand">rubberBand</option>
          <option value="shake">shake</option>
          <option value="swing">swing</option>
          <option value="tada">tada</option>
          <option value="wobble">wobble</option>
        </optgroup>

        <optgroup label="Bouncing Entrances">
          <option value="bounceIn">bounceIn</option>
          <option value="bounceInDown">bounceInDown</option>
          <option value="bounceInLeft">bounceInLeft</option>
          <option value="bounceInRight">bounceInRight</option>
          <option value="bounceInUp">bounceInUp</option>
        </optgroup>

        <optgroup label="Bouncing Exits">
          <option value="bounceOut">bounceOut</option>
          <option value="bounceOutDown">bounceOutDown</option>
          <option value="bounceOutLeft">bounceOutLeft</option>
          <option value="bounceOutRight">bounceOutRight</option>
          <option value="bounceOutUp">bounceOutUp</option>
        </optgroup>

        <optgroup label="Fading Entrances">
          <option value="fadeIn">fadeIn</option>
          <option value="fadeInDown">fadeInDown</option>
          <option value="fadeInDownBig">fadeInDownBig</option>
          <option value="fadeInLeft">fadeInLeft</option>
          <option value="fadeInLeftBig">fadeInLeftBig</option>
          <option value="fadeInRight">fadeInRight</option>
          <option value="fadeInRightBig">fadeInRightBig</option>
          <option value="fadeInUp">fadeInUp</option>
          <option value="fadeInUpBig">fadeInUpBig</option>
        </optgroup>

        <optgroup label="Fading Exits">
          <option value="fadeOut">fadeOut</option>
          <option value="fadeOutDown">fadeOutDown</option>
          <option value="fadeOutDownBig">fadeOutDownBig</option>
          <option value="fadeOutLeft">fadeOutLeft</option>
          <option value="fadeOutLeftBig">fadeOutLeftBig</option>
          <option value="fadeOutRight">fadeOutRight</option>
          <option value="fadeOutRightBig">fadeOutRightBig</option>
          <option value="fadeOutUp">fadeOutUp</option>
          <option value="fadeOutUpBig">fadeOutUpBig</option>
        </optgroup>

        <optgroup label="Flippers">
          <option value="flip">flip</option>
          <option value="flipInX">flipInX</option>
          <option value="flipInY">flipInY</option>
          <option value="flipOutX">flipOutX</option>
          <option value="flipOutY">flipOutY</option>
        </optgroup>

        <optgroup label="Lightspeed">
          <option value="lightSpeedIn">lightSpeedIn</option>
          <option value="lightSpeedOut">lightSpeedOut</option>
        </optgroup>

        <optgroup label="Rotating Entrances">
          <option value="rotateIn">rotateIn</option>
          <option value="rotateInDownLeft">rotateInDownLeft</option>
          <option value="rotateInDownRight">rotateInDownRight</option>
          <option value="rotateInUpLeft">rotateInUpLeft</option>
          <option value="rotateInUpRight">rotateInUpRight</option>
        </optgroup>

        <optgroup label="Rotating Exits">
          <option value="rotateOut">rotateOut</option>
          <option value="rotateOutDownLeft">rotateOutDownLeft</option>
          <option value="rotateOutDownRight">rotateOutDownRight</option>
          <option value="rotateOutUpLeft">rotateOutUpLeft</option>
          <option value="rotateOutUpRight">rotateOutUpRight</option>
        </optgroup>

        <optgroup label="Specials">
          <option value="hinge">hinge</option>
          <option value="rollIn">rollIn</option>
          <option value="rollOut">rollOut</option>
        </optgroup>

        <optgroup label="Zoom Entrances">
          <option value="zoomIn">zoomIn</option>
          <option value="zoomInDown">zoomInDown</option>
          <option value="zoomInLeft">zoomInLeft</option>
          <option value="zoomInRight">zoomInRight</option>
          <option value="zoomInUp">zoomInUp</option>
        </optgroup>

        <optgroup label="Zoom Exits">
          <option value="zoomOut">zoomOut</option>
          <option value="zoomOutDown">zoomOutDown</option>
          <option value="zoomOutLeft">zoomOutLeft</option>
          <option value="zoomOutRight">zoomOutRight</option>
          <option value="zoomOutUp">zoomOutUp</option>
        </optgroup>
      </select>
      </div>
    <div class="notifications-window-row" style="margin-top:60px;">
    <button id="notifications-window-row-button" class="btn btn-info btn-lg" type="button">Submit</button>
    </div>
</div>

</div> -->

   
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
					required: "请提供一个小组名"
				},					
				groupModule: {
					required: "请选择一个模块",
						
				},
				groupDescription: {
						required: "请输入小组简介"
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
								bootbox.alert("<p>新的小组创建成功</p>",function() {
									location.reload();
								});
								
								}
							}

						})
				
					}
	});
		
	</script>
</div><!-- end of container -->