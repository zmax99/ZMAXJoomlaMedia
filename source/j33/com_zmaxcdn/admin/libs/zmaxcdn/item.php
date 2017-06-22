<?php 
/**
 *	description:ZMAX媒体管理组件 资料库文件
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

jimport("joomla.filesystem.file");
//兼容j25 j3x
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/media.php");

class zmaxcdnItemHelper{
	
	//通过资源的ID来获得一个Item对象
	static public function getItemById($id)
	{
		if(!$id)
		{
			return null;
		}
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("*");
		$query->from("#__zmaxcdn_item");
		$query->where('id='.$id);
		$db->setQuery($query);
		$item= $db->loadObject();
		return $item;
	}
	
	//通过调用的资源来追踪到最终的项目
	// 系统保证每一个url只能对应一个记录
	static public function getItemByUrl($url)
	{
		if(!$url)
		{
			return null;
		}
		
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		//如果是以http开头，那么肯定是CDN资源 
		if(substr($url,0,strlen("http"))=="http") 
		{
			$query ->select("*")->from("#__zmaxcdn_item")->where("cdn_path=".'"'.$url.'"');
			$db->setQuery($query);
			return $db->loadObject();
		}
		else
		{
			$query->select("*")->from("#__zmaxcdn_item")->where("local_path=".'"'.$url.'"');								
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!$item)
			{
				//因为新的版本将不再存储media/zmaxcdn/这个前缀，需要向后兼容
				if(substr($url,0,strlen("media/zmaxcdn/"))=="media/zmaxcdn/") 	
				{
					$url = substr($url,strlen("media/zmaxcdn/"));
					$query = $db->getQuery(true);
					$query->select("*")->from("#__zmaxcdn_item")->where("local_path=".'"'.$url.'"');								
					$db->setQuery($query);
					$item = $db->loadObject();
				}	
			}
			
			return $item;			
		}
		return null;
	}
	
	//通过资源的属性来获该资源的ID。
	// 系统保证 uid +  name  这2者可以唯一确定一个资源 ,如果需要精确匹配也可提供资源的文件名称
	static public function getItemId($name ,$uid ,$filename="")
	{
		if($name=="" || $uid=="")
		{
			return null;
		}
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id");
		$query->from("#__zmaxcdn_item");
		$query->where('uid='.$uid);
		$query->where('name="'.$name.'"');
		if($filename)
		{
			$query->where('filename="'.$data->filename.'"');	
		}
		$db->setQuery($query);
		$item= $db->loadObject();
		if(!$item)
		{
			return null;
		}
		
		return $item->id;
	}
	
	//得到资源的远程访问地址
	static public function getCDNUrl($item)
	{
		return $item->cdn_path;
	}
	
	//得到资源的本地访问地址
	static public function getLocalUrl($item)
	{
		
		if(!JFile::exists(JPATH_ROOT.DS.$item->local_path))
		{
			//可能是以前的版本，需要将前缀加上
			return JUri::root()."media/zmaxcdn/".$item->local_path;
		}
		return JUri::root().$item->local_path;
		
	}
	
	
	// 得到资源的最终调用地址
	// 计算的方法如下： 如果CDN资源存在，那么优先调用CDN资源，如果CDN资源不存在，那么调用本地资源
	static public function getItemUrl($item)
	{
		$cdnPath = self::getCDNUrl($item);
		if($cdnPath)
		{
			$url = $cdnPath;
		}
		else
		{
			$url = self::getLocalUrl($item);
		}
		return $url;
	}
	//得到资源作为字段时的值
	static public function getItemValue($item)
	{
		$cdnPath = self::getCDNUrl($item);
		if($cdnPath)
		{
			$url = $cdnPath;
		}
		else
		{
			return $item->local_path;
		}
		return $url;
	}
	
	
	
	//得到项目的预览图片
	//计算方法如下： 
	// 1, 优先获得项目设置的图片
	// 2, 如果资源为图片，那么将返回资源本身
	// 3, 返回该资源代表的类型的图片
	static public function getItemPreviewHtml($item ,$isModal = true)
	{
		$imageSrc = self::getItemPreview($item);
		if($isModal)
		{
			$html = '<a class="modal" href="'.$imageSrc.'" ><img class="zmaximage" src="'.$imageSrc.'" alt="图片"/></a>';	
		}
		else
		{
			$html = '<img class="zmaximage" src="'.$imageSrc.'" alt="图片"/>';
		}
		
		return $html;
	}
	static public function getItemPreview($item)
	{
		$imageSrc="";
		if($item->image)
		{
			$image = json_decode($item->image);
			if($image->image_intro) //图片存在的话
			{
				if(substr($image->image_intro ,0,strlen("http"))=="http")
				{
					$imageSrc = $image->image_intro;
				}
				else
				{
					$imageSrc = JUri::root().$image->image_intro;	
				}	
			}
		}
		
		if(!$imageSrc) //如果在图片设置栏中没有设置图片，那么继续获得
		{
			if($item->type=="")
			{
				$item->type="image";
			}
			
			
			if(zmaxcdnMediaHelper::isImage($item->type))
			{
				$imageSrc = self::getItemUrl($item); //直接显示项目本身的图片
			}
		}
		
		if(!$imageSrc) //如果不是图片 ，那么只得显示该资源的默认代表图片了
		{
			$imageSrc = zmaxcdnMediaHelper::getTypeIcon($item->type);
		}
		
		return $imageSrc;
	}
	static public function getItemDownloadHtml($item ,$config)
	{
		$download = "<span class='text-danger'>不允许</span>";
		if($config->allow_download)
		{
			$download='<a href="#" class="zmaxpackage" data-id="'.$item->id.'" ><i class="icon-download"></i>下载</a>';	
		}
		return $download;
	}
	
	/**
	 *  该函数的功能是获得一个项目在文章中的显示标签
	 *   返回： $tag  字符串 该资源对应的字符串
	 */
	static public function getItemTagHtmlById($id)
	{
		$item =self::getItemById($id);
		$url = self::getItemUrl($item);
		//兼容旧的版本
		if($item->type=="")
		{
			$item->type="image";
		}
			
		if(zmaxcdnMediaHelper::isImage($item->type))
		{
			return $tag ='<img data-marker="zmax" data-id="'.$item->id.'" data-type="image" data-action="display" class="zmax-cdn-item zmaximage" src="'.$url.'"  title="'.$item->name.'" alt="'.$item->name.'" />';	
		}
		if(zmaxcdnMediaHelper::isVideo($item->type))
		{
			return $tag ='<a data-marker="zmax" data-id="'.$item->id.'" data-type="video" data-action="play" class="zmax-cdn-item zmaxvideo"  alt="'.$item->name.'" >'."[播放视频:]".$item->name.'</a>';
		}
		if(zmaxcdnMediaHelper::isPackage($item->type))
		{
			return $tag ='<a data-marker="zmax" data-id="'.$item->id.'" data-type="package" data-action="download" class="zmax-cdn-item zmaxpackage"   alt="'.$item->name.'" >'."[下载文件:]".$item->name.'</a>';	
		}
		if(zmaxcdnMediaHelper::isDoc($item->type))
		{
			return $tag ='<a data-marker="zmax" data-id="'.$item->id.'"  data-type="doc"  data-action="download"  class="zmax-cdn-item zmaxdoc"  >'."[下载文件:]".$item->name.'</a>';		
		}
		if(zmaxcdnMediaHelper::isPdf($item->type))
		{
			return $tag ='<a data-marker="zmax" data-id="'.$item->id.'"  data-type="pdf"  data-action="download"  class="zmax-cdn-item zmaxpdf"  >'."[下载文件:]".$item->name.'</a>';		
		}
		if(zmaxcdnMediaHelper::isExcel($item->type))
		{
			return $tag ='<a data-marker="zmax" data-id="'.$item->id.'"  data-type="excel"  data-action="download"  class="zmax-cdn-item zmaxexcel"  >'."[下载文件:]".$item->name.'</a>';		
		}
		if(zmaxcdnMediaHelper::isText($item->type))
		{
			return $tag ='<a data-marker="zmax" data-id="'.$item->id.'"  data-type="text"  data-action="download"  class="zmax-cdn-item zmaxtext"  >'."[下载文件:]".$item->name.'</a>';		
		}
		
		return $tag ='<a data-marker="zmax" data-id="'.$item->id.'"  data-type="package"  data-action="download"  class="zmax-cdn-item zmaxunkonw"  >'."[下载文件:]".$item->name.'</a>';		
	}	
}
 ?>

