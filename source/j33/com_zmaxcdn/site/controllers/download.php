<?php 
/**
 *	description:ZMAX媒体管理组件  资料下载控制器
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
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/item.php");
	
class zmaxcdnControllerDownload extends JControllerAdmin
{

	public function download()
	{
		$app =  JFactory::getApplication();
		$id = $app->input->get("id","","INT");
		$model = $this->getModel("item");		
		$result =  $model->checkAuthority($id);
		echo json_encode($result);
		$app->close();
	}

	public function getFile()
	{
		$app =  JFactory::getApplication();
		$id = $app->input->get("id","","INT");
		$item = zmaxcdnitemHelper::getItemById($id);
		$file = zmaxcdnitemhelper::getItemUrl($item);
		ob_clean();
		header('Content-Description: File Transfer');
		header("Content-type:".$item->type);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		//设置文件的下载信息
		header("Content-Disposition:attachment;filename=".$item->name);
		header('Pragma: public');
        header('Content-Length: ' .$item->size);
		readfile($file);
		exit();
		$app->close();		
	}
 
 }	
	

?>