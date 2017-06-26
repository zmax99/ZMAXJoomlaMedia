jQuery(document).ready(function(){
	  
	  //处理SITEBASE TOKEN变量声明失败 ：在某一些模板中，使用JFactory::getDocument()->addScriptDeclaration 可能会不起作用
	  if(typeof ZMAX_SYSTEM_AJAX_SITE_BASE=="undefined")
	  {
		  ZMAX_SYSTEM_AJAX_SITE_BASE="";
	  }
	  
	  if(typeof ZMAX_SYSTEM_AJAX_TOKEN=="undefined")
	  {
			ZMAX_SYSTEM_AJAX_TOKEN="";
	  }
	
	//选中一个项目的时候
	jQuery(".zitem").click(function(){
		var id =  jQuery(this).attr("data");
		setData(id);
		jQuery(".zitem").removeClass("selected");
		jQuery(this).addClass("selected");
	});  
	
	//点击清空的时候
	jQuery(".system-clear-selected").click(function(){
		if(jQuery(this).hasClass("layui-disabled"))
		{
			return true;
		}
		jQuery(".zitem").removeClass("selected");
		jQuery(".system-insert-btn").addClass("layui-disabled");
		jQuery(this).addClass("layui-disabled");
		jQuery(".system-no-container").html("0").removeClass("selected");
		jQuery(".zattach-inner").css("visibility","hidden");
	}); 
	jQuery(".system-zmax-todo").click(function(){
		layer.alert("该功能将在下一版本推出，请关注我们的更新！",{icon:1});
	});
	//当需要删除的时候
	jQuery(".system-at-delete-link").click(function(){
		var id =  jQuery(".system-attach-detail-container").attr("data");
		layer.open({
				type:0,
				content:'你确定要删除这个资源吗？在删除记录的同时也会删除存储在服务器上的文件。如果你在其他地方使用过该资源，那么可能会造成资源无法访问。请慎重删除',
				icon:0,
				btn:['确定','取消'],
				btn1:function(index,layero){
					jQuery.ajax({
						type:'post',
						url:ZMAX_SYSTEM_AJAX_SITE_BASE+'index.php?option=com_zmaxcdn&task=items.ajaxDelete&'+ZMAX_SYSTEM_AJAX_TOKEN,
						data:{
							cid:id
						},
						cache:false,
						success:function(data)
						{
							jQuery(".zitem.selected").parent().hide();
							jQuery(".system-clear-selected").trigger("click");
							layer.close(index);
							
							
							layer.msg(data,{time:1000});
						},
						error:function()
						{
							
						}		
					});
				},
				btn2:function(index,layero){
				}
		});
		
	});  
	
	 
	jQuery(".setting input,.setting textarea").blur(function(){
		var name=jQuery(".system-at-set-title-input").val();
		var desc = jQuery(".system-at-set-caption-input").val();
		var id =  jQuery(".system-attach-detail-container").attr("data");
		jQuery.ajax({
			type:'post',
			url:ZMAX_SYSTEM_AJAX_SITE_BASE+'index.php?option=com_zmaxcdn&task=item.ajaxUpdate&'+ZMAX_SYSTEM_AJAX_TOKEN,
			data:{
				id:id,
				name:name,
				description:desc
			},
			cache:false,
			success:function(data)
			{
				layer.msg(data,{time:1000});
			},
			error:function()
			{
				
			}		
		});
	});
	
	jQuery(".system-insert-btn").click(function(){
		if(jQuery(this).hasClass("layui-disabled"))
		{
			return true;
		}
		var id =  jQuery(this).attr("data");
		var func = jQuery(this).attr("function");
		var str=func+"("+id+")";
		window.eval(str);
	});
	
	
	//系统TIPS支持
	jQuery(".zmaxtip").click(function(){
		var intro = jQuery(this).attr('data');
		var d = dialog({
			content:intro,
			quickClose:true
		});
		d.show(jQuery(this).get(0));
	});
	/*
	jQuery(".zmaxtip").click(function(){				
		var data = jQuery(this).attr("data");
		//layer.tips(data,this);
		layer.alert(data);
	});
	*/
	
	//系统下载支持
	jQuery(".zmaxpackage").click(function(){				
		var id = jQuery(this).attr("data-id");				
		jQuery.ajax({
			type:'post',
			data:{
					id:id,
				},
			url:'index.php?option=com_zmaxcdn&task=download.download',
			cache:false,
			success:function(data)
					{					
						var data = jQuery.parseJSON(data);
						if(data.canDownload)
						{	
							window.location.href = 'index.php?option=com_zmaxcdn&task=download.getFile&id='+data.id;	
							return false;
						}
						else
						{
							layer.alert(data.info);
							return false;
						}
					},	
			error: function(XMLHttpRequest, textStatus, errorThrown) 
					{
						alert(XMLHttpRequest.status);
						alert(XMLHttpRequest.readyState);
						alert(textStatus);
					}
			});
		return false;
	});
	

	  /*系统弹出框支持*/ 
	jQuery('.system-zmax-modal').click(function(){
		var url = jQuery(this).attr("url");
		var refresh = jQuery(this).attr("refresh");
		var layer = layui.layer;
		
		
		layer.open({
			type:2,
			area:['80%','60%'],
			title:'ZMAX媒体管理',
			btn:['保存','关闭'],
				btn2: function(index, layero){
					//return false 开启该代码可禁止点击该按钮关闭
					layer.close(index);
					return false;
			    },
				btn1: function(index, layero){
					var myform = layer.getChildFrame('form',index);
					var vfResult = Verification(myform);
					if(vfResult)
					{
						//var childLayer = window['layui-layer-iframe'+index].layer;
						
						//layer.msg(childLayer);
						var cIndex =  layer.load();
						var formData = jQuery(myform).serializeArray();
						//var formData = jQuery(myform).serialize;
						var action = jQuery(myform).attr("action");

						jQuery.ajax({
							type:'post',
							url:ZMAX_SYSTEM_AJAX_SITE_BASE+action,
							data:{
								'data':formData
							},	
							cache:false,
							success:function(data)
							{
								layer.close(index);								
								var msg='保存成功,点击确认刷新页面';	
								if(refresh==0)
								{
									var msg='保存成功';	
								}	
								
								layer.open({
									type:0,
									icon:1,
									content:msg,
									yes:function(index,layero)
									{
										if(refresh==0){
											layer.close(index);	
											return true;
										}	
										location.reload(); 
										return true;
									}
								 
								});
									
								
								
							},
							error:function(e)
							{
								layer.alert(e.msg);
							}		
						});
						
					}
					return false;
			    },
			maxmin:true,
			shadeClose:true,
			content:url,
			success:function(layero,index){
				layer.iframeAuto(index);
			}
			
		});		
	})
});

