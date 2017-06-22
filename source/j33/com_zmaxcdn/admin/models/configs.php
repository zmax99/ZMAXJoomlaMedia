<?php 
/**
 *	description:ZMAX媒体管理组件 配置模型文件
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
class zmaxcdnModelConfigs extends JModelList
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
		$query->select('*');
		$query->from('#__zmaxcdn_upload_config');
		
		//搜索
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(title LIKE ' . $search . ' OR description LIKE ' . $search . ')');
	
		}
	
		//排序
		$orderCol = $this->state->get("list.ordering" ,'id');
		$orderDirn = $this->state->get("list.direction",'desc');
		$query->order($db->escape($orderCol).' '.$db->escape($orderDirn));
		
		return $query;
	}
}