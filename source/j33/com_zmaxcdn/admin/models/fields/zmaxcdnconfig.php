<?php
/**
 *	description:ZMAX媒体管理 zmaxcdnconfig字段
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2016-05-20
 *  @license GNU General Public License version 3, or later
 *  check date:2016-05-20
 *  checker :min.zhang
 */
 
 
defined('_JEXEC') or die('you can not access this file!');
JFormHelper::loadFieldClass('list');

class JFormFieldZmaxcdnconfig extends JFormFieldList
{
	protected $type="zmaxcdnconfig";
	protected function getOptions()
	{		
		$newOptions  = $this->getItems();
		$options = array_merge(parent::getOptions(), $newOptions );
		return $options;
	}
	
	protected function getItems()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('title  AS text,id AS value');
		$query->from('#__zmaxcdn_upload_config');
		$db->setQuery($query,0 ,500);		
		$items = $db->loadObjectList();
		return $items;
	}
}