<?php 
/**
 *	description:ZMAX媒体管理组件 资料导入布局文件
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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
$option = JRequest::getCmd('option');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>
<div class="container-fluid container-main">
	<section id="content">
		<div class="span12">
			<div id="system-message-container">
			</div>
			<form action="<?php echo JRoute::_('index.php?option=com_zmaxcdn&layout=edit&id='.(int)$this->item->id);?>" method="post" name="adminForm" class="form-validate" id="item-form"  enctype="multipart/form-data">
				<div class="form-horizontal">
					
					<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'main')); ?>
					<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
					<?php echo JHtml::_('bootstrap.addTab', 'myTab', $fieldset->name, JText::_($fieldset->label, true)); ?>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid form-horizontal-desktop">
								<?php echo  $this->form->renderFieldset($fieldset->name);?>
							</div>
						</div>
					</div>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php endforeach; ?>
				</div>
				
				<div>
					<input type="hidden" name="option" value="<?php echo $option;?>"/>
					<input type="hidden" name="task" value=""/>
					<input type="hidden" name="id" value="<?php echo  $this->item->id;?>" />
					<?php echo JHtml::_('form.token');?>
				</div>
			</form>
		</div>
	</section>
</div>

	