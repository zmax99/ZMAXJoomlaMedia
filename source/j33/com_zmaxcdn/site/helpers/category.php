<?php
/**
 *	description:ZMAX媒体管理组件 分类实现
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-08-24
 *  check date:2016-07-11
 *  checker :min.zhang
 *  @license GNU General Public License version 3, or later
 */
defined('_JEXEC') or die('you can not access this file!');


class zmaxcdnCategories extends JCategories
{
	public function __construct($options)
	{
		$options['table'] = '#__zmaxcdn_item';
		$options['extension'] = 'com_zmaxcdn';
		parent::__construct($options);
	}
}
 
class zmaxtreeCategory 
{
	//装载分类的树形结构 
	public function loadCategory($extension ,$userId=null,$level=1 ,$parentId=1,$curId)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id,level,title AS name");
		$query->from("#__categories");
		$query->where("extension='".$extension."'");
		$query->where("level=".$level);
		$query->where("parent_id=".$parentId);
		if($userId)
		{
			$query->where("created_user_id=".$userId);	
		}
		$db->setQuery($query);
		$items = $db->loadObjectList();
		if($items)
		{
			foreach($items as $item)
			{
				$item->href="index.php?option=com_zmaxcdn&view=items&layout=field&tmpl=component&catid=".$item->id;
				$item->class="testclass";
				if($this->needOpen($item->id,$curId))
				{
					$item->spread=true;
				}
				if($this->hasChild($item))
				{
					$item->children = $this->loadCategory($extension,$userId,$item->level+1,$item->id ,$curId); 
				}
			}			
		}
		else
		{
			$this->createUserHome();
			return $this->loadCategory($extension ,$userId,$level ,$parentId,$curId);
		}
		
		return $items;
	}	
	
	//判断一个分类对象是否存在子分类
	public function hasChild($item)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id");
		$query->from("#__categories");
		$query->where("parent_id=".$item->id);
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	//判断树形菜单是否需要展开
	public function needOpen($id,$curId)
	{
		$ids = zmaxcdnCommonHelper::getChildCateIds($id ,true);
		if(in_array($curId,$ids))
		{
			return true;
		}
		return false;
	}
	
	public function createUserHome()
	{
		$user = JFactory::getUser();
		
		$parentId = "";
		$title = "我的媒体库";
		$level = 1;
		$path=$user->id."-".JDate::getInstance()->toUnix();
		$extension="com_zmaxcdn";
		$description="用户媒体库";
		$published = 1;
		$language="*";
		$alias = $path;
		
		$basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
		require_once $basePath . '/models/category.php';
		$config = array( 'table_path' => $basePath . '/tables');
		$catmodel = new CategoriesModelCategory( $config);
		
		//创建第0个分类
		$catData = array( 'id' => 0, 
						  'parent_id' => $parentId, 
						  'created_user_id' => $user->id, 
						  'level' => $level, 
						  'path' => $path,
						  'extension' => $extension,
						  'title' => $title, 
						  'alias' => $alias,
						  'description' => $description, 
						  'published' => $published,
						  'language' => $language);
		$status = $catmodel->save( $catData);
		if(!$status) 
		{
			JError::raiseWarning(500, JText::_('ZMAX媒体管理组件创建'.$title.'分类失败!'));
			return false;
		}
		$item = $catmodel->getItem();
		return $item;
	}
	static public function getUserCategory()
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id")->from("#__categories");
		$query->where($db->quoteName('created_user_id') . '=' . (int) $user->id);
		$db->setQuery($query);
		$items = $db->loadColumn();
		$options = JHtml::_('category.options','com_zmaxcdn',$config = array('filter.published' => array(1),'filter.access' =>array(1)));
		
		
		foreach($options as $i=>$option)
		{
			if( !in_array($option->value,$items))
			{
				unset($options[$i]);
			}
		}
		return $options;
	}
 }
