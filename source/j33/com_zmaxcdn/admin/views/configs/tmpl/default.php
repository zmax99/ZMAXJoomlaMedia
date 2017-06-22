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

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');

$title = JHTML::_('searchtools.sort',JText::_("标题"),'title',$listDirn,$listOrder);
$desc = JHTML::_('searchtools.sort',JText::_("描述"),'description',$listDirn,$listOrder);
$client = JHTML::_('searchtools.sort',JText::_("应用范围"),'client',$listDirn,$listOrder);
$default = JHTML::_('searchtools.sort',JText::_("默认"),'default',$listDirn,$listOrder);
$id = JHTML::_('searchtools.sort',JText::_("ID"),'id',$listDirn,$listOrder);
?>

<form action="<?php echo JRoute::_('index.php?option=com_zmaxcdn&view=config');?>" method="post" name="adminForm" id="adminForm">  
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
		<table class="table table-striped" >
			<thead>
				<th width="20px"><?php echo JHtml::_('grid.checkall'); ?></th>				
				<th> <?php echo $title;?> </th>
				<th> <?php echo $desc;?> </th>
				<th> <?php echo $client;?> </th>
				<th> <?php echo $default;?> </th>
				<th> <?php echo $id;?> </th>				
			</thead>
		<?php
			$n = 0;
			foreach ($this->items as $item):
			$checked = JHTML::_('grid.id',$n,$item->id);
			$titleLink = JHTML::_('link','index.php?option=com_zmaxcdn&task=config.edit&id='.$item->id,$item->title);	
		 ?>
			 <tr class="<?php echo "row".$n%2; ?>">
				<td><?php echo $checked;?></td>
				<td><?php echo $titleLink;?></td>
				<td><?php echo $item->description;?></td>
				<td><?php echo $item->client=="admin"?"后台":"前台";?></td>
				<td><?php echo $item->default?"是":"否";?></td>
				<td><?php echo $item->id;?></td>
			 </tr>
		 <?php $n++;?>
		 <?php endforeach;?>
		</table>	
	<?php endif;?>
	<?php  echo $this->pagination->getListFooter();?>
	</div>
		
	<div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token');?>
	</div>	
</form>





	
	