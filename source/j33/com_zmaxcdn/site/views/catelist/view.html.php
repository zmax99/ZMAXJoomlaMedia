<?php 
/**
 *	description:ZMAX CDN 多分类资源列表视图
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2016-03-31
 *  @license GNU General Public License version 3, or later
 */
 
defined('_JEXEC') or die('Restricted access');

class zmaxcdnViewCatelist extends JViewLegacy
{
     
     function display($tpl = null)	 
	 {		
		$this->items = $this->get('Items');
		$this->form = $this->get('Form');
		$this->pagination =$this->get('Pagination');
		$this->state =$this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		$this->listOrder = $this->state->get('list.ordering'); //需要排序的
		$this->listDir = $this->state->get('list.direction');//需要排序的方向
		
		
		if(count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />',$errors));
			return false;
		}
				
		parent::display($tpl);
		
	 }
	 
	  
}