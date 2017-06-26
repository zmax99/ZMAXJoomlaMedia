<?php 
/**
 *	description:ZMAX媒体管理组件 资料列表视图
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

class zmaxcdnViewItems extends JViewLegacy
{
    public function display($tpl = null)	 
	{
		if ($this->getLayout() !== 'modal' && $this->getLayout() !== 'field')
		{
			zmaxcdnHelper::addSubmenu('items');
			$this->sidebar = JHtmlSidebar::render();
		}
		
		$this->items = $this->get('Items');
		$this->pagination =$this->get('Pagination');
		$this->category_id = $this->get("CurCategoryId");
		$this->state =$this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		

		if(count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />',$errors));
			return false;
		}
		
		$this->addToolBar();
		parent::display($tpl);
	}
	 
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_("ZMAX媒体管理 - 资源列表"),'picture');				
		JToolBarHelper::addNew('item.add');
		JToolBarHelper::editList('item.edit');
		JToolBarHelper::deleteList("你确定要删除这个资源吗？在删除记录的同时也会删除存储在服务器上的文件。如果你在其他地方使用过该资源，那么可能会造成资源无法访问。请慎重删除",'items.delete');
		JToolBarHelper::publish('items.publish');
		JToolBarHelper::unpublish('items.unpublish'  );
		
		if (JFactory::getUser()->authorise('core.admin', 'com_zmaxcdn'))
		{
			JToolBarHelper::preferences('com_zmaxcdn');
		}
		
		//添加一个批处理按钮
		JHtml::_('bootstrap.modal', 'collapseModal');
		$title = JText::_('JTOOLBAR_BATCH');
		$layout = new JLayoutFile('joomla.toolbar.batch');
		$dhtml = $layout->render(array('title' => $title));
		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton('Custom', $dhtml, 'batch');
	}
	  
	protected function getSortFields()
	{
		return array(
			'title'     => JText::_('标题'),
			'size'        => JText::_('大小'),
			'id'     => JText::_('编号')
		);
	}
	  
}