
	
		




$(document).ready(function() {
	
	$.ajax({
			type: 'GET',               //提交类型为POST
			url: 'http://dev.atux.co.uk/da/get_students.php',          
			
			dataType:'json',             //请求类型为json
			
		//成功获取到服务器端的响应用后，将向页面上的table中添加元素
		success:function(json){
			$("#list table").empty();     //清空table元素
			
			var li = '<tr><th>studentID</th><th>first_name</th><th>last_name</th><th>school_name</th><th>email</th></tr>';
			//var list = json.list;      得到留言记录列表
			$.each(json,function(index,array){ //遍历json数据列
			    //每一条记录构建一个li
				li+='<tr>';				
				li+='<td>'+array['studentID']+'</td>';
				li+='<td>'+array['first_name']+'</td>';
				li+='<td>'+array['last_name']+'</td>';
				li+='<td>'+array['name']+'</td>';
				li+='<td>'+array['email']+'</td>';
				li+='</tr>';
				
				
			});
			//将构建的li追加到table元素中
			$("#list table").append(li);
			
			$(".details").click(function(){
			     $("#modal-details").empty();
				$("#modal-details").append(str);
			})
			
			$(".del").click(function(){
				$(this).parents("tr").remove();//从表格中删除一行
				//从数据库中删除一行有待实现
			});
			
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
             url:'http://dev.atux.co.uk/da/get_students.php',
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




