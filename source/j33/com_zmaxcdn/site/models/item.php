<?php 
/**
 *	description:ZMAX媒体管理组件  资料模型文件
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

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

jimport('joomla.application.component.modeladmin');

class zmaxcdnModelItem extends JModelAdmin
{
	public function getForm( $data = array() , $loadData = true)
	{
		//Get the form
		$form = $this->loadForm('com_zmaxcdn.item' ,'item' ,array('control' =>' jform' ,'load_data' => $loadData ));
		if(!$form)
		{
			return false;
		}
		
		return $form;
	}
	
	public function loadFormData()
	{
		//Load form data
		$data = $this->getItem();
		return $data;
	}
	
	public function getItem($pk = null)
	{
		if($item = parent::getItem($pk))
		{
			$registry = new JRegistry;
			$registry->loadString($item->attr);
			$item->attr = $registry->toArray();		
		}
		
		return $item;
	}
	
	public function saveEdit($data)
	{
		if(isset($data["attr"]) && is_array($data["attr"])  )
		{
			$registry = new JRegistry;
			$registry->loadArray($data["attr"]);
			$data["attr"] = (string) $registry;
		}
		return parent::save($data);
	}
	
	public function save($data)
	{		
		$file = $data["files"];
		
		//上传文件
		require(JPATH_COMPONENT_ADMINISTRATOR."/libs/upload/zmUpload.php");
		$uploader = new zmUpload($file);
		$name = $uploader->getFileName();//得到用户上传的文件的名称
		$size = $uploader->getSize();
		$type = $uploader->getFileType();
		$catid = $data["catid"];
		$filename = $uploader->makeName($type ,$catid); //存储在本地服务器上的名称
		
		//重新设置上传后的名称
		$uploader->setFileName($filename);
		$uploader->setPath(JPATH_COMPONENT_ADMINISTRATOR."/source");
		if(!$uploader->doUpload())//开始执行上传
		{
			$this->_message =zmUpload::getWrongMessage(); 
			return false;
		}
		
		$cdn_path ="";
		$params = JComponentHelper::getParams("com_zmaxcdn");
		$enableQiniu = $params->get("enable_qiniu",'0');
		
		if($enableQiniu)
		{
			//上传文件到七牛
			require_once(JPATH_COMPONENT.DS."libs".DS."vendor".DS."autoload.php");
			
			// 设置信息
			$APP_ACCESS_KEY = $params->get("accessKey");
			$APP_SECRET_KEY = $params->get("secretKey");
			$bucket = $params->get("bucket");
			
			//得到一个认证对象
			$auth = new Auth($APP_ACCESS_KEY, $APP_SECRET_KEY);
			$token = $auth->uploadToken($bucket);
			$uploadManager = new UploadManager();
			$uploadFilePath = $uploader->getPath().DS.$uploader->getFileName();
			
			//执行上传 (多次上传同一个文件不会有任何的作用)
			list($ret, $err) = $uploadManager->putFile($token, null, $uploadFilePath);
			if ($err != null) 
			{
				$this->_message = "上传失败。错误消息：".$err->message();
				return false;
			}
			$cdn_path= $ret["key"] ;		
		}
		
		//存储数据
		$data["cdn_path"]	= $cdn_path;		
		$data['local_path']="/source/".$uploader->getFileName();
		$data["uid"] = JFactory::getUser()->id;
		$data["name"] = $name;
		$data["filename"]=$filename;
		$data['size']=$size;
		$data['type'] = $type;
		$data["create_date"] = $this->getPostDate();
		
		$session = JFactory::getSession();
		$session->set("com_zmaxcdn.item.data" ,json_encode($data));
		return parent::save($data);
	}
	
	//得到提交订单的时间
    public function getPostDate()
	{
		date_default_timezone_set('PRC');
		$date= date("Y-m-d H:i:s");
		$now = JDate::getInstance($date);
		return $now->toSql();
	}
	
	//检查一个资源是否可以下载
	public function checkAuthority($id)
	{
		//得到项目
		//$item = zmaxitemHelper::loadItemById($id,"i.*");
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("*")->from("#__zmaxcdn_item")->where("id=".$id);
		$db->setQuery($query);
		$item = $db->loadObject();

		
		//得到用户
		$user = JFactory::getUser();
		
		//下载类型
		//$value = $item->download_type;
		//默认下载类型为任何人都可以下载，即游客下载
		$value = 0;
		
		$info = new downloadInfo();
		$info->id = $id;
		$info->uid = $user->id;
		switch($value)
		{
			case "0"://游客下载
					$info->canDownload = true;
					$url = $item->cdn_path?$item->cdn_path:"media/zmaxcdn/".$item->filename;
					$info->url = $url;
					;break; 
			/*
			case "2":;
			case "4":;
			case "1"://注册下载
					if($user->guest) //游客
					{
						$info->canDownload = false;//不允许下载
						$info->info="很抱歉，该资源需要注册用户权限！请登陆";
					}
					else
					{
						$info->canDownload = true;
						$info->url = $item->download_url;	
					}
				;break; 
			case "3":
					$paidItemList = $this->getPaidItemList($user->id);
					$info->canDownload = false;//不允许下载
					$info->info="很抱歉，该资源需要付费购买，如果你不清楚如何操作，请联系管理员！";
					
					if(in_array($id ,$paidItemList))
					{
						$info->canDownload = true;//不允许下载
						$info->url = $item->download_url;
					}
					
					
				;break; //付费下载
			/*	
			case "2":   //To DO 
				;break; //积分下载
			
			case "4": //TO DO
				;break; //用户组下载
			*/
			
			default:break;
		}
		return $info;
	}
}
	

class downloadInfo{
	public $canDownload ; //是否能下载
	public $info ;//附带的信息 作为提示信息用
	public $url ;//下载文件的地址
	public $id;//附件的id
	public $uid;//用户的id
}



?>