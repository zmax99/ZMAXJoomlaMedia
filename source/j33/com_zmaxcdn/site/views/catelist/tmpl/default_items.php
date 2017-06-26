<?php
/**
 *	description:ZMAX媒体管理组件 项目列表布局文件
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-11-03
 */
defined('_JEXEC') or die('You Can Not Access This File!');
$i = 0;
$params = JFactory::getApplication()->getParams();
$colTotal = 0;//统计其他栏目的输出
?>
<div class="zmaxui-row">
	<?php foreach($this->items as $cate):?>	
		<?php if($cate && count($cate)):?>
			<div ><a href="index.php?option=com_zmaxcdn&view=itemlist&id=<?php echo $cate[0]->category;?>"><?php echo $cate[0]->category;?></a></div>
		<?php endif;?>
	<?php endforeach;?>
</div>
