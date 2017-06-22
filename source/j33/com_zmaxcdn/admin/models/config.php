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

jimport('joomla.application.component.modeladmin');
class zmaxcdnModelConfig extends JModelAdmin
{

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_zmaxcdn.config', 'config', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		return $form;
	}
	
	public function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_zmaxcdn.edit.config.data',array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
	
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{		
			
			$registry = new JRegistry;
			$registry->loadString($item->config);
			$item->config = $registry->toArray();
			
		}
		return $item;
	}
	
	public function setDefault()
	{
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid','','ARRAY');
		if(!$ids)
		{
			$this->setError("设置默认错误，无效的ID！");
			return false;
		}
		$id = $ids[0];
		
		$table = $this->getTable("config");
		$table->load($id);
		$client=$table->get("client","site");
		

		$db = JFactory::getDBO();		
		$query = 'UPDATE `#__zmaxcdn_upload_config` SET `default` =0 WHERE `client`="'.$client.'" ';
		$db->setQuery($query);
		$db->query();
		
		$query = "UPDATE `#__zmaxcdn_upload_config` SET `default`=1 WHERE `id` =$id";
		$db->setQuery($query);
		$db->query();	
		return true;
	}
}
?>