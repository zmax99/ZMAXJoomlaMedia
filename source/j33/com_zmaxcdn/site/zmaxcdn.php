<?php 
/**
 *	description:ZMAX媒体管理组件  入口文件
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


//兼容j25 j3x
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

//导入Joomla控制器库
jimport('joomla.application.component.controller');
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/common.php");
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/media.php");
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/item.php");
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/config.php");
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/helpers/category.php");

//加载帮助文件
JLoader::register('zmaxcdnHelper',dirname(__FILE__).DS.'helpers'.DS.'zmaxcdn.php');

//得到控制器实例
$controller = JControllerLegacy::getInstance('zmaxcdn');


//获得输入
$jinput = JFactory::getApplication()->input;
$task = $jinput->get('task',"",'STR');


//执行任务
$controller->execute($task);

//进行重定向
$controller->redirect();
