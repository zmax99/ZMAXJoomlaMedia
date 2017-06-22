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
 //webUpload CSS + JS
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root()."administrator/components/com_zmaxcdn/libs/webuploader/wup.css");
$doc->addScript(JUri::root()."administrator/components/com_zmaxcdn/libs/webuploader/webuploader.min.js");
$doc->addScript(JUri::root()."administrator/components/com_zmaxcdn/libs/webuploader/wup.js");
?>

<div class="page-container">
	<?php echo $this->loadTemplate("upload_set");?>
	<div  id="uploader" class="zuploader-container">
		<div class="queueList">
			<div class="placeholder zdnd-area-container" id="dndArea">
				<div id="filePicker" class="webuploader-container zpicker-container">
					<div class="webuploader-pick "><?php echo $this->config->upload_btn_text;?></div>
				</div>
				<div class="upload_intro_text">
					<?php echo $this->config->drag_text;?>
				</div>
			</div>
		</div>
	</div>
</div>