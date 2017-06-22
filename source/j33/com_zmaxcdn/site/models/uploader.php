<?php 
/**
 *	description:ZMAX媒体管理组件  上传模型文件
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
jimport('joomla.application.component.modeladmin');

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."libs".DS."7niu".DS."vendor".DS."autoload.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."libs".DS."webuploader".DS."webserver.php");

class zmaxcdnModelUploader extends JModelAdmin
{
	public $_data = array();

	public function getForm( $data = array() , $loadData = true)
	{	
		$form = $this->loadForm('com_zmaxcdn.item' ,'item' ,array('control' =>' jform' ,'load_data' => $loadData ));
		if(!$form)
		{
			return false;
		}
		return $form;
	}
	
	public function loadFormData()
	{
		$data = $this->getItem();
		return $data;
	}
	
	
	public function saveCdn()
	{
		$this->data["uid"]=JFactory::getUser()->id;
		$this->data["create_date"] = zmaxcdnCommonHelper::getPostDate();
		
		$this->data["filename"]="";
		$this->data["local_path"]="";
		$this->data["catid"] = JRequest::getVar("catid");
		$this->data["size"] = JRequest::getVar("size");
		$this->data["type"] = JRequest::getVar("type");
		$this->data["name"] = JRequest::getVar("name");
		$extName = zmaxcdnCommonHelper::getExt($this->data["name"]);
		$this->data["type"] = trim($extName,'.');
		$this->data["cdn_path"] = JRequest::getVar("cdnPath");
		return $this->_storeItem($this->data);
	}
	
	
	public function saveServer()
	{
		$webServer = new zmaxcdnWebserver();
		//$webServer->enableLog();
		$webServer->uploadFile();
		$fileInfo = $webServer->getFileInfo();
		if(isset($fileInfo["done"])  && $fileInfo["done"])
		{
			$this->data = $fileInfo;
			
			$this->data["uid"]=JFactory::getUser()->id;
			$this->data["create_date"] = zmaxcdnCommonHelper::getPostDate();
			$this->data["modify_date"] = $this->data["create_date"];
			$this->data["local_path"]="media/zmaxcdn/".$this->data["filename"];
			
			$extName = zmaxcdnCommonHelper::getExt($this->data["name"]);
			
			// v4.1.6版本后 ，将doc_type字段去掉  将type赋值为文档的扩展名
			$this->data["type"] = trim($extName,'.'); //这里的扩展名是带点的，因此需要去掉
			$fileName = JPATH_SITE.DS.$this->data["local_path"];
			$this->data["hash"]=md5_file($fileName);
			
			return $this->_storeItem($this->data);
		}
		//这里执行的逻辑很奇怪，难道这个函数会不断的执行并且返回
		//因为分片传输，但是我不能够返回false
		return false;
		
	}

	
	//七牛 获得文件上传的凭证
	public function getUptoken()
	{
		$params = JComponentHelper::getParams("com_zmaxcdn");
		
		// 设置信息
		$APP_ACCESS_KEY = $params->get("accessKey");
		$APP_SECRET_KEY = $params->get("secretKey");
		$bucket = $params->get("bucket");
		
		$APP_ACCESS_KEY = trim($APP_ACCESS_KEY);
		$APP_SECRET_KEY = trim($APP_SECRET_KEY);
		$bucket = trim($bucket);
		
		//得到一个认证对象
		$auth = new Auth($APP_ACCESS_KEY, $APP_SECRET_KEY);
		$token = $auth->uploadToken($bucket);
		
		$tk = array('uptoken'=>$token);
		$tk = json_encode($tk);
		echo $tk;
		return $token;
	}
	
	
	//七牛 上传一个文件到七牛服务器
	public function uploadQiniu($file)
	{
		$cdn_path ="";
		
		$token = $this->getUptoken();
		$uploadManager = new UploadManager();
			
		//执行上传 (多次上传同一个文件不会有任何的作用)
		list($ret, $err) = $uploadManager->putFile($token, null, $file);
		if ($err != null) 
		{
				//$this->_message = "上传失败。错误消息：".$err->message();
				return false;
		}
		$cdn_path= $ret["key"] ;		
		return $cdn_path;	
	}
	
	
	//如果成功返回新纪录的ID
	protected function _storeItem($data)
	{	
		//存储数据
		$table = $this->getTable("item");
		
		if(!$table->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if(!$table->check())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if(!$table->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $table->id;//如果存储成功，直接返回改行记录的id 
		
	}
}
?>