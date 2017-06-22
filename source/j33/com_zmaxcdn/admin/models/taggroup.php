<?php 
/**
 *	description:ZMAX媒体管理组件 标签模型文件
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

class zmaxcdnModelTaggroup extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_zmaxcdn.taggroup', 'taggroup', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		return $form;
	}
	
	public function loadFormData()
	{
		//Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_zmaxcdn.edit.taggroup.data',array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
	
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{			
			$item->tags = json_decode($item->tags);
		}
		return $item;
	}
	
	
}


?>