function setData(id)
{
	//STEP 1 设置当前的资源ID
	jQuery(".system-no-container").html("1").addClass("selected");
	
	jQuery(".system-insert-btn").attr("data",id);
	jQuery(".system-attach-detail-container").attr("data",id);
	
	jQuery(".system-insert-btn").removeClass("layui-disabled");
	jQuery(".system-clear-selected").removeClass("layui-disabled");
	jQuery(".zattach-inner").css("visibility","initial");
	
	//STEP 2 获得资源的详细情况
	var action="index.php?option=com_zmaxcdn&task=item.loadItem"
	jQuery.ajax({
		type:'post',
		dataType: "json", 
		url:ZMAX_SYSTEM_AJAX_SITE_BASE+action,
		data:{
			'id':id
		},	
		cache:false,
		success:function(data)
		{
			jQuery(".system-at-img").attr("src",data.thumb);
			jQuery(".system-at-filename").html(data.name);
			jQuery(".system-at-size").html(data.size);
			jQuery(".system-at-dim").html(data.dim);
			
			jQuery(".system-at-set-url-input").val(data.pubUrl);
			jQuery(".system-at-set-title-input").val(data.name);
			jQuery(".system-at-set-caption-input").val(data.description);
			jQuery(".system-at-set-alt-input").val(data.alt);
			jQuery(".system-at-set-desc-input").val(data.description);
			
			
		},
		error:function(e)
		{
			layer.alert(e.msg);
		}		
	});
}

 var Verification = function (form) {
    var config = {
        verify: {
            required: [
              /[\S]+/
              , '必填项不能为空'
            ]
          , phone: [
            /^1\d{10}$/
            , '请输入正确的手机号'
          ]
          , email: [
            /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
            , '邮箱格式不正确'
          ]
          , url: [
            /(^#)|(^http(s*):\/\/[^\s]+\.[^\s]+)/
            , '链接格式不正确'
          ]
          , number: [
            /^\d+$/
            , '只能填写数字'
          ]
          , date: [
            /^(\d{4})[-\/](\d{1}|0\d{1}|1[0-2])([-\/](\d{1}|0\d{1}|[1-2][0-9]|3[0-1]))*$/
            , '日期格式不正确'
          ]
          , identity: [
            /(^\d{15}$)|(^\d{17}(x|X|\d)$)/
            , '请输入正确的身份证号'
          ]
        }
    };
    formElem = jQuery(form);
    var button = jQuery(this), verify = config.verify, stop = null
    , DANGER = 'layui-form-danger', field = {}
    , verifyElem = formElem.find('*[lay-verify]') //获取需要校验的元素
    , fieldElem = formElem.find('input,select,textarea') //获取所有表单域

    //开始校验
    layui.each(verifyElem, function (_, item) {
        var othis = jQuery(this), tips = '';
        var arr = othis.attr('lay-verify').split(',');
        for (var i in arr) {
            var ver = arr[i];
            var value = othis.val(), isFn = typeof verify[ver] === 'function';
            othis.removeClass(DANGER);
            if (verify[ver] && (isFn ? tips = verify[ver](value, item) : !verify[ver][0].test(value))) {
                layer.msg(tips || verify[ver][1], {
                    icon: 5
                  , shift: 6
                });
                //非移动设备自动定位焦点
                if (!layui.device().android && !layui.device().ios) {
                    item.focus();
                }
                othis.addClass(DANGER);
                return stop = true;
            }
        }
    });

    if (stop) return false;

    layui.each(fieldElem, function (_, item) {
        if (!item.name) return;
        if (/^checkbox|radio$/.test(item.type) && !item.checked) return;
        field[item.name] = item.value;
    });

    //返回序列化表单元素， JSON 数据结构数据。
   // return formElem.serializeArray();
    return true;
};



  
  
 