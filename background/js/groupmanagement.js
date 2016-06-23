  
  
  
  $(document).ready(function() {
	
	$.ajax({
			type: 'GET',               //提交类型为get
			url: 'http://dev.atux.co.uk/da/getGroupList.php?page=1',          
			
			dataType:'json',             //请求类型为json
			
		//成功获取到服务器端的响应用后，将向页面上的table中添加元素
		success:function(json){
			
			$("#list table").empty();     //清空table元素
			
			var li = '<tr><th>groupID</th><th>groupName</th><th>adminID</th><th>type</th><th>active</th><th>createdDate</th></tr>';
			
			$.each(json.data,function(index,item){ //遍历json数据列
			    //每一条记录构建一个li
			
				li+='<tr>';				
				li+='<td>'+item.groupID+'</td>';
				li+='<td>'+item.groupName+'</td>';
				li+='<td>'+item.adminID+'</td>';
				li+='<td>'+item.type+'</td>';
				li+='<td>'+item.active+'</td>';
				li+='<td>'+item.createdDate+'</td>';
				li+='</tr>';
				
				
			});
			//将构建的li追加到table元素中
			$("#list table").append(li);
			
			
		},
		
		//在出现请求错误时提示数据加载失败
		error:function(){
			alert("数据加载失败");			
		}
	});

	
	
	
	
	
	//搜索框查询
	$("#searchbtn").click(function(){
		 $.ajax({
             type:"get",
             url:'http://202.196.1.141/~tristan/tj/get_students.php',
             data:{value:$("#searchbox").val()},
             dataType:"json",
             success:function(data){
                $('#list table"').empty();   //清空table里面的所有内容
				var li = '<tr><th>小组名</th><th>小组标签</th><th>小组人数</th><th>小组活动数量</th><th>编辑</th></tr>';
			    var list = json.list;
				$.each(list,function(index,array){
			    	li+='<tr>';				
					li+='<td>'+array['studentID']+'</td>';
					li+='<td>'+array['first_name']+'</td>';
					li+='<td>'+array['last_name']+'</td>';
					li+='<td>'+array['name']+'</td>';
					li+='<td>'+array['email']+'</td>';
					li+='<button class="btn btn-info details" data-toggle="modal" data-target="#mymodal-data" type="button">MoreDetails</button></td>'
					li+='<td><button class="btn btn-default del" onclick="del(this)">删除</button></td>'
					li+='</tr>';
				});
				$("#list table").append(li);
              },
					  
		     error:function(){
				 alert("没有找到此条记录...");
				 $('#list').empty();
				 $('#pagecount').empty();
				 $("#list").append("<p>没有找到此条记录...</p>");
		     }			  
		 });
	});
	
	
	
});

  
  
  
  
  
  /*
    var curPage = 1;               //保存当前页码的全局变量
	var total,pageSize,totalPage;  //保存总记录数、页大小和总页数的全局变量
	//使用jQuery的$.ajax函数，异步的获取数据
	function getData(page){ 
		$.ajax({
			type: 'POST',               //提交类型为POST
			url: 'list.php',            //请求地址为list.php
			data: {'pageNum':page-1},   //页码数为传入的页码数
			dataType:'json',             //请求类型为json
		//成功获取到服务器端的响应用后，将向页面上的table中添加元素
		success:function(json){
			$("#list table").empty();     //清空table元素
			total = json.total;         //总记录数
			pageSize = json.pageSize;   //每页显示条数
			curPage = page;             //当前页
			totalPage = json.totalPage; //总页数
			var li = '<tr><th>GroupID</th><th>groupName</th><th>admininID</th><th>type</th><th>active</th><th>More</th><th>编辑<th></tr>';
			
			var list = json.list;      //得到留言记录列表
			$.each(list,function(index,array){ //遍历json数据列
			    //每一条记录构建一个li
				
				li+='<tr>';				
				li+='<td>'+array['groupID']+'</td>';
				li+='<td>'+array['groupName']+'</td>';
				li+='<td>'+array['adminID']+'</td>';
				li+='<td>'+array['type']+'</td>';
				li+='<td>'+array['active']+'</td>';
				li+='<button class="btnmodal btn btn-info details" type="button">MoreDetails</button></td>'
				li+='<td><button class="btn btn-default del" >删除</button></td>'
				li+='</tr>';
				
				var str='<p>groupID:'+array['groupID']+'</p>'+'<p>groupName:'+array['groupName']+'</p>'+'<p>adminID:'+array['adminID']+'</p>'+'<p>type:'+array['type']+'</p>';
										
			});
			//将构建的li追加到table元素中
			$("#list table").append(li);
			
			$(".details").click(function(){
			     	$("#modal-details").empty();
					$("#modal-details").append(str);
				});	
			
			$(".del").click(function(){
				$(this).parents("tr").remove();//从表格中删除一行
				});
			
		},
		//在成功的完成请求后，更新页面底部的分页条
		complete:function(){ 
			getPageBar();
		},
		//在出现请求错误时提示数据加载失败
		error:function(){
			alert("数据加载失败");
		}
	});
}







//获取分页条
function getPageBar(){
	//页码大于最大页数
	if(curPage>totalPage) curPage=totalPage;
	//页码小于1
	if(curPage<1) curPage=1;
	pageStr = "<span>共"+total+"条</span><span>"+curPage+"/"+totalPage+"</span>";
	
	//如果是第一页
	if(curPage==1){
		pageStr += "<span class='btn btn-default'>首页</span><span class='btn btn-default'>上一页</span>";
	}else{
	   //为链接添加rel属性，以记录分页位置
		pageStr += "<span class='btn btn-default'><a href='javascript:void(0)' rel='1'>首页</a></span><span class='btn btn-default'><a href='javascript:void(0)' rel='"+(curPage-1)+"'>上一页</a></span>";
	}
	
	//如果是最后页
	if(curPage>=totalPage){
		pageStr += "<span class='btn btn-default'>下一页</span><span class='btn btn-default'>尾页</span>";
	}else{
	   //为链接添加rel属性，以记录分页位置	
		pageStr += "<span class='btn btn-default'><a href='javascript:void(0)' rel='"+(parseInt(curPage)+1)+"'>下一页</a></span><span class='btn btn-default'><a href='javascript:void(0)' rel='"+totalPage+"'>尾页</a></span>";
	}
	//将HTML字符串插入到显示分页的容器div中
	$("#pagecount").html(pageStr);
}

$(document).ready(function() {
	//搜索框查询
	$("#searchbtn").click(function(){
		 $.ajax({
             type:"get",
             url:"test.php",
             data:{value:$("#searchbox").val()},
             dataType:"json",
             success:function(data){
                $('#list table"').empty();   //清空table里面的所有内容
				var li = '<tr><th>小组名</th><th>小组标签</th><th>小组人数</th><th>小组活动数量</th><th>编辑</th></tr>';
			    var list = json.list;
				$.each(list,function(index,array){
			    	li+='<tr>';				
					li+='<td>'+arry['id']+'</td>';
					li+='<td>'+arry['name']+'</td>';
					li+='<td>'+arry['gender']+'</td>';
					li+='<td>'+arry['major']+'</td>';
					li+='<td>'+arry['school']+'</td>';
					li+='<button class="btn btn-info" data-toggle="modal" data-target="#mymodal-data" type="button">MoreDetails</button></td>'
					li+='</tr>';
				});
				$("#list table").append(li);
              },
					  
		     error:function(){
				 alert("没有找到此条记录...");
				 $('#list').empty();
				 $('#pagecount').empty();
				 $("#list").append("<p>没有找到此条记录...</p>");
		     }			  
		 });
	});
	
	
	
	

	getData(curPage);    //在页面加载时，获取当前页的数据
	//为分页导航栏关联单击事件处理代码
	$("#pagecount span a").live('click',function(){
	    //判断其是否存在rel属性
		var rel = $(this).attr("rel");
		if(rel){
			getData(rel);   //如果存在，在单击时则调用getData(rel)异步的获取分页的数据
		}
	});	
});
*/



