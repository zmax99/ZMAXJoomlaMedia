<?php
/**
 *	description:ZMAX媒体管理组件 资料上传控制器
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

class zmaxcdnControllerUpload extends JControllerForm
{
	//在文章界面的插入
	public function insert()
	{
		$app = JFactory::getApplication();
		$id = $app->input->get("id",'','INT');
		$this->_insertEditor($id);
		$app->close();
	}
	
	public function uploadAndInsert()
	{
		$app = JFactory::getApplication();
		
		$uploader = $app->input->get("uploader","","STRING");//获得uploader 是七牛 还是本地
		$caller = $app->input->get("caller","plugin","STRING");//获得当前调用插入程序的是插件 还是字段类型
		$function = $app->input->get("function","","STRING");//获得当前调用插入程序的是插件 还是字段类型
		
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
		
		//在这里应该将这个错误抛出去
		if(!$id) //如果存储失败，就直接关闭
		{
			$errorMsg = $this->getError();
			echo "alert('".$errorMsg."');";
			$app->close();
			return false;
		}
		
		switch($caller)
		{
			case 'plugin':
					$this->_insertEditor($id);	
				break;
			case 'field':
					$this->_insertField($id,$function);
				break;
			default:
					$errorMsg="caller is invalid!";
					echo "alert('".$errorMsg."');";
				break;
		}
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
	protected function _insertEditor($id)
	{
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('zmaxcdn');
		
		$tag = zmaxcdnItemHelper::getItemTagHtmlById($id);	
		
		//得到组件的设置参数
		$params = JComponentHelper::getParams("com_zmaxcdn");
		$warpTag = $params->get("warp-tag" ,"span");
		
		$warpTagClassname=$params->get("warp-tag-classname","zmax-item-warp");
		$tag='<'.$warpTag.' class="'.$warpTagClassname.'"'.' >'.$tag.'</'.$warpTag.'>';
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