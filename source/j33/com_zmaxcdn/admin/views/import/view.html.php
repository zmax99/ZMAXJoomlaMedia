<?php 
/**
 *	description:ZMAX媒体管理组件 资料导入视图
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

class zmaxcdnViewImport extends JViewLegacy
{
     public function display($tpl = null)	 
	 {
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		JToolBarHelper::title(JText::_("ZMAX媒体管理 - 导入资源"),'lightning');
		JToolBarHelper::cancel('item.cancel','JTOOLBAR_CLOSE');
		JRequest::setVar('hidemainmenu' , true);
		$this->addToolBar();
		parent::display($tpl);
	 }
	 
	 protected function addToolBar()
	 {
		JToolBarHelper::save('import.import',"执行导入");		
	 }
}