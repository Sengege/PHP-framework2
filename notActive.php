<?php 

error_reporting(E_ALL);/*include database connection*/
require_once('scripts/prepend.php');

// If user is not logged in redirect to index.php
if(!$student->userExists()) { header('Location: index.php'); die(); } 
if($student->active) { header('Location: dashboard.php'); die(); }

require_once('scripts/functions/functions.php');

/* Start HTML*/
startHTML("Account Not Active",false);

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
		 
          <li><a href="#" id="logOut"><i class="fa fa-sign-out"></i>退出</a></li>
                
          </ul>
            </li>
		  
		</ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
	<div class="container">
		<div>
			<p>你好， <?php echo $student->username; ?>, 你的账号还没有激活! 请检查我们发送给你的激活邮件 (<?php echo $student->email; ?>) 请务必检查您的垃圾邮件/垃圾邮件文件夹，以防万一。</p>
			<p>看下面的选项</p>
			<p>
				<a href="#" id="resend">重新发送激活邮件</a><br>
				<a href="#" id="change">抱歉，我使用了错误的邮箱注册,更改邮箱</a><br>
				<div id="changeEmailForm" style="display:none; padding:10px;">
					<form id="changeEmail">
						<input id="newEmail" type="text" placeholder="请输入新邮箱" style="width:100%; padding:10px;">
						<button type="submit">更改</button>
					</form>
				</div>
				<a href="#" id="delete">我想删除我的帐户</a>
			</p>
		</div>

	</div>

<script>
$(document).ready(function() {
   $("#resend").click(function(e){
		e.preventDefault();
		$.get( "/scripts/notConfirmed/resend" ,function( data ) {
			var jData = jQuery.parseJSON(data);
			var result = jData.result;
			if(result == 'successful')
			{
				bootbox.alert("一封新的邮件已经发送到你的邮箱");
			}
			else
			{
				var message = jData.message;
				bootbox.alert("激活邮件发送失败.<br>原因: "+message);
			}
		}); 
   })
   $("#change").click(function(e){
	e.preventDefault();
	$("#changeEmailForm").toggle();
   })
   $("#changeEmail").submit(function(e){
		e.preventDefault();
		var newEmail = $("#newEmail").val();
		$.get( "/scripts/notConfirmed/change/"+newEmail ,function( data ) {
			var jData = jQuery.parseJSON(data);
			var result = jData.result;
			if(result == 'successful')
			{
				bootbox.alert("你的邮箱已经改变了，检查 <strong>"+newEmail+"</strong> 你的激活邮件", function(){
					location.reload();
				});
				
			}
			else
			{
				var message = jData.message;
				bootbox.alert("邮箱更改失败.<br>原因: "+message);
			}
		});
   })
   
   $("#delete").click(function(e){
	e.preventDefault();
	bootbox.confirm("你确定你想要删除你的账号?<br><br>这个动作是永久性的，不能被撤消", function(result){
		if(result)
		{
			$.get( "/scripts/notConfirmed/delete",function( data ) {
				var jData = jQuery.parseJSON(data);
				var result = jData.result;
				if(result == 'successful')
				{
					bootbox.alert("你的账号已经删除",function(){
						location.reload();
					});
				}
				else
				{
					var message = jData.message;
					bootbox.alert("账号删除失败<br>原因: "+message);
				}
			})
					
		}
	});
   })
	
});

</script>
<?php
footerHTML();
?>