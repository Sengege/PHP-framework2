$(document).ready(function(){
	$.getJSON("http://dev.atux.co.uk/da/get_feedback.php",function(data){
		for(var i = 0; i < data.length; i++){
			$('tbody').append("<tr><td>"+data[i].studentID+"</td><td>"+data[i].content+"</td><td>"+data[i].postDate+"</td></tr>");
		}
	});
});
/*	var curPage = 1;               //保存当前页码的全局变量
	var total,pageSize,totalPage;  //保存总记录数、页大小和总页数的全局变量
	//使用jQuery的$.ajax函数，异步的获取数据
	function getData(page){ 
		$.ajax({
			type: 'POST',               //提交类型为POST
			url: 'list.php',            //请求地址为list.php
			data: {'pageNum':page-1},   //页码数为传入的页码数
			dataType:'json',             //请求类型为json
			
			//成功获取到服务器端的响应用后，将向页面上的ul中添加元素
			success:function(json){
				$("#list ul").empty();     //清空ul元素
				total = json.total;         //总记录数
				pageSize = json.pageSize;   //每页显示条数
				curPage = page;             //当前页
				totalPage = json.totalPage; //总页数
				var li = "";
				var list = json.list;      //得到反馈记录列表
				$.each(list,function(index,array){ //遍历json数据列
			    //每一条留言构建一个li，在每一个li内部构建一个表格显示留言的详细信息
					li += "<li>"				
					li+='<table width="515" border="0" cellpadding="0" cellspacing="0">';
					li+='  <tr> ';				
					li+='	  <th width="261" height="30" align="left">';
					li+='	  <span>反馈标题：</span>';
			    	li+= array['title'];
					li+='	  <th width="437" align="left">';
					li+='	    <span>反馈人：</span> ';                          
					li+= array['name'];	  
					li+='  | 时间：'+ array['time'];	  
					li+='  </tr> ';
					li+='  <tr> ';
					li+='  <td height="50" colspan="2" align="left" valign="top"> ';
			    	li+= array['content'];
					li+='    </tr> ';
			    	li+='    </table>  	';			
			   		li+='    <hr/>  	';						
					li+="</li>";							
				});
				//将构建的li追加到ul元素中
				$("#list ul").append(li);
			},
			//在成功的完成请求后，更新页面底部的分页条
			complete:function(){ 
				getPageBar();
			},
			//在出现请求错误时提示数据加载失败
			error:function(){
				//alert("数据加载失败");
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
			pageStr += "<span>首页</span><span>上一页</span>";
		}else{
	   		//为链接添加rel属性，以记录分页位置
			pageStr += "<span><a href='javascript:void(0)' rel='1'>首页</a></span><span><a href='javascript:void(0)' rel='"+(curPage-1)+"'>上一页</a></span>";
		}
	
		//如果是最后页
		if(curPage>=totalPage){
			pageStr += "<span>下一页</span><span>尾页</span>";
		}else{
	   		//为链接添加rel属性，以记录分页位置	
			pageStr += "<span><a href='javascript:void(0)' rel='"+(parseInt(curPage)+1)+"'>下一页</a></span><span><a href='javascript:void(0)' rel='"+totalPage+"'>尾页</a></span>";
		}
		//将HTML字符串插入到显示分页的容器div中
		$("#pagecount").html(pageStr);
	}

	$(document).ready(function() {
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