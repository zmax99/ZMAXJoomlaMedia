jQuery(function($) {

	jQuery(".zmaxtip").click(function(){
		var intro = jQuery(this).attr('data');
		var d = dialog({
			content:intro,
			quickClose:true
		});
		d.show(jQuery(this).get(0));
	});

	jQuery('#itemlist_controller').click(function() {
		var itemlist = jQuery('#itemlist');
			itemlist.slideToggle();
	});
	
	
	jQuery('a.zmaxinsert').click(function() {
		var id = jQuery(this).attr("data");
		jQuery.ajax({
			type:'post',
			url:'index.php?option=com_zmaxcdn&task=upload.insert',
			data:{
				id:id
			},
			cache:false,
			success:function(data){
				if(window.execScript) {
					// 给IE的特殊待遇
					window.execScript(data);
				}
				else {
					// 给其他大部分浏览器用的
					window.eval(data);
				}
			},
			error:function()
			{				
				alert("添加失败，Ajax异常，请联系支持团队：Email:zhang19min88@163.com");
			}		
		});
	});
	
});

