<style>
.input-profile input:disabled, .input-profile textarea:disabled { 
  background: none;
  border: 0px;
  padding: 5px;
  width:100%;
}
.input-profile input {
  padding:5px;
  display:block;
}
  </style>

  <div class="container">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title" id="panel-title">账户详情</h3>
      </div>
      <div class="panel-body input-profile">
        <form id="usernameDetails"><div class="row"><div class="col-md-3"><strong>用户名</strong></div><div class="col-md-5"><input type="text" name="username" value="<?php echo $student->username; ?>" disabled="disabled" placeholder="用户名" /></div><div class="col-md-4"><button id="editUsername" type="button" class="btn btn-default">修改</button><button id="submitUsername" type="submit" class="btn btn-default" style="display:none;">更新</button><br><br></div></div></form>
        <form id="emailDetails"><div class="row"><div class="col-md-3"><strong>邮箱</strong></div><div class="col-md-5"><input type="text" name="email" value="<?php echo $student->email; ?>" disabled="disabled" placeholder="Email" /></div><div class="col-md-4"><button id="editEmail" type="button" class="btn btn-default">修改</button><button id="submitEmail" type="submit" class="btn btn-default" style="display:none;">更新</button><br><br></div></div></form>
        <form id="passwordDetails"><div class="row"><div class="col-md-3"><strong>密码</strong></div><div class="col-md-5"><div id="newPasswordFields" style="display:none;" ><label>原密码</label><input type="password" name="oldPassword" value="" disabled="disabled" placeholder="当前密码" /><br/><label>新密码</label><input type="password" name="newPassword" value="" disabled="disabled" placeholder="新密码" /><br/></div></div><div class="col-md-4"><button id="editPassword" type="button" class="btn btn-default">修改</button><button id="submitPassword" type="submit" class="btn btn-default" style="display:none;">更新</button><br><br></div></div></form>
      </div>
    </div>
    <script>
      // Used to create JSON from forms //
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

      /* script for update personal details */
      $( document ).ready(function() {

      

          $("#editUsername").click(function(e){
            e.preventDefault();
            $("#usernameDetails input").prop("disabled", false);
            
            $(this).hide();
            $("#submitUsername").show();
          })
          
          $("#editEmail").click(function(e){
            e.preventDefault();
            $("#emailDetails input").prop("disabled", false);
            
            $(this).hide();
            $("#submitEmail").show();
          })
          
          $("#editPassword").click(function(e){
            e.preventDefault();
            $("#newPasswordFields").show();
            $("#passwordDetails input").prop("disabled", false);
            
            $(this).hide();
            $("#submitPassword").show();
          })
          
          // Adds alphanumeric rule
          $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^\w+$/i.test(value);
          }, "仅仅数字，字母，下划线被允许");
      
          // validate Username
          $("#usernameDetails").validate({
            rules: {
              username: {
                required: true,
                minlength: 5,
                alphanumeric: true,
                remote: "/scripts/student/register/validate/username"
              }
            },
            
            messages: {
              username: {
                required: "请提供一个用户名",
                minlength: "你的用户名必须至少5个字符长"
              }
            },
            submitHandler: function(form) {
              var jsonObject = JSON.stringify($(form).serializeObject());
              $.post( "scripts/student/change/username", jsonObject, function( data ) {
                var jData = jQuery.parseJSON(data)
                var result = jData.result;
                if(result)
                {
                  if(result == 'successful')
                  {
                    bootbox.alert("你的用户名已经被更改了", function() {
                      $("#usernameDetails input").prop("disabled", true);
                      $("#submitUsername").hide();
                      $("#editUsername").show();
                    })
                  }
                  else{
                    var message = jData.message;
                    bootbox.alert("更改用户名失败.<br>原因: "+message);
                  }
                }
                  
              })
            }
          });
          
          // validate new Email
          $("#emailDetails").validate({
            rules: {
              email: {
              required: true,
              email: true,
              remote: "/scripts/student/register/validate/email"
              }
            },
            
            messages: { email: { required: "请提供一个邮箱" } },
            submitHandler: function(form) {
              var jsonObject = JSON.stringify($(form).serializeObject());
              $.post( "scripts/student/change/email", jsonObject, function( data ) {
                console.log(data);
                var jData = jQuery.parseJSON(data)
                var result = jData.result;
                if(result)
                {
                  if(result == 'successful')
                  {
                    bootbox.alert("你的邮箱已经更改", function() {
                      $("#emailDetails input").prop("disabled", true);
                      $("#submitEmail").hide();
                      $("#editEmail").show();
                    })
                  }
                  else{
                    var message = jData.message;
                    bootbox.alert("邮箱更改失败.<br>原因: "+message);
                  }
                }
                  
              })
            }
          });
          
          // validate password
          $("#passwordDetails").validate({
            rules: {
              oldPassword: { required: true },
              newPassword: { required: true, minlength: 6 }
            },
            messages: {
              oldPassword: {
                required: "请提供你的当前密码",
              },
              newPassword: {
                required: "请提供一个新密码",
                minlength: "你的密码必须至少6个字符长"
              }
            },
            submitHandler: function(form) {
              var jsonObject = JSON.stringify($(form).serializeObject());
              $.post( "scripts/student/change/password", jsonObject, function( data ) {
                console.log(data);
                var jData = jQuery.parseJSON(data)
                var result = jData.result;
                if(result)
                {
                  if(result == 'successful')
                  {
                    bootbox.alert("你的密码已经更改", function() {
                      $("#passwordDetails input").prop("disabled", true);
                      $("#passwordDetails input").val('');
                      $("#submitPassword").hide();
                      $("#editPassword").show();
                      $("#newPasswordFields").hide();
                    })
                  }
                  else{
                    var message = jData.message;
                    bootbox.alert("密码更改失败.<br>原因: "+message);
                  }
                }
                  
              })
                
            }
          });
          
      });

        
      </script>

    <div id="univerID" hidden="true" class="col-md-5"><?php echo $student->universityID; ?></div> 
    <div id="schooID" hidden="true" class="col-md-5"><?php echo $student->schoolID; ?></div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title" id="panel-title">个人信息</h3>
      </div>
      <div class="panel-body input-profile">
        <form id="personalDetails">
        <div class="row"><div class="col-md-3"><strong>姓</strong></div><div class="col-md-5"><input type="text" name="first_name" value="<?php echo $student->firstName; ?>" disabled="disabled" placeholder="First Name" /><br></div></div>
        <div class="row"><div class="col-md-3"><strong>名</strong></div><div class="col-md-5"><input type="text" name="last_name" value="<?php echo $student->lastName; ?>" disabled="disabled" placeholder="Last Name"/><br></div></div>
        <div class="row"><div class="col-md-3"><strong>出生日期</strong></div><div class="col-md-5"><input type="text" name="DOB" id="DOB" value="<?php echo date("d/m/Y",strtotime($student->DOB)) ?>" disabled="disabled" placeholder="dd/mm/yyyy" /><br></div></div>
        <div class="row"><div class="col-md-3"><strong>个人简介</strong></div><div class="col-md-9"><textarea id="bio" name="bio" disabled="disabled" ><?php echo $student->bio; ?></textarea><br><br></div></div>
        <div class="row"><div class="col-md-3">
          <button type="button" id="editPersonal" class="btn btn-default" style="">编辑个人信息</button>
          <button type="submit" id="submitPersonal" class="btn btn-default" style="display:none;">更新</button>
        </div></div>
        </form>
      </div>
    </div>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title" id="panel-title">学校信息</h3>
      </div>
      <div class="panel-body input-profile">
        <div class="row"><div class="col-md-3"><strong>学校</strong></div><div class="col-md-5"><?php echo $student->universityName; ?></div><div class="col-md-4"></div></div>
        <div class="row"><div class="col-md-3"><strong>学院</strong></div><div class="col-md-5"><?php echo $student->schoolName; ?></div><div class="col-md-4"><button id="editSchool" type="button" class="btn btn-default btn-md pull-right" data-toggle="modal" data-target="#editSchoolModal">修改</button><br><br></div></div>
        <div class="row">
          <div class="col-md-3"><strong>专业</strong></div>
         <!-- <div class="col-md-9"><button id="addModules" type="button" class="btn btn-default btn-md pull-right" data-toggle="modal" data-target="#editModulesModal">Edit Modules</button><br><br></div> -->
          <div class="col-md-12">
            
              <table class="table table-hover">
                <thead>
                  <tr>
                  <th>专业名称</th>
                  <th>专业代码</th>
                  <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                foreach($student->currentlyStudyingFull() AS $studying)
                {
                  echo '<tr><td>'.$studying["moduleName"].'</td><td>'.$studying["moduleCode"].'</td></tr>';
                }
          
                ?>
                </tbody>
              </table>
            
          </div>
          
        </div>
        
      </div>
    </div>
    

