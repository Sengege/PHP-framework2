
$(document).ready(function() {
	
	$.ajax({
			type: 'GET',               //提交类型为POST
			url: 'http://dev.atux.co.uk/da/get_count.php',         
			dataType:'json',             //请求类型为json
			
		//成功获取到服务器端的响应用后，将向页面上的table中添加元素
		success:function(json){
			var li = '<tr><th></th><th>ThisWeek</th><th>TwoWeekAgo</th><th>ThreeWeekAgo</th><th>FourWeekAgo</th><th>FiveWeekAgo</th><th>SixWeekAgo</th></tr>';
			var list = json.data;  
			$.each(list,function(index,array){ //遍历json数据列
			    //每一条记录构建一个li
				li+='<tr>';	
				li+='<th>'+ index  +'</th>';			
				li+='<td>'+array['1']+'</td>';
				li+='<td>'+array['2']+'</td>';
				li+='<td>'+array['3']+'</td>';
				li+='<td>'+array['4']+'</td>';
				li+='<td>'+array['5']+'</td>';
				li+='<td>'+array['6']+'</td>';
				li+='</tr>';
			});
			//将构建的li追加到table元素中
			$("#list table").append(li);
                            
		},
		
		
		error:function(){
			alert("数据加载失败");			
		}
		
	});

});