<?php 
/**
 *	description:ZMAX媒体管理组件  分类列表文件
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

class zmaxcdnModelCatelist extends JModelList
{
	public $params;
	protected $limit;
	protected $orderBy;
	public function __construct($config = array())
	{
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
			'id',
			'title',
			'name',
			'category',
			'size',
			'type',
			'doc_type',
			'date',
			'description',
			'category_id'
			);
			if (JLanguageAssociations::isEnabled())
			{
				$config['filter_fields'][] = 'association';
			}
		}
		$app = JFactory::getApplication();
		$this->params = $app->getParams();	
		$this->limit = $this->params->get('max_number' ,'10');
		$this->orderby = $this->params->get('orderby' ,'date desc');
		parent::__construct($config);
	}
	
	public function getForm( $data = array() , $loadData = true)
	{
		//Get the form
		$form = $this->loadForm('com_zmaxcdn.item' ,'item' ,array('control' =>' jform' ,'load_data' => $loadData ));
		if(!$form)
		{
			return false;
		}
		
		return $form;
	}
	
	public function getItems()
	{		
		$app = JFactory::getApplication();		
		$ids = $app->input->get("ids",'','ARRAY');
		$items = array();
		foreach($ids as $id)
		{
			$items[]=$this->getItemById($id);
		}
		return $items;
	}
	
	
	public function getItemById($id)
	{
		$db =  JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("i.id ,i.catid ,i.uid ,i.name AS title ,i.filename ,i.description ,i.type,i.size ,i.length ,i.cdn_path ,i.local_path, i.create_date AS date");
		$query->from( '#__zmaxcdn_item AS i' );
		$query->where("i.catid=".$id);
		$query->select("c.title AS category,c.params as params");
		$query->leftJoin('#__categories AS c on c.id = i.catid');			
		$query->order($this->orderby);
		$query->setlimit($this->limit);		
		$db->setQuery($query);
		$items = $db->loadObjectList();
		return $items;
	}
	
	protected function getListQuery()
	{
		$db =  JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("i.id ,i.catid ,i.uid ,i.name AS title ,i.filename ,i.description ,i.type,i.doc_type,i.size ,i.length ,i.cdn_path ,i.local_path, i.create_date AS date");
		$query->from( '#__zmaxcdn_item AS i' );
		$query->select("c.title AS category");
		$query->leftJoin('#__categories AS c on c.id = i.catid');
		

		//处理文件的分类		
		if ($catid = $this->getState('filter.category_id'))
		{
			$ids = zmaxcdnHelper::getChildCateIds($catid);
			$ids = implode(',',$ids);
			$query->where('catid IN ('.$ids .")" );				
		}
		
		//处理文件的文档类型
		if ($docType = $this->getState('filter.doc_type'))
		{
			$docType = $db->quote($db->escape($docType, true) . '%');
			$query->where('i.doc_type LIKE '. $docType );					
		}
		
		
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = strtolower($search);
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(i.name LIKE ' . $search . ' OR i.description LIKE ' . $search . ' OR i.type LIKE ' . $search . ' OR i.size LIKE ' . $search .')');				
		}
		
		
		
		
		//排序
		$orderCol = $this->state->get("list.ordering" ,'id');
		$orderDirn = $this->state->get("list.direction",'desc');
		$query->order($db->escape($orderCol).' '.$db->escape($orderDirn));
		
		//echo $query;
		
		return $query;
		
	 }
}
