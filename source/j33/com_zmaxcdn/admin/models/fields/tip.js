jQuery(document).ready(function(){	
	try{
			var x=10;
			var y=20;
			
			
			jQuery('.zmax-hasTipPreview').mouseover(function(e){
				
				var previewId= jQuery(this).attr("data");
				var previewImg = jQuery("#"+previewId+"_preview").val();
				var previewTitle = jQuery("#"+previewId+"_title").val();
				if(previewImg.substr(0,4)!= "http")
				{
					previewImg=SITE_URL+previewImg;
				}
				
				timer = setTimeout(function(){
					var  tooltip='<div id="zmax-fieldtooltip"><p>'+previewTitle+'</p><img class="img-responsive" src='+previewImg+' alt="预览图片"/></div>';
					if(previewImg=="")
					{
						var  tooltip='<div id="zmax-fieldtooltip"><p>'+previewTitle+'当前没有可预览的图片</p></div>';
					}
					
					jQuery("body").append(tooltip);
					jQuery("#zmax-fieldtooltip").css({
						top:(e.pageY + y)+"px",
						left:(e.pageX + x)+"px",
						position:"absolute",
						zIndex:"1000"
						}).show("slow");
				},300);
			}).mouseout(function(){
				jQuery("#zmax-fieldtooltip").remove();
				clearTimeout(timer);
			});
		}catch(e)
		{
			alert(e.message);
		}
});
