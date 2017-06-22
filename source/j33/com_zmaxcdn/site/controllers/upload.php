<?php
/**
 *	description:ZMAX CDN 资源上传控制器
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2016-04-17
 *  @license GNU General Public License version 3, or later
 *  check date:2016-05-20
 *  checker :min.zhang
 */
 
defined('_JEXEC') or die('You can not access this file!');

require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/common.php");
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/item.php");
jimport('joomla.application.component.controllerform');
class zmaxcdnControllerUpload extends JControllerForm
 {
	public function uploadAndInsert()
	{
		$app = JFactory::getApplication();
		$uploader = $app->input->get("uploader","","STRING");//获得uploader 是七牛 还是本地
		
		//获得上传模型 ,执行上传
		$model = $this->getModel("uploader");
		
		$id = false;
		if($uploader=="server")
		{
			$id = $model->saveServer();	
		}
		if($uploader=="cdn")
		{
			$id = $model->saveCdn();
		}
		
		if(!$id) //如果存储失败，就直接关闭
		{
			$app->close();
			return false;
		}
		
		$this->_insert($id);
		$app->close();
	}
	
	
	
	public function insert()
	{
		$app = JFactory::getApplication();
		$id = $app->input->get("id",'','INT');
		$this->_insert($id);
		$app->close();
	}
	
	/**
	 * 这里的动作是一样的，必须要获得function的名称
	 */
	//Field视图执行的任务
	//他的功能就是能够将用户选择的本地资源能够自动上传到服务器，并且被选中
	public function fieldUploadAndInsert()
	{
		$app = JFactory::getApplication();
		$uploader = $app->input->get("uploader","","STRING");//获得uploader 是七牛 还是本地
		$function = $app->input->get("function","SelectItem1","STRING");
		//获得上传模型 ,执行上传
		$model = $this->getModel("uploader");
		
		$id = false;
		if($uploader=="server")
		{
			$id = $model->saveServer();	
		}
		if($uploader=="cdn")
		{
			$id = $model->saveCdn();
		}
				
		if(!$id) //如果存储失败，就直接关闭
		{
			$app->close();
			return false;
		}
			
		$this->_insertField($id ,$function);
		$app->close();
	}
	
	
	
	public function uploadServer()
	{
		$model = $this->getModel("uploader");
		$model->saveServer();		
		$app = JFactory::getApplication();
		$app->close();
	}
	
	/*###########  上传到七牛CDN服务器  处理程序的上传逻辑  #################*/
	public function uploadCdn()
	{
		$model = $this->getModel("uploader");
		$model->saveCdn();		
		$app = JFactory::getApplication();
		$app->close();
	}
	

	
	public function getUptoken()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel("uploader");
		$model->getUptoken();		
		$app->close();
	}
	
	/*
	 *  ############### 用于在 编辑器按钮插件 中 ####################
	 * 该函数 可以将一个资源插入到文章中
	 * 
	 * 参数：$id   资源的唯一id
	 *  
     * 返回：false 	 
	 * 
	 */
	protected function _insert($id)
	{
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('zmaxcdn');
		
		$tag = zmaxcdnItemHelper::getItemTagHtmlById($id);		
		//这里触发一个事件，用户可以相应这是事件来定制各种输出
		$dispatcher->trigger('onBeforeShowItemTag', array ($id , &$tag));
		
		$js='
				var tag;
				tag=\''.$tag.'\';
				window.parent.jInsertEditorText(tag, "jform_articletext");
				window.parent.SqueezeBox.close();				
		';
		echo $js;
	}
	
	
	/*
	 *  ############### 用于在 zmaxfile字段 中 ####################
	 * 该函数 可以将一个资源插入到字段值
	 * 
	 * 参数：$id   资源的唯一id
	 *  
     * 返回：false 	 
	 * 
	 */
	protected function _insertField($id ,$function)
	{		
		$js = "window.parent.".$function."('".$id."')";		
		echo $js;
	}
	
}	
	

?>