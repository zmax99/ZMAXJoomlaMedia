<?php 
/**
 *	description:ZMAX媒体管理组件 资源组模型文件
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
		$this->preprocessData('com_zmaxcdn.item', $data);
		return $data;
	}
	
	public function getItem($pk = null)
	{
		if($item = parent::getItem($pk))
		{
			$registry = new JRegistry;
			$registry->loadString($item->attr);
			$item->attr = $registry->toArray();

			$registry->loadString($item->image);
			$item->image = $registry->toArray();
			$item->path = $item->cdn_path?$item->cdn_path:$item->local_path;
		}
		
		if(!empty($item->id))
		{
			$item->tags = new JHelperTags;
			$item->tags->getTagIds($item->id ,'com_zmaxcdn.item');
		}
				
		return $item;
	}
	
	public function save($data)
	{	
		
		//检查PATH是否为空 
		if(!isset($data["path"]) || $data["path"]=="" )
		{
			$this->setError("你没有选择任何的资源!");
			return false;
		}
		
		//获得记录的ID
		if(!isset($data["id"]) || $data["id"]=="" )
		{
			//依据当前的用户，当前的path来获得记录的ID
			$item = zmaxcdnItemHelper::getItemByUrl($data["path"]);
			if(!$item->id)
			{
				$this->setError("系统找不到对应资源的ID！".$data["path"]);
				return false;	
			}
			$data["id"] = $item->id;
		}
		
		//设置用户的ID
		$data["uid"] = JFactory::getUser()->id;
			
		//更新修改时间
		$data["modify_date"]=zmaxcdnCommonHelper::getPostDate();
		
		
		if(isset($data["attr"]) && is_array($data["attr"])  )
		{
			$registry = new JRegistry;
			$registry->loadArray($data["attr"]);
			$data["attr"] = (string) $registry;
		}
		
		if(isset($data["image"]) && is_array($data["image"])  )
		{
			$registry = new JRegistry;
			$registry->loadArray($data["image"]);
			$data["image"] = (string) $registry;
		}
		
		
		return parent::save($data);
	}
	
	//检查一个资源是否可以下载
	public function checkAuthority($id)
	{
		//得到项目
		$item = zmaxcdnItemHelper::getItemById($id);

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
					$url = zmaxcdnItemHelper::getItemUrl($item);
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