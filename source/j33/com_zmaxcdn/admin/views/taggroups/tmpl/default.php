<?php 
/**
 *	description:ZMAX媒体管理组件 标签组列表布局文件
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


$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$option = JRequest::getCmd('option');
$view = JRequest::getCmd('view');

//load tooltip behavior
JHtml::_('behavior.tooltip');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');

$title = JHTML::_('searchtools.sort',JText::_("标题"),'title',$listDirn,$listOrder);
$desc = JHTML::_('searchtools.sort',JText::_("描述"),'description',$listDirn,$listOrder);
$tags = JHTML::_('searchtools.sort',JText::_("标签"),'tags',$listDirn,$listOrder);

$uri = JUri::getInstance();

?>

<form action="<?php echo $uri->toString();?>" method="post" name="adminForm" id="adminForm">  
    <?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>

	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));?>
	
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="table table-striped" id="itemslist">
			<thead>
				<th width="20px"><?php echo JHtml::_('grid.checkall'); ?></th>				
				<th> <?php echo $title;?> </th>
				<th> <?php echo $desc;?> </th>
				<th> <?php echo $tags;?> </th>
			</tr>
		<?php
			$n = 0;
			foreach ($this->items as $item):
			$checked = JHTML::_('grid.id',$n,$item->id);
			$titleLink = JHTML::_('link','index.php?option=com_zmaxcdn&task=taggroup.edit&id='.$item->id,$item->title);	
		 ?>
			 <tr class="<?php echo "row".$n%2; ?>">
				<td><?php echo $checked;?></td>
				<td><?php echo $titleLink;?></td>
				<td><?php echo $item->description;?></td>
				<td><?php echo $item->tags;?></td>
			 </tr>
		 <?php $n++;?>
		 <?php endforeach;?>
		</table>	
	<?php endif;?>
	<?php  echo $this->pagination->getListFooter();?>
	</div>
		
	<div>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="option" value="<?php echo $option;?>"/>
			<input type="hidden" name="view" value="<?php echo $view;?>"/>
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token');?>
	</div>	
</form>





	
	