<!-- Edit School Modal -->
<div class="modal fade" id="editSchoolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  <form id="editSchoolForm" type="get" action="#">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">修改学院</h4>
      </div>
      <div class="modal-body">
        
              
            <div id="changeSchoolContent" style="display:show;">
              <div id= "changeSchool">
              <label>选择一个新的学院</label>
              <select id="schoolOptions" class="form-control" name="school" placeholder="请选择你的学院">
              </select>
            </div>
            
              <p></p>
            
              <div id="moduleSection"  style="display:none;">
                <p>选择新的专业</p>
                  <select id="moduleOptions1" class="chosen-select"  multiple data-placeholder="请选择你的专业"></select>          
              </div>
            </div>              
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button id="moduleSubmit" type="submit" class="btn btn-primary">保存修改</button>
      </div>
    </form>
    </div>
  </div>
</div>


<!-- Edit Modules Modal -->

<div class="modal fade" id="editModulesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editModuleForm" type="get" action="#">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel2">编辑模块</h4>
        </div> 
        
        <div class="modal-body">

                <div id="ChangeModuleContent" style="display:show;">
                  <div id="moduleSelection2">
                    <label>Select your modules</label> 
                    <select id="moduleOptions2" class="chosen-select" multiple data-placeholder="Select your modules">
                    </select>
                  </div>
                </div>  
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          <button id="moduleSubmit2" type="submit" class="btn btn-primary">保存修改</button>
        </div> 

      </form>  
    </div>  
  </div>  
