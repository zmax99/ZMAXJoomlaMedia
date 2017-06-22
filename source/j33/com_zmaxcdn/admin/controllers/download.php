<?php
/**
 *	description:ZMAX媒体管理组件 资料下载控制器
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

	public  function getFile()
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