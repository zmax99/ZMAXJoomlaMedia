<?php 
/**
 *	description:ZMAX媒体管理组件  分类模型文件
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

jimport('joomla.application.component.modeladmin');

class zmaxcdnModelCategory extends JModelAdmin
{
	public function getForm( $data = array() , $loadData = true)
	{		
		$form = $this->loadForm('com_zmaxcdn.category' ,'category' ,array('control' =>' jform' ,'load_data' => $loadData ));
		if(!$form)
		{
			return false;
		}
		return $form;
	}
	
	public function loadFormData()
	{
		//Load form data
		$data = $this->getItem();
		return $data;
	}
	
	public function save($data)
	{
		$parentId = $data["parent"];
		$title = $data["name"];
		$level = 1;
		$path=JDate::getInstance()->toUnix();
		$extension="com_zmaxcdn";
		$description="";
		$published = 1;
		$language="*";
		if($parentId)
		{
			$categories = JCategories::getInstance('zmaxcdn');
			$pc = $categories->get($parentId);
			$level = $pc->level+1;
			$path=$level->path."/".$path;
		}
		$alias = $path;
		
		$basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
		require_once $basePath . '/models/category.php';
		$config = array( 'table_path' => $basePath . '/tables');
		$catmodel = new CategoriesModelCategory( $config);
		
		//创建第0个分类
		$catData = array( 'id' => 0, 
						  'parent_id' => $parentId, 
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
		
		//这里得到刚刚创建的分类。可以进行后续的更多操作
		$item = $catmodel->getItem();
		return true;
	}
	
	

}


?>