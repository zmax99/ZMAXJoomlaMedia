<?php 
/**
 *	description:ZMAX媒体管理组件 上传设置布局文件
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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'config.cancel' || document.formvalidator.isValid(document.id('adminForm')))
		{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
</script>

<div class="container-fluid container-main">
	<section id="content">
		<div class="span12">
			<div id="system-message-container">
			</div>
			<form action="<?php echo JRoute::_('index.php?option=com_zmaxcdn&view=config&layout=edit&id='.(int)$this->item->id);?>" method="post" name="adminForm" class="form-validate" id="adminForm"  enctype="multipart/form-data">
				<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
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
					<input type="hidden" name="task" value=""/>
					<input type="hidden" name="id" value="<?php echo  $this->item->id;?>" />
					<?php echo JHtml::_('form.token');?>
				</div>
			</form>
		</div>
	</section>
</div>
