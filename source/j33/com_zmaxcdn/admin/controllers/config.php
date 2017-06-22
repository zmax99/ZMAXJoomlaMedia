<?php
/**
 *	description:ZMAX媒体管理 设置文件控制器
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-08-07
 * @license GNU General Public License version 3, or later
 *  check date:2016-05-19
 *  checker:min.zhang
 */
defined('_JEXEC') or die('You Can Not Access This File!');

jimport('joomla.application.component.controllerform');
class zmaxcdnControllerConfig extends JControllerForm
 {	

	public function setDefault()
	{
		$model = $this->getModel("config");
		
		if($model->setDefault())
		{
			$message =JText::_("设置默认成功!");
			$type ="Message";	
		}
		else
		{
			$message = $model->getError();
			$type="Warning";
		}
		$this->setRedirect("index.php?option=com_zmaxcdn&view=configs" ,$message ,$type);

	  }
	
 }	
	

?>