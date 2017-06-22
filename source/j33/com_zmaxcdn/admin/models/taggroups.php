<?php 
/**
 *	description:ZMAX媒体管理组件 标签组模型文件
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

jimport('joomla.application.component.modellist');
class zmaxcdnModelTaggroups extends JModelList
{	
	public function __construct($config = array())
	{
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
			'id', 
			'title',
			'description',
			'tags'
			);
			if (JLanguageAssociations::isEnabled())
			{
				$config['filter_fields'][] = 'association';
			}
		}
		return parent::__construct($config);
	}
	
	 protected function getListQuery()
	 {
		$db =  JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('tg.id as id, tg.title ,tg.description ,tg.tags AS tags');
		$query->from('#__zmaxcdn_taggroup AS tg');
		
		//搜索
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(tg.title LIKE ' . $search . ' OR tg.description LIKE ' . $search . ')');
	
		}
	
		//排序
		$orderCol = $this->state->get("list.ordering" ,'id');
		$orderDirn = $this->state->get("list.direction",'desc');
		$query->order($db->escape($orderCol).' '.$db->escape($orderDirn));
		
		return $query;
	 }
	 
	 public function getItems()
	 {
		 $items = parent::getItems();
		 foreach($items as $item)
		 {
			 $item->tags = $this->getTagTilte(json_decode($item->tags));
		 }
		 
		 return $items;
	 }
	 
	 public function getTagTilte($tagIds)
	 {
		 if($tagIds && is_array($tagIds))
		 {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select("title")->from("#__tags")->where("id IN (".implode(",",$tagIds).")");
			$db->setQuery($query);
			$titles = $db->loadColumn();	
			
			return implode(",",$titles);
		 }
		 return "";
	 }
	 
	 
	 
	
	 
	 
}