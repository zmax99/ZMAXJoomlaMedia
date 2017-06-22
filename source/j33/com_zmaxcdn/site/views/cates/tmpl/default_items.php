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



<div class="zmaxui">
	<div class="zmax-row">
		<?php foreach($this->items as $cate):?>	
			<?php if($cate && count($cate)):?>
			  <?php $i++;?>
			  <div class="zmax-col-md-6">
				<div class="zmax-down-cate">
					<h3 class="down-cate"><?php echo $cate[0]->category;?></h3>
					
					<?php // 获得分类的图片
						$cateParams = $cate[0]->params;
						$cateParams = json_decode($cateParams);
						$defaultCateImage = ""; //在这里指定一个默认的图片 默认的图片
						$cateImg = $defaultCateImage;
						if($cateParams)
						{
							if($cateParams->image)
							{
								$cateImg = $cateParams->image; //指定的图片
							}
							
						}
						//在这里查看新的分类图片的信息
						//echo "<pre>";
						//	print_r($cateParams);
						//echo "</pre>";				
						//echo $cateImg ;
					?>
					
					<div class="zmax-row">
						<div class="items-info">
						<?php foreach($cate as $item):?>
			
							<!--  标题-->
							<?php if($params->get('show_title','1' )):?>
							<div class="zmax-col-md-6 item-title"><?php echo mb_strimwidth($item->title ,0 ,$params->get('max_length','25' ),'...');?></div>		
							<?php endif;?>
							
							
							<!-- 文件日期 -->
							<?php if($params->get('show_date','0')):?>
							<div class="zmax-col-md-2"><?php echo  JHTML::_('date' ,$item->date , JText::_('Y-m-d'));?></div>
							<?php $colTotal++; ?>
							<?php endif;?>
						
							<!-- 文件描述 -->
							<?php if($params->get('show_desc','0')):?>
							<div class="zmax-col-md-2"><?php echo $item->description;?></div>
							<?php $colTotal++; ?>
							<?php endif;?>
						
							<!-- 文件分类 -->
							<?php if($params->get('show_category','0')):?>
							<div class="zmax-col-md-2"><?php echo $item->category;?></div>
							<?php $colTotal++; ?>
							<?php endif;?>
							
							<!-- 文件大小 -->
							<?php if($params->get('show_filesize','0')):?>
							<div class="zmax-col-md-2"><?php echo zmaxcdnHelper::formatFileSize($item->size);?></div>
							<?php $colTotal++; ?>
							<?php endif;?>
							
							<!-- 文档类型 -->
							<?php if($params->get('show_doc_type','0')):?>
							<div class="zmax-col-md-2"><?php echo zmaxcdnHelper::formatDocType($item->doc_type);?></div>
							<?php $colTotal++; ?>
							<?php endif;?>
							
							<!-- 下载按钮 -->
							<?php if($params->get('show_download','1')):?>
							<?php $downloadCol = 6-$colTotal*2;
								$downloadCol = $downloadCol > 0?$downloadCol:"6";
								$colTotal = 0;
								?>
							<div class="zmax-col-md-<?php echo $downloadCol;?> zmax-text-right"><a href="#" class="zmaxpackage" data-id="<?php echo $item->id;?>" ><i class="fa fa-download"></i>下载</a></div>
							<?php endif;?>
							
						<?php endforeach;?>
						<div class="zmax-col-md-12">
							<div class="zmax-down-cate-readmore zmax-pull-right">
								<a class="down-more"href="index.php?option=com_zmaxcdn&view=items&id=<?php echo $item->catid;?>">更多</a>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>	
			<?php if($i%2==0):?>
				</div><div class="zmax-row">
			<?php endif;?>
			<?php endif;?>
		<?php endforeach;?>
	</div>
</div>
