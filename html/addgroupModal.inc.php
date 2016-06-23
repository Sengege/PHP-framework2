<!-- Modal -->
<div class="modal fade" id="addGroupModal" taminsex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <div id="standardAddGroup">
	<form id="newGroupForm" type="get" action="#">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Group</h4>
      </div>
      <div class="modal-body">
        
							
							<label>Group Name</label>		
							
								<input type="text" name="groupName" class="form-control" placeholder="Group name here" >
										
							<br>
							<label>Group Description</label><br>		
							
								<textarea id="groupDescription" name="groupDescription" class="form-control" placeholder="Group Description here" ></textarea>
								<div id="groupDescriptionMessage"></div>
							<br>
						  <label>Relevant Module</label><br>
								<select type="text" id="moduleOptions" class="form-control" name="groupModule" placeholder="Select relevant module">
									<option value="">Select a module</option>
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
							<label>Group Tags Category(optional)</label>
								<select type="text" id="groupCategoryTags" class="form-control" name="groupTags" placeholder="Select tag category">
								<option value="">Tag Category</option>
								<?php
							    $q = $db->prepare("SELECT * FROM `Tag_category`");
								$q->execute();
									foreach($q->fetchAll() AS $tagCategories)
									{
										
										echo '<option value="'.$tagCategories['Tag_categoryID'].'">'.$tagCategories['name_EN'].'</option>';
									
									}
								?>
								</select>					
							<br>
								<div id="tagSection"  style="display:none;">
								<label>Add Tags (optional)</label>
								<br>
								<select id="tagOptions" class="chosen-select" name="tags" multiple data-placeholder="Select your tags"></select>
								<br>
						        <a href="#newtag" id="newTag">+New Tag</a>
							</div>
								<br>
							<label>Group Type</label>		
							<div class="input-group">
								<select type="text" name="groupType" class="form-control" placeholder="Group Type" >
									<option value="public">Public</option>
									<option value="private">Private</option>
								</select>
							</div>
								<br>
						
							<label>Add Students (optional)</label>
								<select type="text" id="studentOptions" class="form-control" name="students" multiple placeholder="Add students">
									
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Group</button>
      </div>
	  </form>
	  </div>
	  <div style="display:none;" id="addTag">
	 
							<form id="newTagForm" type="get" action="#">
	                        
	                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                             <h4 class="modal-title" id="myModalLabel">Create Tags</h4>
                             </div>
                               <div class="modal-body">
	                        
							<label>New Tag</label>		
	                        <input type="text" id="tagcategory" name="tagCat" placeholder="Tag Category" style="display:none;" value="">
							<input type="text" name="tagName" class="form-control" placeholder="New Tag" >
	                        <br>
							<button id="submitTag" type="submitTag" class="btn btn-primary">Add Tag</button>
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
				$("#tagOptions").append('<option value="">Select Tag</option>');
				
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