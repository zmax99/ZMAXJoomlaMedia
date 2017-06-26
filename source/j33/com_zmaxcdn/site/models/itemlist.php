<?php
/**
 *	description:ZMAX媒体管理  资源列表模型
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2016-04-18
 *  @license GNU General Public License version 3, or later
 *  check date: 2016-05-19
 *  checker :min.zhang
 *  modified :2017-06-20
 */

defined('_JEXEC') or die('you can not access this file!');
class zmaxcdnModelItemlist extends JModelList
{
	protected $_id;//当前分类的ID
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
			'date',
			'path',
			'published',
			'hash',
			'hits',
			'description',
			'other_id',
			'category_id'
			);
			if (JLanguageAssociations::isEnabled())
			{
				$config['filter_fields'][] = 'association';
			}
		}
		parent::__construct($config);
	}
	
	protected function getListQuery()
	{
		$db =  JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("i.id ,i.hits, i.published,
						i.catid ,i.uid ,i.name AS title ,i.filename,
						i.description ,i.type,i.size ,i.length ,i.cdn_path ,
						i.local_path, i.create_date AS date 
						,i.image,i.other_id");
		$query->from( '#__zmaxcdn_item AS i' );
		$query->select("c.title AS category");
		$query->leftJoin('#__categories AS c on c.id = i.catid');
		

		//处理文件的分类		
		//系统会自动列出所有的子分类的内容，这里可能会涉及到分页
		

		$catid = $this->_id;//当前的ID
		if ($catid)
		{
			$ids = zmaxcdnCommonHelper::getChildCateIds($catid);
			$ids = implode(',',$ids);
			if($ids)
			{
				$query->where('catid IN ('.$ids .")" );		
			}
		}
		
		//处理文件的文档类型
		if ($docType = $this->getState('filter.type'))
		{
			$docType = $db->quote($db->escape($docType, true) . '%');
			$query->where('i.type LIKE '. $docType );					
		}
		
		//处理搜索
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = strtolower($search);
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(i.name LIKE ' . $search . ' OR i.description LIKE ' . $search . ' OR i.other_id LIKE ' . $search . ' OR i.type LIKE ' . $search . ' OR i.size LIKE ' . $search .')');				
		}
		
		//当外部调用的时候需要额外的过滤
		$input = JFactory::getApplication()->input;
		$tmpl = $input->get('tmpl','','STRING');
		$caller = $input->get('caller','','STRING');
		
		if($tmpl =="component")
		{
			$cfgName="zmaxcdn.caller.config";
			if($caller=="field")
			{
				$id = $input->get("id",'','STRING');
				$cfgName="zmaxcdn.caller.config_".$id;
			}

			//STEP 1 ：从session获得设置参数
			$session = JFactory::getSession();
			$config = $session->get($cfgName);
			
		
			
			//STEP 2 : 从cookie中获得设置参数
			if(!$config)  //目前cookie部分不能够正常获取
			{
				$inputCookie = JFactory::getApplication()->input->cookie;
				$config = $inputCookie->get($cfgName);
			}
		
			//STEP 3 :加载默认的设置参数
			if(!$config)
			{
				$config = zmaxcdnConfigHelper::loadDefaultConfig(); //加载默认的配置
			}
			else
			{
				$config = json_decode($config);
			}
			
			$session->set("zmaxcdn.field.config",json_encode($config));
			/*
			/***************************************************************************
			 * 该部分的设计可能存在问题，需要重新设计 目前暂时注释掉                   *
			 *  2017-06-20           min.zhang                                         * 
			 ***************************************************************************/
			 
			 
			 
			
			/***************************************************************************
			 *  增加过滤条件 extension_filter 。按照资源所属的extension来进行过滤      *
			 *  2016-07-21                                                             * 
			 ***************************************************************************/
			 /*
			 if(!isset($config->extension_filter))
			 {
				 $config->extension_filter = "";
			 }
			$extensionFilterConfig = trim($config->extension_filter);
			if($extensionFilterConfig)
			{
				$extensionFilterConfig = explode(";",$extensionFilterConfig);	
				if(is_array($extensionFilterConfig) && count($extensionFilterConfig))
				{					
					for($i= 0; $i< count($extensionFilterConfig) ;$i++)
					{
						$extensionFilterConfig[$i]='"'.$extensionFilterConfig[$i].'"';
					}
					//只显示当前的扩展			
					$query->where('i.extension IN('.implode(',',$extensionFilterConfig).')' );		
				}
			}
			*/
			
			//只显示当前用户的扩展
			if(!$config->allow_see_other)
			{
				$user = JFactory::getUser();
				$query->where('i.uid='.$user->id);	
			}
		}
		
		
		//排序
		$orderCol = $this->state->get("list.ordering" ,'id');
		$orderDirn = $this->state->get("list.direction",'desc');
		$query->order($db->escape($orderCol).' '.$db->escape($orderDirn));
		
		return $query;
	 }
	 
	 public function getCurCategoryId()
	 {
		 return $this->_id;
	 }

	protected function populateState($ordering=null , $direction=null )
	{
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$cookie = JFactory::getApplication()->input->cookie;
		$catid = $app->input->get('catid', '', 'INT');
		
		if(!$catid)
		{
			$catid = $session->get("com_zmaxcdn.last_catid_itemlist");
			if(!$catid)
			{
				$catid=$cookie->get("com_zmaxcdn_last_catid_itemlist");	
			}
		}
		
		//STEP 1 将catid 存储到session中
		$session->set("com_zmaxcdn.last_catid_itemlist",$catid);
		//STEP 2 将catid 存储到cookie中
		$cookie->set("com_zmaxcdn_last_catid_itemlist",$catid);
		
		$this->_id = $catid;	
		
		// List state information.
		parent::populateState($ordering, $direction);
	}

}