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
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/zmaxcdn.css");
$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/ui-dialog.css");
$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/ui.css");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/layui/lay/dest/layui.all.js");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/zmaxcdn.js");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/dialog-min.js");


//得到配置设置
$config = zmaxcdnConfigHelper::loaddefaultConfig();
?>

<form action="<?php echo JRoute::_('index.php?option=com_zmaxcdn&view=items');?>" method="post" name="adminForm"  id="adminForm">
  
    <?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>

	<?php	echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<?php 
			$grid =  JHtml::_('grid.checkall');
			$title = JHTML::_('searchtools.sort',JText::_("文件"),'name',$listDirn,$listOrder);
			$category = JHTML::_('searchtools.sort',JText::_("分类"),'category',$listDirn,$listOrder);
			$size = JHTML::_('searchtools.sort',JText::_("大小"),'size',$listDirn,$listOrder);
			$type = JHTML::_('searchtools.sort',JText::_("类型"),'type',$listDirn,$listOrder);
			$path = JHTML::_('searchtools.sort',JText::_("访问地址"),'path',$listDirn,$listOrder);
			$date = JHTML::_('searchtools.sort',JText::_("上传时间"),'date',$listDirn,$listOrder);
			$description = JHTML::_('searchtools.sort',JText::_("备注"),'description',$listDirn,$listOrder);
			$published = JHTML::_('searchtools.sort',JText::_("状态"),'published',$listDirn,$listOrder);
			$hits = JHTML::_('searchtools.sort',JText::_("下载量"),'hits',$listDirn,$listOrder);
			$id = JHTML::_('searchtools.sort',JText::_("序号"),'id',$listDirn,$listOrder);
			$otherId = JHTML::_('searchtools.sort',JText::_("COM_ZMAXCDN_ITEMS_OTHER_ID"),'other_id',$listDirn,$listOrder);
		?>
		<table class="table table-striped" id="itemslist">
			<thead>
				<th width="20px"><?php echo $grid;?></th>
				<th> <?php echo $title;?> </th>
				<th class="hidden-phone">预览</th>
				<th> <?php echo $size;?> </th>
				<th width="15%" class="hidden-phone">访问地址</th>
				<th>下载</th>				
				<th class="hidden-phone"> <?php echo $date;?></th>	
				<th class="hidden-phone"> <?php echo $description;?></th>
				<th class="hidden-phone"> <?php echo $published;?></th>
				<th class="hidden-phone"> <?php echo $hits;?></th>
				<th> <?php echo $id;?></th>
				<?php if($config->show_other_id):?>
				<th> <?php echo $otherId;?></th>
				<?php endif;?>
			</thead>
			<tbody>
				<?php
				 $n = 0;
				 foreach ($this->items as $item):
				 $checked = JHTML::_('grid.id',$n,$item->id);
				 $titleLink = JHTML::_('link','index.php?option=com_zmaxcdn&task=item.edit&id='.$item->id,mb_strimwidth($item->title,0,25,"..."),array('title'=>$item->title));	
				?>
					 <tr >
						<td><?php echo $checked;?></td>
						<td>
							<?php echo $titleLink;?><span class="small break-word">(<span>类型</span>: <?php echo $item->type;?>)</span>
							<div class="small">类别:<?php echo $item->category?$item->category:"无";?></div>
						</td>
						<td class="hidden-phone"><?php echo zmaxcdnItemHelper::getItemPreviewHtml($item);?></td>
						<td><?php echo zmaxcdnCommonHelper::formatFileSize($item->size);?></td>					
						<td class="hidden-phone"><span class="zmaxtip" data="<?php echo zmaxcdnItemHelper::getItemUrl($item);?>"><?php echo mb_strimwidth(zmaxcdnItemHelper::getItemUrl($item),0,25,'...');?><a href="javascript:void(0)" title="点我了解更多信息" ><i class="icon icon-help"></i></a></span></td>
						<td><?php echo zmaxcdnItemHelper::getItemDownloadHtml($item ,$config);?></td>				
						<td class="hidden-phone"><?php echo  JHTML::_('date' ,$item->date , JText::_('Y-m-d'));?></td>
						<td class="hidden-phone"><?php  echo $item->description;?></td>
						<td class="hidden-phone"><?php echo JHtml::_('jgrid.published', $item->published, $n, 'items.', true, 'cb', null,null); ?></td>
						<td class="hidden-phone"><?php  echo $item->hits;?></td>
						<td><?php  echo $item->id;?></td>
						<?php if($config->show_other_id):?>
						<td><?php  echo $item->other_id;?></td>
						<?php endif;?>
					 </tr>
					 <?php $n++;?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php  echo $this->pagination->getListFooter();?>
	<?php endif;?>
	</div>
	<!-- 批处理 -->	
	<?php echo $this->loadTemplate('batch'); ?>
	<div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token');?>
	</div>	
</form>






	
	