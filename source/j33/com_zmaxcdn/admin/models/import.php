<?php 
/**
 *	description:ZMAX媒体管理组件 导入模型文件
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
class zmaxcdnModelImport extends JModelAdmin
{
	public function getForm( $data = array() , $loadData = true)
	{
		$form = $this->loadForm('com_zmaxcdn.import' ,'import' ,array('control' =>' jform' ,'load_data' => $loadData ));
		if(!$form)
		{
			return false;
		}
		return $form;
	}
	
	public function loadFormData()
	{
		$data = $this->getItem();
		$this->preprocessData('com_zmaxcdn.import', $data);
		return $data;
	}
	
	public function import()
	{
		$app = JFactory::getApplication();
		$data = $app->input->get('jform','','ARRAY');
		
		//准备插件
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('zmaxcdn');
		
		//检查PATH是否为空 
		if(!isset($data["import_file"]) || $data["import_file"]=="" )
		{
			$this->setError("你没有选择任何的文件!");
			return false;
		}
		$file = $data["import_file"];
		$file = JPath::clean(JPATH_ROOT.DS.$file);
		
		//检查文件是否存在在本地环境中
		if(!JFile::exists($file))
		{
			$this->setError("需要导入的源文件不存在，请选择本地文件，不要选择CDN文件!");
			return false;
		}
		
		//开始解析需要导入的XML文件
		$xml = simplexml_load_file($file);	//确保XML文件成功解析
		if(!$xml)
		{
			$this->setError("XML文件解析失败!");
			return false;
		}
		
		
		$db = JFactory::getDBO();
		//开始执行查询		
		$items = $xml->items->children();
		foreach($xml->children() as $item)
		{
			$resourceId = (string)$item->resourceid;
			$title = (string)$item->title;
			$description = (string)$item->description;
			$url = (string)$item->url;
			$catid = (string)$item->catid;
			$size =  (string)$item->size;
			$type =  (string)$item->type;
			$length =  (string)$item->length;
			$image = (string)$item->image;
			$hash = (string)$item->hash;
			
			//检查是否已经存在了 $resourceId 必须唯一
			$query = $db->getQuery(true);
			$query->select("*")->from("#__zmaxcdn_item")->where("other_id=".$db->quote($resourceId));
			$db->setQuery($query);
			$cdnItem = $db->loadObject();
			if($cdnItem) //如果存在就执行更新
			{
				$cdnItem->title = $title;
				$cdnItem->description = $description;
				$cdnItem->catid = $catid;
				$cdnItem->size = $size;
				$cdnItem->type = $type;
				$cdnItem->length = $length;
				if($image)
				{
					$registry = new JRegistry;
					$data= array();
					$data["alt"]=$title;
					$data["image_intro"]=$image;
					$data["image_intro_alt"]=$description;
					$data["image_intro_caption"]=$title;
					$registry->loadArray($data);
					$cdnItem->image= (string) $registry;
				}
				$db->updateObject("#__zmaxcdn_item" ,$cdnItem);
				continue ;
			}
			else //就执行插入
			{
				//初始化新的对象
				$newCdnItem = new Stdclass();
				$newCdnItem->other_id = $resourceId;
				$newCdnItem->catid = 0;
				$newCdnItem->uid = JFactory::getUser()->id;
				$newCdnItem->name = $title;
				$newCdnItem->alias="";
				$newCdnItem->filename = $title;
				$newCdnItem->description = $description;
				$newCdnItem->type = $type;
				$newCdnItem->extension="com_zmaxcdn";
				$newCdnItem->size=$size;
				$cdnItem->length = $length;
				$newCdnItem->cdn_path=$url;
				$newCdnItem->local_path = "";
				$newCdnItem->create_date = JDate::getInstance()->toSql();
				$newCdnItem->modify_date = JDate::getInstance()->toSql();
				$newCdnItem->attr="";
				if($image)
				{
					$registry = new JRegistry;
					$data= array();
					$data["alt"]=$title;
					$data["image_intro"]=$image;
					$data["image_intro_alt"]=$description;
					$data["image_intro_caption"]=$title;
					$registry->loadArray($data);
					$newCdnItem->image= (string) $registry;
				}
				$newCdnItem->published ="1";
				$newCdnItem->hash = $hash;
				$db->insertObject("#__zmaxcdn_item" ,$newCdnItem);
				$id = $db->insertId();	
				//这里触发一个事件，用户可以相应这是事件来定制各种输出
				$dispatcher->trigger('onAfterInsertItem', array ($id ,$item ));
			}			
		}
		return true;
	}
}

?>