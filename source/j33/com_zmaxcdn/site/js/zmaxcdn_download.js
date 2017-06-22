/**
 *	description:ZMAX应用市场 js文件
 *				这些JS主要是完成一些商城系统必须的任务，比如购物车操作，点击计数等等操作
 *              而有关商城界面相关的JS则完全交由模板设计师来控制
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-11-06
 */	
 jQuery(document).ready(function(){		
	jQuery(".zmaxpackage").click(function(){				
		var id = jQuery(this).attr("data-id");				
		jQuery.ajax({
		type:'post',
		data:{
				id:id,
			},
		url:'index.php?option=com_zmaxcdn&task=download.download',
		cache:false,
		success:function(data){					
			var data = jQuery.parseJSON(data);
			if(data.canDownload)
			{	
				window.location.href = 'index.php?option=com_zmaxcdn&task=download.getFile&id='+data.id;	
				return false;
			}
			else
			{
				alert(data.info);
				return false;
			}
		},
		/*
	   error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest.status);
				alert(XMLHttpRequest.readyState);
				alert(textStatus);
			}
			*/
		
		error:function()
		{
			//alert("更新，Ajax异常，请联系支持团队：Email:zhang19min88@163.com");
		}
			
	});
		return false;
	});
	
 });







 



