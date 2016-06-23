<!-- Modal -->
<div class="modal fade" id="addGroupModal" taminsex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <div id="standardAddGroup">
	<form id="newGroupForm" type="get" action="#">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加小组</h4>
      </div>
      <div class="modal-body">
        
							
							<label>小组名字</label>		
							
								<input type="text" name="groupName" class="form-control" placeholder="请输入小组名字" >
										
							<br>
							<label>小组简介</label><br>		
							
								<textarea id="groupDescription" name="groupDescription" class="form-control" placeholder="请输入小组简介信息" ></textarea>
								<div id="groupDescriptionMessage"></div>
							<br>
						  <label>相关模块</label><br>
								<select type="text" id="moduleOptions" class="form-control" name="groupModule" placeholder="请选择相关模块">
									<option value="">选择一个模块</option>
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
								<div id="groupModuleMessage"></div>								
								
							<br>
							<label>标签类别(可选择)</label>
								<select type="text" id="groupCategoryTags" class="form-control" name="groupTags" placeholder="选择标签类别">
								<option value="">标签类别</option>
								<?php
							    $q = $db->prepare("SELECT * FROM `tag_category`");
								$q->execute();
									foreach($q->fetchAll() AS $tagCategories)
									{
										
										echo '<option value="'.$tagCategories['tag_categoryID'].'">'.$tagCategories['name_CN'].'</option>';
									
									}
								?>
								</select>					
							<br>
								<div id="tagSection" >
								<label>添加标签 (可选择)</label>
								<br>
								<select id="tagOptions" class="chosen-select" name="tags" multiple data-placeholder="选择你的标签"></select>
								<br>
						        <a href="#newtag" id="newTag">+新标签</a>
							</div>
								<br>
							<label>小组类型</label>		
							<div class="input-group">
								<select type="text" name="groupType" class="form-control" placeholder="小组类型" >
									<option value="public">公开</option>
									<option value="private">私有</option>
								</select>
							</div>
								<br>
						
							<label>添加学生 (可选择)</label>
								<select type="text" id="studentOptions" class="form-control" name="students" multiple placeholder="添加学生">
									
									<?php
									
									$s = $db->prepare("SELECT * FROM `students` WHERE `schoolID` = :schoolID AND `universityID` = :universityID AND `studentID` != :studentID");
									$s->bindParam(':schoolID',$student->schoolID);
									$s->bindParam(':universityID',$student->universityID);
									$s->bindParam(':studentID',$student->userID);
									$s->execute();
										foreach($s->fetchAll() AS $students)
										{
											
											echo '<option value="'.$students['studentID'].'">'.$students['first_name'].' '.$students['last_name'].' ('.$students['username'].')</option>';
										
										}
									?>
								</select>
								
							
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="submit" class="btn btn-primary">添加小组</button>
      </div>
	  </form>
	  </div>
	  <div style="display:none;" id="addTag">
	 
							<form id="newTagForm" type="get" action="#">
	                        
	                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                             <h4 class="modal-title" id="myModalLabel">新建标签</h4>
                             </div>
                               <div class="modal-body">
	                        
							<label>新标签</label>		
	                        <input type="text" id="tagcategory" name="tagCat" placeholder="标签类别" style="display:none;" value="">
							<input type="text" name="tagName" class="form-control" placeholder="新标签" >
	                        <br>
							<button id="submitTag" type="submitTag" class="btn btn-primary">添加标签</button>
	                        </div>
							</form>
							</div>
    </div>
  </div>
</div>

	<!-- Choosen jQuery -->
	<script src="js/chosen.jquery.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

	<script type="text/javascript"> 
	
		$( document ).ready(function() {
			$("#tagOptions").chosen({
				
				no_results_text: "Oops, nothing found!",
				width:"95%",
				max_selected_options: 5
			  });
			$("#tagOptions").chosen({
				
				no_results_text: "Oops, nothing found!",
				width:"95%"
				
			  });
			  
			  
			    
			  $("#newTag").click(function() {
             $("#addTag").show();
             $("#standardAddGroup").hide();
			});
			});

	</script>

<script>

		var displayedTags = 1;
		var Tags = [];
        
$('#groupCategoryTags').change(function(){
getSelectTags();
$("#tagcategory").attr('value',$("#groupCategoryTags").val());
})

function getSelectTags(){
 var categoryID = $("#groupCategoryTags").val();
console.log(categoryID);
    $.get( "/scripts/tags/"+categoryID, function( data ) {
						
		var jData = jQuery.parseJSON(data);
			console.log(jData);
	    Tags = jData;
		addSelectTags();
	}) 
}

function addSelectTags(){
			// get tag data from local variable
			var selectedTags = [];
			
			$("#tagOptions").empty();
				$("#tagOptions").append('<option value="">选择标签</option>');
				
				$.each( Tags, function( key, value ) {
					console.log(value);
					html = '<option value="'+value.ID+'">'+value.Name+'</option>';
					$("#tagOptions").append(html)
				})
				// Update chosen JS 
				$('#tagOptions').trigger('chosen:updated');
				$("#tagSection").show()
			
		}
		
		
		$("#tagOptions").on('change',".tagSelected:last",function(e){
			var tagSelectedValue = $(this).val();
			
			    if (tagSelectedValue != '')
			    {
			    // Add new field
			    addSelectTags();
				}
				
				    $( "#tagOptions .tagSelected" ).each(function( key, value ) {
				    // Add selected tags to array
				    if($(this).val() != '') {
					    selectedTags.push($(this).val());
				    }
				
				    console.log(selectedtags);
			    });
		})
		
		$('#newTagForm').submit(function(e) {
		e.preventDefault();
		  $.post('/scripts/tags/new', $('#newTagForm').serializeObject(), function(response) {
		  console.log(response);
		    if (response == 'unsuccessful') {
		      console.log('failed');
		    } else /*if (response == 'successful') */{
		      console.log('succeeded');
		      getSelectTags();
		      $("#addTag").hide();
		      $("#standardAddGroup").show();
		    }
		  }, "text");
		});
		
</script>