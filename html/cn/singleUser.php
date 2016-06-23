<?php

  global $db;
  global $student;
  
  
  $getUserData = $db->prepare("SELECT *,b.name AS universityName,c.name AS schoolName FROM `students` a INNER JOIN `university` b ON a.universityID = b.universityID INNER JOIN `school` c ON a.schoolID = c.schoolID WHERE a.username = :userName");
  $getUserData->bindParam(":userName",$userName);
  $getUserData->execute();



if($getUserData->rowCount() == 0)
{
  // Start HTML
  startHTML("User Not Found",false);
  echo '<div class="section white"><div class="container"><h2>抱歉，这个用户不存在</h2></div></div>';
  footerHTML();
  return;
}
$userData = $getUserData->fetch();

startHTML($userData['username'],false);
?>
<nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/" style="height:auto;">
		  <img src="/img/logo3.png" class="img-responsive">
		  </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          
          <ul id="myTab" class="nav navbar-nav navbar-right">
		  <li role="presentation" ><a href="/dashboard.php" ><i class="fa fa-dashboard"></i> 主界面</a></li>
          <li><a href="#" id="logOut"><i class="fa fa-sign-out"></i>退出</a></li>
                
          </ul>
            </li>
		  
		</ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>

 
<div class="section white">
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title" id="panel-title">学生详细信息</h3>
      </div>
      <div class="panel-body input-profile">
        <div class="row"><div class="col-md-3"><strong>姓名</strong></div><div class="col-md-5"><?php echo $userData['first_name']." ".$userData['last_name']; ?></div><div class="col-md-4"><br><br></div></div>
        <div class="row"><div class="col-md-3"><strong>日期</strong></div><div class="col-md-5"><?php echo $userData['DOB']; ?></div><div class="col-md-4"><br><br></div></div>
        <div class="row"><div class="col-md-3"><strong>大学</strong></div><div class="col-md-5"><?php echo $userData['universityName']; ?></div><div class="col-md-4"></div></div>
        <div class="row"><div class="col-md-3"><strong>校区</strong></div><div class="col-md-5"><?php echo $userData['schoolName']; ?></div><div class="col-md-4"><br><br></div></div>
        <div class="row">
          <div class="col-md-3"><strong>学习</strong></div>
          <div class="col-md-12">
            
              <table class="table table-hover">
                <thead>
                  <tr>
                  <th>模块名称</th>
                  <th>模块代码</th>
                  <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                $userModules = $db->prepare("SELECT * FROM `studying` a INNER JOIN `module` b ON a.moduleID = b.moduleID WHERE a.studentID = :userID");
                $userModules->bindParam(":userID",$userData['studentID']);
                $userModules->execute();
                foreach($userModules AS $studying)
                {
                  echo '<tr><td>'.$studying["module_name"].'</td><td>'.$studying["module_code"].'</td></tr>';
                }
          
                ?>
                </tbody>
              </table>
            
          </div>
          
        </div>
        
      <div class="row">
          <div class="col-md-3"><strong>用户小组</strong></div>
          <div class="col-md-12">
            
              <table class="table table-hover">
                <thead>
                  <tr>
                  <th>小组名称</th>
                  <th>模块</th>
                  <th>描述</th>
                  <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                
                $userGroups = $db->prepare("SELECT * FROM `group_membership` a INNER JOIN `groups` b ON a.groupID = b.groupID INNER JOIN `module` c ON c.moduleID = b.moduleID WHERE a.studentID = :userID");
                $userGroups->bindParam(":userID",$userData['studentID']);
                $userGroups->execute();
                foreach($userGroups AS $group)
                {
                  echo '<tr><td><a href="/dashboard/group/'.$group['groupID'].'">'.$group["groupName"].'</a></td><td>'.$group["module_name"].'</td><td>'.$group["groupDescription"].'</td></tr>';
                }
          
                ?>
                </tbody>
              </table>
            
          
        </div>
        
      </div>
    </div>
        
  </div>
</div>

<?php footerHTML(); ?>