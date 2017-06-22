<?php 
/**
 *	description:ZMAX媒体管理组件  资料列表控制器文件
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

jimport('joomla.application.component.controlleradmin');
class zmaxcdnControllerItems extends JControllerAdmin
{
	 public function getModel($name = 'item' ,$prefix = 'zmaxcdnModel' ,$config = array())
	 {
		return parent::getModel($name , $prefix ,$config);
	 }
 }	
	

?>