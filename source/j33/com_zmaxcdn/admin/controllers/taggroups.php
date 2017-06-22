<?php
/**
 *	description:ZMAX媒体管理组件 标签组列表控制器
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


class zmaxcdnControllerTaggroups extends JControllerAdmin
{
	public function getModel($name = 'taggroup' ,$prefix = 'zmaxcdnModel' ,$config = array())
	{
		return parent::getModel($name , $prefix ,$config);
	}
}	
	

?>