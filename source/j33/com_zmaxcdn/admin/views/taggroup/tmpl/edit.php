<?php 
/**
 *	description:ZMAX媒体管理组件 标签布局文件
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
$url = JUri::getInstance();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'taggroup.cancel' || document.formvalidator.isValid(document.id('field-form')))
		{
			Joomla.submitform(task, document.getElementById('field-form'));
		}
	}
</script>

<form action="<?php echo $url->toString();?>" method="post" name="adminForm" class="form-validate" id="field-form"  enctype="multipart/form-data">
	<?php // echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div class="form-horizontal">	
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
	
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('字段组详细', true)); ?>
		
		<div class="row-fluid">
			<div class="span6">
				
				<fieldset class="adminform" >
					<?php echo $this->form->renderFieldset('category'); ?>
				</fieldset>
			</div>
	
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
	</div>
	
	<div>
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="id" value="<?php echo  $this->item->id;?>" />
		<?php echo JHtml::_('form.token');?>
	</div>
</form>
