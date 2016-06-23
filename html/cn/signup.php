<?php

// Start HTML
startHTML_CN("登记");
global $db;


?>

 
<div class="section white">
		<div class="container">
		
		
		<div class="col-md-12">
			<h1>登记</h1>

			
			
				<div id="stepOne">
					
					<form id="newStudent" type="post" action="#">
					<input type="hidden" name="language" value="CN">
					<h3>第一步</h3>
					<div class="col-md-6">
							
							
							<label>名字</label>		
							<div class="input-group">
								<input type="text" name="first_name" class="form-control" placeholder="这里的名字" >
							</div>
							
							<label>出生日期</label>		
							<div class="input-group">
								<input type="text" name="DOB" id="DOB" class="form-control">
							</div>
							
							<label>大学</label>
							<br>
						  <!-- Example row of columns -->
						  
							<select name="university" id="university" style="padding:5px;display:block;">
								<option value="">选择大学</option>
								<?php
									foreach($db->query("SELECT * FROM `university`") AS $university)
									{
										echo '<option value="'.$university['universityID'].'">'.$university['name'].'</option>';
									}
								?>
								<option value="notListed">不在列表中？</option>
							</select>
								
								
					</div>
					<div class="col-md-6">	
								
								<label>电子邮件</label>		
								<div class="input-group">
									<input type="text" name="email" class="form-control"  >
								</div>
								
								<label>昵称</label>		
								<div class="input-group">
									<input type="text" name="username" class="form-control"  >
								</div>
								
								<label>密码</label>		
								<div class="input-group">
									<input type="password" name="password" class="form-control" >
								</div>
								
								<button type="submit">下一个</button>
								
							
					</div>
					</form>
				</div>
				
				<div id="stepTwo" style="display:none" >
					
					<h3>第二步</h3>
					<div class="col-md-12">
						<div id="stepTwoContent" style="display:none;">
							<div id="schoolSection"  >
								<p>选择专业</p>
								<select id="schoolOptions" name="school" data-placeholder="Select your school">
								</select>
							</div>
						
							<p></p>
						
							<div id="moduleSection"  style="display:none;">
								<p>选择科目</p>
								<select id="moduleOptions" class="chosen-select"  multiple data-placeholder="Select your modules"></select>					
							</div>
							<p></p>
							<button type="button" onclick="changePage(1);" class="" id="back">后退</button>
						<button type="button" disabled="disabled" class="btn btn-primary" id="submitAll">至少选择三科</button>
						</div>
					</div>
					
				</div>
			</div>

			
			
			
		</div>
</div>


	
	<!-- Choosen jQuery -->
	<script src="/js/chosen.jquery.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <!-- Chinese Language script for JQuery UI datepicker-->
    <script src="/js/datepicker-zh-CN.js" type="text/javascript" charset="UTF-8"></script>
	<script type="text/javascript"> 
		$( document ).ready(function() {
			$("#schoolOptions").chosen({
				
				no_results_text: "没有发现",
				width:"95%"
				
			  });
			$("#moduleOptions").chosen({
				
				no_results_text: "没有发现",
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
			
			$( "#DOB" ).datepicker({ dateFormat: "dd/mm/yy", changeMonth: true, changeYear: true, minDate: "-80Y",  yearRange:"-80:+0" });
		
			
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
							remote: "/scripts/student/register/validate/username"
						},
						password: {
							required: true,
							minlength: 6
						}
						
						
					},
					
					messages: {
					first_name: {
						required: "名称是必需的"
					},
					university: {
						required: "请选择一所大学"
					},
						DOB: {
							required: "请提供您的出生日期"
						},
						
						password: {
							required: "请提供密码",
							minlength: "您的密码必须至少六个字符"
						},
						
						email: {
							required: "请提供一个电子邮件",
							email: "请输入有效的电子邮件地址"
							
						},
						
						username: {
							required: "请提供一个昵称",
							minlength: "您的昵称必须至少为5个字符"
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
				$.get( "/scripts/modules/"+universityID, function( data ) {
								
					var jData = jQuery.parseJSON(data)
					var validUniversity = jData.valid_university;
					var numberOfSchools = jData.school_number;
					if(validUniversity)
					{
						if(numberOfSchools == 0)
						{
							var universityName = jData.university_name;
							bootbox.alert("<p><strong>警告</strong><br/>"+universityName+" 没有提供专业</p>");
						}
						newStudent["schools"] = jData.schools;
						populateSchools();
					}
					else 
					{
						bootbox.alert("出事了");
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
				$("#schoolOptions").append('<option value="">选择专业</option>');
				
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
				$("#submitAll").text('至少选择三科');
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
					  message: "<p><b>STUDY WITH ME</b> 只适用于选定的大学<br><br>想报名参加你们的大学？点击报名参加我的大学，了解如何</p>",
					  title: "<strong>大学未列出？</strong>",
					  buttons: {
						success: {
						  label: "提交我的大学",
						  className: "btn-success",
						  callback: function() {
							
						  }
						},
						
						close: {
						  label: "行",
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
						  message: "<p><strong>警告</strong><br>您已经选择科目 <strong>"+previousUniversity+"</strong>. 如果继续用 <strong>"+newUniversity+"</strong> 下一页您选择的对象将丢失</p>",
						  title: "<strong>University Change</strong>",
						  buttons: {
							success: {
							  label: "恢复到 "+previousUniversity,
							  className: "btn-primary",
							  callback: function() {
								$("#university").val(newStudent["university"]);
							  }
							},
							
							close: { label: "继续",  className: "btn-danger", }
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
						$("#submitAll").text('完成');
						$("#submitAll").attr("disabled", false);
					}
				
					else
					{
						$("#submitAll").text('至少选择三科');
						$("#submitAll").attr("disabled", true);
					}
				}
				
			})
			
			function convertToObject()
			{
				var json = {};
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
				$.post( "/scripts/student/register/", jsonObject, function( data ) {
						console.log(data);
						var msg = '';
						var jData = jQuery.parseJSON(data)
						var result = jData.result;
						if(result)
						{
							if(result == 'successful')
							{
							bootbox.alert("<p>谢谢您注册</p>",function() {
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

<?php footerHTML_CN(); ?>