<?php 
/**
 *	description:ZMAX媒体管理组件 配置视图
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

class zmaxcdnViewConfig extends JViewLegacy
{
	 public function display($tpl = null)	 
	 {
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		
		$isNew = ($this->item->id < 1);
		if($isNew)
		{
			JToolBarHelper::title( JTEXT::_( 'COM_ZMAXCDN_FIELD_ADDNEW') , 'generic.png');
		}
		else
		{
			JToolBarHelper::title( JTEXT::_( 'COM_ZMAXCDN_FIELD_MODIFY') , 'generic.png');
		}
		
		JToolBarHelper::cancel('config.cancel',$isNew ? 'JTOOLBAR_CANCEL':'JTOOLBAR_CLOSE');			
		JToolBarHelper::title(JText::_("ZMAX媒体管理 - 新建或者编辑配置"));
		JToolBarHelper::apply('config.apply');
		JToolBarHelper::save('config.save');
		JToolBarHelper::save2new('config.save2new');
		
		JRequest::setVar('hidemainmenu' , true);
		parent::display($tpl);
	 }
	 
}