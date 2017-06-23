<?php
/**
 *	description:ZMAX媒体管理组件 资料列表控制器
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

jimport('joomla.application.component.controlleradmin');
jimport("joomla.filesystem.file");

class zmaxcdnControllerItems extends JControllerAdmin
 {
	 public function getModel($name = 'item' ,$prefix = 'zmaxcdnModel' ,$config = array())
	 {
		return parent::getModel($name , $prefix ,$config);
	 }
	 
	 public function ajaxDelete()
	 {
		$app = JFactory::getApplication();
		$ids = $app->input->get("cid","","ARRAY");
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_zmaxcdn/tables');
		$table = JTable::getInstance('item', 'Table', array());
		foreach($ids as $id)
		{
			$item = zmaxcdnItemHelper::getItemById($id);
			
			//测试是否能够删除文件 
			if($this->canDeleteFile($item))
			{
				//删除文件
				if($item->local_path)
				{
					$file = JPath::clean(JPATH_SITE.DS.$item->local_path);
					if(JFile::exists($file))
					{
						JFile::delete($file);
					}
				}
			}
			
			//在这里调用删除吧
			$table->delete($id);
		}
		echo "删除成功!";
		
		$app->close();
	 }
	 public function delete()
	 {
		 //return parent::delete();
		 //待实现
		$ids = JFactory::getApplication()->input->get("cid","","ARRAY");
		foreach($ids as $id)
		{
			$item = zmaxcdnItemHelper::getItemById($id);
			//测试是否能够删除文件
			if($this->canDeleteFile($item))
			{
				//删除文件
				if($item->local_path)
				{
					$file = JPath::clean(JPATH_SITE.DS.$item->local_path);
					if(JFile::exists($file))
					{
						JFile::delete($file);
					}
				}
			}
		}
		return parent::delete();
	 }
	 
	 //是否能够删除文件
	 protected function canDeleteFile($item)
	 {
		 if(!$item->hash)
		 {
			 return false;
		 }
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id")->from("#__zmaxcdn_item")->where("hash='".$item->hash."'");
		$db->setQuery($query);
		$items = $db->loadObjectList();
		if($items && count($items)>1 )
		{
			return false;
		}
		return true;
	 }
 }	
	

?>