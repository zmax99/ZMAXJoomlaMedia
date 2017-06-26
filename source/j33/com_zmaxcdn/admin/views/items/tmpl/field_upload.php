<?php
/**
 *	description:ZMAXCDN 项目列表布局文件
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-11-08
 */
defined('_JEXEC') or die('You Can Not Access This File!');
jimport('joomla.html.html.tabs');

$params = JComponentHelper::getParams("com_zmaxcdn");		
$yourQiNiuDomain = $params->get("domain");
$yourQiNiuDomain = trim($yourQiNiuDomain);
$yourQiNiuDomain = "http://".$yourQiNiuDomain."/";

?>
<?php 
	$options = array(
    'startOffset' => 1,  // 0 starts on the first tab, 1 starts the second, etc...
    'useCookie' => true, // this must not be a string. Don't use quotes.
	);			
	echo JHtml::_('tabs.start' ,'tab_ground_id' ,$options);
 
	if($this->config->show_local_upload)
	{
		echo JHtml::_('tabs.panel' ,JText::_('上传到本地服务器'),'panel_1_id');
		echo $this->loadTemplate("upload_server");
	}
	
	
	 if($this->config->show_qiniu_upload)
	 {
		echo JHtml::_('tabs.panel' ,JText::_('上传到七牛CDN'),'panel_2_id');
		echo $this->loadTemplate("upload_remote");	
	 }
	
	echo JHtml::_('tabs.end');
?>
<h4 class="text-right"><small>文件在上传完成后将自动被选中</small></h4>

<!--  ZMAX媒体管理组件的 全局配置文件-->
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
</script>

					



