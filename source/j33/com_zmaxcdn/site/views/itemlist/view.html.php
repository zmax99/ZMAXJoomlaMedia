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

class zmaxcdnViewItemlist extends JViewLegacy
{
    public function display($tpl = null)	 
	{
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
		
		parent::display($tpl);
	}
	 

	  
}