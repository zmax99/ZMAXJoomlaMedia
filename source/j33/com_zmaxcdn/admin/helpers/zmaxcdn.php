<?php
/**
 *	description:ZMAX媒体管理组件 帮助类实现文件
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2016-04-18
 *  @license GNU General Public License version 3, or later
 *  check date:2016-05-19
 *  checker:min.zhang
 */
defined('_JEXEC') or die('you can not access this file'); 

class zmaxcdnHelper
{
	static public  function addSubmenu($submenu)
	{		
		JHtmlSidebar::addEntry(JText::_('<i class="icon-dashboard"></i> '.'控制面板'),
										'index.php?option=com_zmaxcdn&view=main',$submenu =='main');
		JHtmlSidebar::addEntry(JText::_('<i class="icon-picture"></i> '.'资源管理'),
										'index.php?option=com_zmaxcdn&view=items',$submenu == 'items');
		JHtmlSidebar::addEntry(JText::_('<i class="icon-drawer"></i> '.'分类管理'),
										'index.php?option=com_categories&view=categories&extension=com_zmaxcdn',$submenu == 'categories');
		JHtmlSidebar::addEntry(JText::_('<i class="icon-filter"></i> '.'标签组管理'),
										'index.php?option=com_zmaxcdn&view=taggroups',$submenu == 'taggroups');
		JHtmlSidebar::addEntry(JText::_('<i class="icon-upload"></i> '.'上传设置'),
										'index.php?option=com_zmaxcdn&view=configs',$submenu == 'configs');
		JHtmlSidebar::addEntry(JText::_('<i class="icon-lightning"></i> '.'导入资源'),
										'index.php?option=com_zmaxcdn&view=import',$submenu == 'import');

		$document = JFactory::getDocument();
		if($submenu == 'categories')
		{
			$document->setTitle(JText::_('COM_ZMAXSHOP_HELPER_ADMINISTRATOR_CATEGORIES_TITLE'));	
		}
	}
}
?>