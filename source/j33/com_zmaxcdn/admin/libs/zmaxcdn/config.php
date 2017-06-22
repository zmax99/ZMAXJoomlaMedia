<?php 
/**
 *	description:ZMAX媒体管理组件 配置库文件
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

class zmaxcdnConfigHelper{
	
	//获得zmaxcdn的配置文件，
	static public function loadConfig($configId="")
	{
		if($configId) //如果设置了ID，那么将加载这个ID指定的配置文件，系统并不负责判断当前的config_id是否和当前的客户端相匹配
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select("config")->from("#__zmaxcdn_upload_config");
			$query->where("id=".$configId);
			$db->setQuery($query);
			$config = $db->loadObject();
			if($config)
			{
				return json_decode($config->config);
			}
		}
		return null;
	}
	
	static public function loadDefaultConfig()
	{
		$app = JFactory::getApplication();
		$client = $app->isAdmin()?"admin":"site";
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("config")->from("#__zmaxcdn_upload_config");
		$query->where('client="'.$client.'"');
		$query->where('`default`=1');
		$db->setQuery($query);
		$config = $db->loadObject();
		return json_decode($config->config);
	}

}
 
 ?>

