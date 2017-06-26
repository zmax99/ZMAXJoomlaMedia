<?php 
/**
 *	description:ZMAX媒体管理组件 控制面板布局文件
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

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/zmaxcdn.css");
$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/ui-dialog.css");
$doc->addStyleSheet(JUri::root()."/media/zmaxcdn/assets/ui.css");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/layui/lay/dest/layui.all.js");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/zmaxcdn.js");
$doc->addScript(JUri::root()."/media/zmaxcdn/assets/dialog-min.js");


$cpInfo = $this->cpInfo;
$sysInfo = $this->sysInfo;
?>

<form action="<?php echo JRoute::_('index.php?option=com_zmaxcdn');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>
		<section>
			<div class="row-fluid">				
				<div class="span12">
					<div class="well well-small">
						<div class="module-title nav-header"><i class="icon-dashboard"></i> <?php echo JText::_('系统信息') ?> <small>点击问号了解更多</small></div>
						<hr class="hr-condensed">
						<div class="row-fluid">
							<div class="span6">
								<dl class="dl-horizontal">
									<dt>编辑器按钮插件 <span class="zmaxtip" data="该插件可以在编辑下方的按钮区域增加一个按钮，以便你使用ZMAX媒体管理组件来插入资源到文章中"><a href="javascript:void(0)" title="点我了解更多信息" ><i class="icon icon-help"></i></a></span>:</dt>
									<dd>
										<?php if($sysInfo->insertbtnPlg):?>
											启用
										<?php else:?>
											<font style="color:red">禁用</font>
										<?php endif;?>
									</dd>
									<dt>图片选择插件 <span class="zmaxtip" data="该插件可以让文章编辑页面的所有系统默认的图片选择器都使用ZMAX媒体管理组件来取代"><a href="javascript:void(0)" title="点我了解更多信息" ><i class="icon icon-help"></i></a></span>:</dt>
									<dd>
										<?php if($sysInfo->fieldPlg):?>
											启用
										<?php else:?>
											<font style="color:red">禁用</font>
										<?php endif;?>
									</dd>
									<dt>资源下载插件 <span class="zmaxtip" data="该插件允许你在文章中插入word文档，Excel文档等等其他的文档，并且可供前台用户下载"><a href="javascript:void(0)" title="点我了解更多信息" ><i class="icon icon-help"></i></a></span>:</dt>
									<dd>
										启用
									</dd>
									<dt>播放视频插件 <span class="zmaxtip" data="该插件允许你在文章中插入视频"><a href="javascript:void(0)" title="点我了解更多信息" ><i class="icon icon-help"></i></a></span>:</dt>
									<dd>
										启用
									</dd>
								</dl>
							</div>
							<div class="span6">
								<dl class="dl-horizontal">
									<dt>服务器上传设置 <span class="zmaxtip" data="为了让插件正常工作，你的服务器的php必须满足下面的设置,你可以修改php.ini中对应变量的值来做调整。<br/>
									    <b>file_uploads</b> 设置为true 或者 1 表示允许上传文件<br/>
										<b>upload_max_filesize</b> 这个值默认为2M,如果你要上传超过2M的文件，请增加这个值 <br/>
										<b>post_max_size</b> 这个值必须不小于upload_max_filesize .否则 upload_max_filesize设置将不起作用<br/>
										<hr />
										<b>温馨提示：当组件不能上传文件的时候，一般都是upload_max_filesize的值太小，导致上传失败!</b>
										
									"><a href="javascript:void(0)" title="点我了解更多信息" ><i class="icon icon-help"></i></a></span>:</dt>
									<dd>
										<b>允许上传：</b>
											<?php $uploads = ini_get("file_uploads");?>	
											<?php if($uploads):?>
												<span class="text-info">允许</span>
											<?php else:?>
												<font style="color:red">不允许</font>
											<?php endif;?>
											<br/>
										<b>上传文件大小：</b>
											<?php $uploadMaxFilesize = ini_get("upload_max_filesize");?>
											<span class="text-info"><?php echo $uploadMaxFilesize;?></span>
											<br/>
										<b>POST给PHP的最大值：</b>
											<?php $postMaxSize = ini_get("post_max_size");?>
											<span class="text-info"><?php echo $postMaxSize;?></span>
											<br/>
										<?php $uploadSize = min($uploadMaxFilesize ,$postMaxSize);?>	
										<b>综合评估：</b>
											<span class="text-warning">你的服务器允许你上传文件的最大文件为<?php echo $uploadSize;?></span>
											<br/>
									</dd>
									<dt>服务器文件夹权限 <span class="zmaxtip" data="服务器的文件夹的权限，有时候如果文件夹的权限不可写，也会导致上传失败"><a href="javascript:void(0)" title="点我了解更多信息" ><i class="icon icon-help"></i></a></span>:</dt>
									<dd>
										<b>资源文件夹ZMAXCDN的权限：</b>
										<?php $fileName = JPATH_ROOT."/media/zmaxcdn";?>
										
											<?php if(is_readable($fileName)):?>											
												可读
											<?php else:?>
												<font style="color:red">不可读</font>
											<?php endif;?>
											，
											<?php if(is_writeable($fileName)):?>
												可写
											<?php else:?>
												<font style="color:red">不可写</font>
											<?php endif;?>										
									</dd>									
								</dl>
							</div>							
						</div>
						<div class="module-title nav-header"><i class="icon-list"></i> <?php echo JText::_('功能介绍') ?></div>
						<hr class="hr-condensed">												
						<dl class="dl-horizontal">
							<dt>改进系统媒体管理:</dt>
							<dd>
								本扩展可以取代Joomla系统的媒体管理。上传文件和插入文件一步到位
							</dd>
							<dt>插入文件更加容易:</dt>
							<dd>
								内置搜索功能，让你迅速定位你想要插入的资源
							</dd>
							<dt>改进系统不支持中文名称:</dt>
							<dd>
								本扩展可以支持中文名称资源(图片，视频，压缩包等等)以及任何其他字符的名称
							</dd>
							<dt>内部集成七牛CDN加速:</dt>
							<dd>
								系统会自动将你网站的静态内容同步到你的七牛空间，让你的Joomla网站飞起来！
							</dd>
							<dt>批量添加:</dt>
							<dd>
								在随后的版本中我们会实现批量添加的功能，让你不再为joomla网站插入图片而头痛
							</dd>
						</dl>		
					</div>
				</div>
			</div>
		</section>
		<section class="content-block" id="zmaxlogin" role="main">
				<div class="row-fluid">
					<div class="span7">
						<div class="well well-small">
							<div class="module-title nav-header"><i class="icon-star"></i> <?php echo JText::_('欢迎使用ZMAX资源管理系统') ?></div>
							<hr class="hr-condensed">
							<div id="dashboard-icons" class="btn-group">
								
								<a class="btn" href="index.php?option=com_zmaxcdn&view=items">
									<img src="../media/zmaxcdn/assets/images/items.png" alt="<?php echo JText::_('资源管理') ?>" /><br/>
									<span><?php echo JText::_('资源管理') ?></span>
								</a>
								<a class="btn" href="index.php?option=com_categories&view=categories&extension=com_zmaxcdn">
									<img src="../media/zmaxcdn/assets/images/categories.png" alt="<?php echo JText::_('类别管理') ?>" /><br />
									<span><?php echo JText::_('类别管理') ?></span>
								</a>
								<a class="btn" href="index.php?option=com_zmaxcdn&view=configs">
									<img src="../media/zmaxcdn/assets/images/shezhi.png" alt="<?php echo JText::_('上传设置') ?>" /><br />
									<span><?php echo JText::_('上传设置') ?></span>
								</a>
								<a class="btn" href="index.php?option=com_config&view=component&component=com_zmaxcdn">
									<img src="../media/zmaxcdn/assets/images/config.png" alt="<?php echo JText::_('系统设置') ?>" /><br />
									<span><?php echo JText::_('系统设置') ?></span>
								</a>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="well well-small">
							<div class="module-title nav-header"><i class="icon-help"></i><?php echo JText::_('帮助资源') ?></div>
							<hr class="hr-condensed">
							<div id="dashboard-icons" class="btn-group">
								
								<a class="btn" href="http://www.joomlachina.cn/shipin-jiaocheng" target="_blank">
									<img src="../media/zmaxcdn/assets/images/video.png" alt="<?php echo JText::_('视频教程') ?>" /><br/>
									<span><?php echo JText::_('视频教程') ?></span>
								</a>
								<a class="btn" href="http://www.zmax99.com/forum/index" target="_blank">
									<img src="../media/zmaxcdn/assets/images/bug.png" alt="<?php echo JText::_('BUG反馈') ?>" /><br />
									<span><?php echo JText::_('BUG反馈') ?></span>
								</a>
								<a class="btn" href="http://www.zmax99.com/forum/index" target="_blank">
									<img src="../media/zmaxcdn/assets/images/advice.png" alt="<?php echo JText::_('改进建议') ?>" /><br />
									<span><?php echo JText::_('改进建议') ?></span>
								</a>
								<a class="btn" href="http://www.zmax99.com" target="_blank">
									<img src="../media/zmaxcdn/assets/images/develop.png" alt="<?php echo JText::_('开发者网站') ?>" /><br />
									<span><?php echo JText::_('开发者网站') ?></span>
								</a>
								<a class="btn" href="http://www.zmax99.com" target="_blank">
									<img src="../media/zmaxcdn/assets/images/extension.png" alt="<?php echo JText::_('扩展') ?>" /><br/>
									<span><?php echo JText::_('扩展') ?></span>
								</a>
				
							</div>
							<div class="clearfix"></div>
						</div>
					</div>

					<div class="span5">
						<div class="well well-small">
							<div class="center">
								<img src="../media/zmaxcdn/assets/images/zmax_logo.png" / title="ZMAX程序人，中国专业的Joomla扩展开发商!">
							</div>
							<hr class="hr-condensed">
							<dl class="dl-horizontal">
								<dt><?php echo JText::_('版本:') ?></dt>
								<dd><?php echo $cpInfo->version;?></dd>
								<dt>日期:</dt>
								<dd><?php echo $cpInfo->creationDate;?></dd>
								<dt>作者:</dt>
								<dd><a href="<?php echo $cpInfo->authorUrl;?>" target="_blank"><?php echo $cpInfo->author;?></a></dd>
								<dt>版权:</dt>
								<dd>&copy;<?php echo $cpInfo->copyright;?></dd>
								<dt>许可:</dt>
								<dd>GNU General Public License</dd>
							</dl>
						</div>
					</div>
				</div>
			</section>
		
		</div>
	<div>
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token');?>
	</div>
</form>
