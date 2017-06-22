<?php 
/**
 *	description:ZMAX媒体管理组件 资料列表布局文件
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-10-11
 * 	@license GNU General Public License version 3, or later
 *  check date:2016-07-11
 *  checker :min.zhang
 *  modified:min.zhang
 *  modify date:2017-06-21
 */
defined('_JEXEC') or die('you can not access this file!');

//加载需要的css ,js
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root()."administrator/components/com_zmaxcdn/libs/7niu/main.css");
$doc->addStyleSheet(JUri::root()."administrator/components/com_zmaxcdn/libs/7niu/highlight.css");
$doc->addScript(JUri::root()."administrator/components/com_zmaxcdn/libs/7niu/ui.js");
$doc->addScript(JUri::root()."administrator/components/com_zmaxcdn/libs/7niu/plupload.js");
$doc->addScript(JUri::root()."administrator/components/com_zmaxcdn/libs/7niu/qiniu.js");
JHtml::_('jquery.framework');
JHtml::_('behavior.keepalive');

$params = JComponentHelper::getParams("com_zmaxcdn");
$ak = $params->get("accessKey","");
$sk = $params->get("secretKey","");
$bucket = $params->get("bucket","");
$domain = $params->get("domain","");

$needConfig = false;
if($ak=="" || $sk=="" || $bucket=="" || $domain=="")
{
	$needConfig=true;
}
$needConfig = false;
?>
<div class="page-container">
	<?php echo $this->loadTemplate("upload_set");?>
	<div class="wrapper zuploader-container">
		<?php if($needConfig):?>
			<div class="alert alert-warning">	
				<p>系统检查到你的七牛CDN没有配置正常，请先配置，然后才能将资源上传到七牛CDN上。</p>
				<p>配置视频教程：<a href="http://www.zmax99.com/menu-cn-cooperation-item/joomla-component/joomla-zmaxcdn" target="_blank">配置视频教程</a></p>	
				<p>请点击如下的链接进行配置：<a href="index.php?option=com_config&view=component&component=com_zmaxcdn" target="_blank">配置CDN参数</a></p>	
			</div>
		<?php endif;?>
		<div class="qiniu-uploader">
			<div class="placeholder zdnd-area-container"  id="container">
				<table id="info-table" class=" table table-bordered "  style="display:none" >
					<thead>
						<td>文件名</td>
						<td>大小</td>
						<td>状态</td>
					</thead>
					<tbody id="fsUploadProgress">
					</tbody>
				</table>
				
				<div id="pickfiles"  class="zpicker-container" href="#">				
					<span class="webuploader-pick"><?php echo $this->config->upload_btn_text;?></span>
				</div>
				<div class="upload_intro_text">
					<?php echo $this->config->drag_text;?>
				</div>	
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(function() {
		var uploader = Qiniu.uploader({
			runtimes: 'html5,flash,html4',
			browse_button: 'pickfiles',
			container: 'container',
			drop_element: 'container',
			max_file_size: FILE_SIZE_LIMIT+'M',
			extensions: FILE_TYPE_LIMIT,
			flash_swf_url: 'components/com_zmaxcdn/libs/7niu/Moxie.swf',
			dragdrop: true,
			chunk_size: '4mb',
			uptoken_url: 'index.php?option=com_zmaxcdn&task=upload.getUptoken',
			domain: YOU_QINIU_DOMAIN,
			auto_start: true,			
			init: {
				'FilesAdded': function(up, files) {							
					 jQuery('#info-table').show();             
					 plupload.each(files, function(file) {
						var progress = new FileProgress(file, 'fsUploadProgress');						
						progress.setStatus("等待...");
						progress.bindUploadCancel(up);
					});
				},
				'BeforeUpload': function(up, file) {
				},
				'UploadProgress': function(up, file) {
					var progress = new FileProgress(file, 'fsUploadProgress');
					var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
					progress.setProgress(file.percent + "%", file.speed, chunk_size);
				},
				'UploadComplete': function() {
				},
				'FileUploaded': function(up, file, info) {
					var progress = new FileProgress(file, 'fsUploadProgress');
					//progress.setComplete(up, info);
				},
				'Error': function(up, err, errTip) {
					switch(err.code)
					{
						case plupload.FILE_SIZE_ERROR:
							alert("上传文件的大小超过了指定的大小!文件大小不超过"+FILE_SIZE_LIMIT+"M"); 
							break;
						default :
							alert(errtip);
							break;						
					}
				}
			}
		});

		uploader.bind('FileUploaded', function(up ,file ,info) 
		{
			try{				
				var domain = up.getOption('domain');
				
				 var res = jQuery.parseJSON(info.response);
				 //alert(res.key);
				 if(res.key == undefined)
				 {
					res.key=file.name;
				 }
				 var cdnPath = domain + res.key;// 获取上传成功后的文件的Url			 
				 var name = file.name;
				 var type=file.type;
				 var size = file.size;
				 var catid = jQuery("#select_catid").val();
				 jQuery.ajax({
					type:'post',
					url:'index.php?option=com_zmaxcdn&task=<?php echo $this->escape($this->task);?>&uploader=cdn&function=<?php echo $this->escape($this->function);?>&caller=<?php echo $this->escape($this->caller);?>',
					data:{
						catid:catid,
						cdnPath:cdnPath,
						name:name,
						type:type,
						size:size,
					},
					cache:false,
					success:function(data){
						eval(data);
					},
					error:function()
					{					
						alert("添加失败，Ajax异常，请联系支持团队：Email:zhang19min88@163.com");
					}		
					
			});
			 }catch(e)
			 {
				alert(e.message);
			 }
		});
		jQuery('#container').on(
			'dragenter',
			function(e) {
				e.preventDefault();
				jQuery('#container').addClass('draging');
				e.stopPropagation();
			}
		).on('drop', function(e) {
			e.preventDefault();
			jQuery('#container').removeClass('draging');
			e.stopPropagation();
		}).on('dragleave', function(e) {
			e.preventDefault();
			jQuery('#container').removeClass('draging');
			e.stopPropagation();
		}).on('dragover', function(e) {
			e.preventDefault();
			jQuery('#container').addClass('draging');
			e.stopPropagation();
		});


	});

</script>	

	
	