</div>       

<!-- Choosen jQuery -->
  <script src="js/chosen.jquery.js"></script>
  <script type="text/javascript"> 
    $( document ).ready(function() {
      $("#schoolOptions").chosen({
        
        no_results_text: "Oops, nothing found!",
        width:"95%"
        
        });
      $("#moduleOptions1").chosen({
        
        no_results_text: "Oops, nothing found!",
        width:"95%",
        max_selected_options: 10
        });
        $("#moduleOptions2").chosen({
        
        no_results_text: "Oops, nothing found!",
        width:"95%",
        max_selected_options: 10
        });
    });
  </script>



    <script src="/ckeditor/ckeditor.js"></script>
    <script src="/ckeditor/adapters/jquery.js"></script>
    
    <script>
    
    
        /* script for update personal details */
      $( document ).ready(function() {
          
          var editor = $( 'textarea#bio' ).ckeditor();
          
          function getUni(){
           return document.getElementById("univerID").innerHTML;
          }
          storeModuleData(getUni());
         
         
          function getSchoo(){
           return document.getElementById("schooID").innerHTML;
           //return "\"school\":\"" + document.getElementById("schooID").innerHTML + "\"";
          }
          
          var studentSchoolID = parseInt(getSchoo()) -1;
          
          console.log(getSchoo());

          //for updating modules
            var moduleData = [];
            var updateStudent = [];
            updateStudent["schools"];
            updateStudent["schoolSelected"];
            updateStudent["schoolModules"];
            updateStudent["selectedModules"] = [];
            getSchoo(); 
           loadModules2(getUni(), studentSchoolID);

/** CODE TO GET DATA **/
      function storeModuleData(universityID)
      {
        console.log(universityID);
        /* Download and store module JSON */
        $.get( "scripts/modules/"+universityID, function( data ) {
                
          var jData = jQuery.parseJSON(data)
          var validUniversity = jData.valid_university;
          var numberOfSchools = jData.school_number;
          if(validUniversity)
          {
            if(numberOfSchools == 0)
            {
              var universityName = jData.university_name;
              bootbox.alert("<p><strong>Notice!</strong><br/>"+universityName+" has no schools.</p>");
            }
            updateStudent["schools"] = jData.schools;
            populateSchools();
          }
          else 
          {
            bootbox.alert("Something went wrong");
          }
        })
      }
      /**  --  **/
      
      
      /** Populates school field for chosen university **/
      function populateSchools()
      {       
        // Clear School Options and repopulate
        $("#schoolOptions").empty();
        $("#schoolOptions").append('<option value="">选择学院</option>');
        
        $.each( updateStudent["schools"], function( key, value ) {
          html = '<option value="'+key+'">'+value.name+'</option>';
          $("#schoolOptions").append(html)
        })
        // Update chosen JS 
        $('#schoolOptions').trigger('chosen:updated');
        //Display stepTwoContent content when school form has loaded
        $("#changeSchoolContent").show();
      }
    
    
        /** When school option has been chosen **/
      $("#schoolOptions").on('change',function(e){
        var schoolSelectedValue = $(this).val();
        console.log(schoolSelectedValue);
        if (schoolSelectedValue != '')
        {
          updateStudent["schoolSelected"] = schoolSelectedValue;
          updateStudent["schoolModules"] = updateStudent["schools"][updateStudent["schoolSelected"]].modules;
          // Reset and load modules
          $("#moduleSection").hide();
          loadModules();
        }
      })
      

      function loadModules()
        {
          $("#moduleOptions1").empty();
          $.each( updateStudent["schoolModules"], function( key, value ) {
            var html ='<option value="'+value.ID+'">'+value.module_name+' '+value.module_code+'</option>';
            $("#moduleOptions1").append(html)
          });
          
          $('#moduleOptions1').trigger('chosen:updated');
          $("#moduleSection").show()
        }

        $("#moduleSection").on('change',"#moduleOptions1",function(e){
        // Get selected modules
        var moduleSelectedValue = $(this).val();
        // Add select modules to array
        updateStudent["selectedModules"] = moduleSelectedValue;
        console.log(moduleSelectedValue);
        if(moduleSelectedValue != null)
        {
          if(moduleSelectedValue.length > 2 )
          {
            $("#moduleSubmit").text('确定');
            $("#moduleSubmit").attr("disabled", false);
          }
        
          else
          {
            $("#moduleSubmit").text('至少选择3个科目');
            $("#moduleSubmit").attr("disabled", true);
          }
        }
        
      })

        function loadModules2(thisuni, thisSchool)
        {   
        $.get( "scripts/modules/"+thisuni, function( data ) {
          var jData = jQuery.parseJSON(data)
          var validUniversity = jData.valid_university;
          var numberOfSchools = jData.school_number;
            updateStudent["schools"] = jData.schools;
            updateStudent["schoolSelected"] = thisSchool;
            updateStudent["schoolModules"] = updateStudent["schools"][updateStudent["schoolSelected"]].modules;
          $("moduleOptions2").empty();
          $.each(updateStudent["schoolModules"], function(key,value) {
            var html ='<option value="'+value.ID+'">'+value.module_name+' '+value.module_code+'</option>';
            $("#moduleOptions2").append(html);
           });
          
          $('#moduleOptions2').trigger('chosen:updated');
          $("#moduleSection2").show()
          })
        }

        $("#moduleSection2").on('change',"#moduleOptions2",function(e){
        // Get selected modules
        var moduleSelectedValue = $(this).val();
        // Add select modules to array
        updateStudent["selectedModules"] = moduleSelectedValue;
        
        if(moduleSelectedValue != null)
        {
            $("#moduleSubmit2").text('Finish');
            $("#moduleSubmit2").attr("disabled", false);
          }
        
          else
          {
            $("#moduleSubmit2").text('Select at least 1 module');
            $("#moduleSubmit2").attr("disabled", true);
          }
        
       })
      
      function convertToObject()
      {
        var json = {};
        $.each(updateStudent, function( key, value ) {
          json[value.name] = value.value;
        })
        // Gets school ID from selected school
        json["school"] = updateStudent["schools"][updateStudent["schoolSelected"]].ID;
        console.log(JSON.stringify(json));
        json["studying"] = updateStudent["selectedModules"];
        return JSON.stringify(json);
        
      } 
      
      
      
      $("#moduleSubmit").click(function(e){
        
        var jsonObject = convertToObject();
        $.post( "scripts/student/change/modules", jsonObject, function( data ) {
            var msg = '';
            var jData = jQuery.parseJSON(data);
            var result = jData.result;
            console.log(result);
            if(result)
            {
              if(result == 'successful')
              {
              bootbox.alert("<p>School/Modules changed!</p>",function() {
                
              });
              
              }
            }
            
          })

      })
      
    $("#moduleSubmit2").click(function(e){
        
        var jsonObject = convertToObject();
        $.post( "scripts/student/change/modules", jsonObject, function( data ) {
            var msg = '';
            var jData = jQuery.parseJSON(data);
            var result = jData.result;
            console.log(result);
            if(result)
            {
              if(result == 'successful')
              {
              bootbox.alert("<p>Modules changed!</p>",function() {
                
              });
              
              }
            }
            
          })

      })
  
          //Nicks Code

          $("#editPersonal").click(function(e){
            e.preventDefault();
            $("#personalDetails input").prop("disabled", false);
            $("#personalDetails textarea").prop("disabled", false);
            $(this).hide();
            $("#submitPersonal").show();
            
            CKEDITOR.instances['bio'].setReadOnly(false);
          })
          
          $( "#DOB" ).datepicker({ dateFormat: "dd/mm/yy", changeMonth: true, changeYear: true, minDate: "-80Y", maxDate: "-12Y", yearRange:"-80:+0" });
          // Adds british time date format rule
          $.validator.addMethod(
            "britishDate",
            function(value, element) {
              // put your own logic here, this is just a (crappy) example
              return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
            },
            "Please enter a date in the format dd/mm/yyyy."
          );
          
          $("#personalDetails").validate({
            rules: {
              first_name: "required",
              last_name: "required",
              DOB : {
                required: true,
                britishDate: true
              }
            },
            
            messages: {
              first_name: {
                required: "Please provide your first name"
              },
              last_name: {
                required: "Please provide your first name"
              },
              DOB: {
                required: "Please provide your Date of Birth"
              }
            },
            submitHandler: function(form) {
              // Updates textarea field
              CKEDITOR.instances['bio'].updateElement();
              
              var jsonObject = JSON.stringify($(form).serializeObject());
              console.log(jsonObject);
              $.post( "scripts/student/change/personal", jsonObject, function( data ) {
          
                var jData = jQuery.parseJSON(data)
                var result = jData.result;
                if(result)
                {
                  if(result == 'successful')
                  {
                    bootbox.alert("你的个人简介已经更新成功", function() {
                      $("#personalDetails input").prop("disabled", true);
                      $("#personalDetails textarea").prop("disabled", true);
                      $("#submitPersonal").hide();
                      $("#editPersonal").show();
                      
                      
                      // Make ckeditor read only
                      CKEDITOR.instances['bio'].setReadOnly(true);
                    })
                  }
                  else{
                    var message = jData.message;
                    bootbox.alert("密码更改失败.<br>原因: "+message);
                  }
                }
                  
              })
                
            }
            
            
        });
      });
      </script>

  </div>