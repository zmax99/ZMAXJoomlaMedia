<?php 
/**
 *	description:ZMAX媒体管理组件 控制面板视图
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

class zmaxcdnViewMain extends JViewLegacy
{
    public  function display($tpl = null)	 
	{
		$this->addToolBar();
		
		$this->cpInfo = $this->get("ComponentInfo");
		$this->sysInfo = $this->get("SystemInfo");
		if ($this->getLayout() !== 'modal')
		{
			zmaxcdnHelper::addSubmenu('main');
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}
	 
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_("ZMAX媒体管理 - 控制面板"),"dashboard");
		JToolBarHelper::preferences('com_zmaxcdn');
	}  
}