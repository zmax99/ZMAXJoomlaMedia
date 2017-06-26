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

JHtml::_('jquery.framework');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$this->function = JRequest::getCmd('function','');
$params = JComponentHelper::getParams("com_zmaxcdn");		
$yourQiNiuDomain = $params->get("domain");
$yourQiNiuDomain = trim($yourQiNiuDomain);
$yourQiNiuDomain = "http://".$yourQiNiuDomain."/";

$app = JFactory::getApplication();
$clientClass = $app->isAdmin()?"zadmin":"zsite";

$doc = JFactory::getDocument();

$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/zmaxcdn.css");
$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/ui.css");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/layui/lay/dest/layui.all.js");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/zmaxcdn.js");

//临时调试使用
$css='';
if($css){
	$doc->addStyleDeclaration($css);	
}


//得到配置设置
$session = JFactory::getSession();
$config = $session->get("zmaxcdn.field.config");
$config = json_decode($config);
$this->config= $config;
$this->function = trim($this->function,'"');
if($this->function=="")
{
	$this->function = $session->get("zmaxcdn.field.function","selectItem1");	
}
else
{
	$session->set("zmaxcdn.field.function",$this->function);	
}
$this->task = $session->get("zmaxcdn.field.task","upload.uploadAndInsert");
$this->caller = JFactory::getApplication()->input->get("caller",'plugin',"STRING");


$url = Juri::getInstance()->toString();
$user = JFactory::getUser();
$treeCategory = new zmaxtreeCategory();
$items =$treeCategory->loadCategory("com_zmaxcdn",$user->id,1,1,$this->category_id);


//echo "<pre>";
//	print_r($items);
//echo "</pre>";


?>
<script type="text/javascript">
// 添加全局站点信息
var BASE_URL = '<?php echo JURI::root();?>components/com_zmaxcdn/libs/webuploader/';
var DOMAIN_URL = '<?php echo JURI::root();?>';	
var SERVER_URL ='index.php?option=com_zmaxcdn&task=<?php echo $this->escape($this->task);?>&uploader=server&function=<?php echo $this->escape($this->function);?>&caller=<?php echo $this->escape($this->caller);?>';
var UPLOADER_VIEW = 'field';
var FILE_SIZE_LIMIT = '<?php echo $this->config->max_size;?>';
var FILE_TYPE_LIMIT = '<?php echo $this->config->file_type;?>';
var UPLOAD_BTN_TEXT = '<?php echo $this->config->upload_btn_text;?>';
var YOU_QINIU_DOMAIN = '<?php echo $yourQiNiuDomain;?>';
var ZMAX_SYSTEM_AJAX_TOKEN='<?php echo JSession::getFormToken()."=1"; ?>';
</script>




<form action="<?php echo $url;?>" method="post" name="adminForm" id="adminForm" class="<?php echo $clientClass;?> forminline" enctype="multipart/form-data">
<div class="layui-tab layui-tab-brief">
  <ul class="layui-tab-title">
    <li class="layui-this">媒体库</li>
    <li>上传文件</li>
    <li class="system-zmax-todo">管理</li>
  </ul>
  <div class="layui-tab-content">
	<div class="layui-tab-item layui-show">
		<!--从媒体库中选择 -->
		<div class="ztree-container">
			<div  class="zmaxui-row">
				<div class="zmaxui-col-md-2 ztree-dir-container">
					<div class="zdir-container">
						<div class="zdir" >
							<!-- 目录树 -->
							<ul id="tree-category"></ul>
							<hr />
							<!-- 新增目录-->
							<a href="javascript:void(0)" url="index.php?option=com_zmaxcdn&view=category&layout=edit&tmpl=component" class="system-zmax-modal"><i class="layui-icon">&#xe654;</i> 添加新分类</a>
						</div>
					</div>
				</div>
				<div class="zmaxui-col-md-10">
					<div class="ztree-list-container">
						<div class="zlist">	
							<div class="zmaxui-row">
								<div class="items-container">
									<div class="zmaxui-col-md-9">
										<div class="items" >
											<?php echo $this->loadTemplate('item_filter');?>
											<?php echo $this->loadTemplate('items');?>
											<?php  echo $this->pagination->getListFooter();?>
										</div>
									</div>
									<div class="detail-container">
										<div class="zmaxui-col-md-3">
											<div class="detail">
												<?php echo $this->loadTemplate('item_detail');?>
											</div>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
					<div class="ztree-list-footer-container">
						<div class="zmaxui-row">
							<div class="zmaxui-col-md-9">
								<div class="zselected-container">
									<div class="">
										已选择<span class="system-no-container">0</span>个<br/>
										<span><a href="javascript:void(0);" class="system-clear-selected layui-disabled">清空</a></span>
									</div>
								</div>
							</div>
							<div class="zmaxui-col-md-3">
								<div class="btn btn-primary pull-right system-insert-btn insert-btn layui-disabled " function="window.parent.<?php echo $this->function;?>" data="" >
									插入至文章
								</div>
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="layui-tab-item">
		<div class="layui-tab layui-tab-card">
		  <ul class="layui-tab-title">
			<?php if($this->config->show_local_upload):?>
				<li class="layui-this">上传到服务器</li>
			<?php endif;?>
			
			<?php  if($this->config->show_qiniu_upload):?>
			<li>上传到七牛</li>
			<?php endif;?>
		  </ul>
		  <div class="layui-tab-content">
			<div class="layui-tab-item layui-show">
			<?php 
			if($this->config->show_local_upload)
			{
				echo $this->loadTemplate("upload_server");
			}
			?>
			</div>
			<div class="layui-tab-item">
			<?php 
			 if($this->config->show_qiniu_upload)
			 {
				echo $this->loadTemplate("upload_remote");	 
			 }
			?>
			</div>
		  </div>
		</div>
	</div>
    <div class="layui-tab-item">
	<div class="layui-tab layui-tab-card">
		  <ul class="layui-tab-title">
			<li class="layui-this">上传设置</li>
			<li>缩略图设置</li>
		  </ul>
		  <div class="layui-tab-content">
			<div class="layui-tab-item layui-show">
				<div class="info-container"><p>专业版提供参数设置</p></div>
			</div>
			<div class="layui-tab-item">
				<div class="info-container"><p>专业版提供参数设置</p></div>
			</div>
		  </div>
		</div>
	</div>
	
	</div>
  </div>
</div> 
<?php echo JHtml::_('form.token');?>     
</form>	

<script>
layui.tree({
	  elem: '#tree-category' //传入元素选择器
	  ,nodes: <?php echo json_encode($items);?>
	});		
</script> 


				
