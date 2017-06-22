<?php 
/**
 *	description:ZMAX媒体管理组件 分类布局文件
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

$option = JRequest::getCmd('option');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo 'index.php?option=com_zmaxcdn&task=category.ajaxSave&'.JSession::getFormToken()."=1";?>" method="post" name="adminForm" id="adminForm" class="layui-form layui-form-pane">
		<div class="form-horizontal">	
			<fieldset class="adminform" >
				<?php echo $this->form->renderFieldset('category'); ?>
			</fieldset>
		</div>
	<div>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token');?>
	</div>
</form>


		
		
	
