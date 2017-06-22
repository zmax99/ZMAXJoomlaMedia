<?php 
/**
 *	description:ZMAX媒体管理组件 控制面板模型文件
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

class zmaxcdnModelMain extends JModelAdmin
{
	public function getForm( $data = array() , $loadData = true)
	{
		//Get the form
		$form = $this->loadForm('com_zmaxcdn.main' ,'main' ,array('control' =>' jform' ,'load_data' => $loadData ));
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
	
	
	function getComponentInfo()
	 {
		$zmaxlogin = JComponentHelper::getComponent('com_zmaxcdn');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("manifest_cache");
		$query->from("#__extensions");
		$query->where("extension_id =".$zmaxlogin->id);
		$db->setQuery($query);
		$item = $db->loadObject();
		$item = json_decode($item->manifest_cache);
		return $item;
	 }
	 
	function getSystemInfo()
	{		
		jimport( 'joomla.plugin.helper' );
		$insertbtnPlg = JPluginHelper::isEnabled("editors-xtd","zmaxcdn_insertbtn");
		$fieldPlg = JPluginHelper::isEnabled("content","zmaxcdn_field");
		$downloadPlg = JPluginHelper::isEnabled("system","zmaxcdn_download");
		
		
		
		$system = new stdclass();
		$system->insertbtnPlg = $insertbtnPlg;
		$system->fieldPlg = $fieldPlg;
		$system->downloadPlg = $downloadPlg;
		
		return $system;
	}
}


?>