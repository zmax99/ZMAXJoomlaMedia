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
$this->listOrder = $this->state->get('list.ordering');
$this->listDirn = $this->state->get('list.direction');
$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_zmaxcdn/css/system.css");
$doc->addScript("components/com_zmaxcdn/js/zmaxcdn_download.js");
?>
<form action="index.php?option=com_zmaxcdn&view=items" method="post" name="adminForm" id="adminForm" class="forminline" enctype="multipart/form-data">		
		<?php			
			echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>		
		<?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>		
		<div class="zmaxui">
			<table class="zmax-table zmax-table-striped" >
				<thead>
					<?php 
					$title = JHTML::_('searchtools.sort',JText::_("文件"),'name',$this->listDirn,$this->listOrder);
					$category = JHTML::_('searchtools.sort',JText::_("分类"),'category',$this->listDirn,$this->listOrder);
					$size = JHTML::_('searchtools.sort',JText::_("大小"),'size',$this->listDirn,$this->listOrder);
					$type = JHTML::_('searchtools.sort',JText::_("类型"),'doc_type',$this->listDirn,$this->listOrder);
					$date = JHTML::_('searchtools.sort',JText::_("上传时间"),'date',$this->listDirn,$this->listOrder);
					$description = JHTML::_('searchtools.sort',JText::_("说明"),'description',$this->listDirn,$this->listOrder);
					?>			
					<th> <?php echo $title;?></th>				
					<th> <?php echo $category;?> </th>
					<th> <?php echo $size;?> </th>
					<th> <?php echo $date;?></th>	
					<th> <?php echo $description;?></th>
					<th> <?php echo  $type;?></th>
				</thead>
			

				<tbody>
				<?php foreach ($this->items as $item):?>
					<tr>
						<td><a href="#" class="zmaxpackage" data-id="<?php echo $item->id;?>" ><?php echo $this->escape($item->title); ?></a></td>				
						<td><?php echo $item->category;?></td>
						<td><?php echo zmaxcdnCommonHelper::formatFileSize($item->size);?></td>				
						<td><?php echo  JHTML::_('date' ,$item->date , JText::_('Y-m-d'));?></td>
						<td><?php  echo $item->description;?></td>
						<td><?php echo $item->type;?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
		<?php  echo $this->pagination->getListFooter();?>
		<?php endif;?>
	
	
	<div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token');?>
	</div>	
</form>