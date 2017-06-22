<?php
/**
 *	description:ZMAX媒体管理组件 分类控制器
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
jimport('joomla.application.component.controllerform');

class zmaxcdnControllerCategory extends  JControllerForm
{
	public function ajaxSave()
	{
		JSession::checkToken('get') or die( 'zmax Invalid Token' );
		$app = JFactory::getApplication();
		$postData = $app->input->get("data",'jform','ARRAY');
	
		$fields = array();
		foreach($postData as $data)
		{
			$name = trim($data["name"]);
			$name=trim($name,' jform[');
			$name=trim($name,']');
			$fields[$name]=$data["value"];
		}

		$model = $this->getModel("category");
		if($model->save($fields))
		{
			$message=JText::_("COM_ZMAXCDN_ADDRESS_SAVE_OK");
		}	
		else
		{
			$message=JText::_("COM_ZMAXCDN_ADDRESS_SAVE_FAILE");
		}
		
		echo $message;
		$app->close();
	}
}
