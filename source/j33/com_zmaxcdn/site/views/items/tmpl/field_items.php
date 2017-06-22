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
?>
<div class="zitemlist-container">
<?php if (empty($this->items)) : ?>
	<div class="alert alert-no-items">
		<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	</div>
<?php else : ?>		
	<ul>
		<?php foreach ($this->items as $item):?>
		<li class="zitem-container zmaxfield-insert"  title="<?php echo $this->escape($item->title);?>">	
			<div class="zitem"   data="<?php echo $item->id;?>" >
				<a class="zimg-preview">
					<div class="zimg-thumb">
						<div class="zimg-thumb-inside">
							<?php echo zmaxcdnItemHelper::getItemPreviewHtml($item ,false);;?>
						</div>
					</div>
					<div class="zimg-detail small">
						<?php  echo mb_strimwidth($item->title,0,15,"...");?>	
					</div>
				</a>
			</div>
		</li>
		<?php endforeach;?>
	</ul>
<?php endif;?>
</div>
