<?php 
/**
 *	description:ZMAX媒体管理组件  资料控制器
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

jimport('joomla.application.component.controllerform');	
class zmaxcdnControllerItem extends JControllerForm
 { 
	
	public function uploadInsert()
	{
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->get('jform','','array');
		
		$file = $jinput->files->get('jform');
		$data = array_merge($data, $file);		
		
		
		$model = $this->getModel();
		if(!$model->save($data))
		{
			return false;
		}
		
		
		$session = JFactory::getSession();
		$data = $session->get("com_zmaxcdn.item.data");
		$data = json_decode($data);
		
		
		$params = JComponentHelper::getParams("com_zmaxcdn");
		$domain = $params->get("domain");
		
		$path = "components/com_zmaxcdn/".$data->local_path;
		$url = "administrator/".$path;
		if($data->cdn_path)
		{
			$url = "http://".$domain."/".$data->cdn_path;
		}
		$srcName=$data->name;
		$isImage = true;
		
		
		$js='
			<script>
				var tag;
				tag=\'<img class="zmax_upload_image" src="'.$url.'" alt="'.$srcName.'" />\';
				window.parent.jInsertEditorText(tag, "jform_articletext");
				window.parent.SqueezeBox.close();				
			</script>
		';
		
		$message= $js;
		//JLog::add($message,JLOG::DEBUG ,'zmax');
		echo $message;
		//$this->setRedirect($link,$message);
		
	
	}
	
	public function loadItem()
	{
		$app = JFactory::getApplication();
		$id = $app->input->get('id',"",'INT');
		if($id=="")
		{
			return null;
		}
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("*")->from("#__zmaxcdn_item")->where("id=".$id);
		$db->setQuery($query);
		$item = $db->loadObject();
		if($item)
		{
			$item->url  = zmaxcdnHelper::getItemPath($item);
		}
		$item = $this->formatItemForAjax($item);
		echo json_encode($item);
		$app->close();
	}
	
	public function formatItemForAjax($item)
	{
		$item->thumb=zmaxcdnItemHelper::getItemPreview($item);
		$item->date = JHtml::_("date",$item->create_date);
		$item->size = zmaxcdnCommonHelper::formatFileSize($item->size);
		$item->dim = "99*109";
		$item->pubUrl = JUri::root().$item->url;
		$item->caption=$item->name;
		$item->alt="";
		return $item;
	}
 }	
	

?>