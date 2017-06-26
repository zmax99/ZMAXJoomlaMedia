<?php
/**
 *	description:ZMAX媒体管理 ZMAX资源字段
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2016-04-19
 *  @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('You Can Not Access This File!');

JHtml::_('jquery.framework');
$this->listOrder = $this->state->get('list.ordering');
$this->listDirn = $this->state->get('list.direction');


$doc = JFactory::getDocument();
$doc->addStyleSheet("media/zmaxcdn/assets/zmaxcdn.css");
$doc->addStyleSheet("media/zmaxcdn/assets/ui.css");
$doc->addScript("media/zmaxcdn/assets/zmaxcdn.js");
$js ='var SITEBASE ="'.JUri::root().'"';
$doc->addScriptDeclaration($js);

?>

<form action="<?php echo JUri::getInstance()->toString();?>" method="post" name="adminForm" id="adminForm" class="forminline" enctype="multipart/form-data">
	
	<?php
		//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>		
		<?php echo $this->loadTemplate('items');?>
	<?php endif;?>
		

	<div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token');?>
	</div>	
</form>