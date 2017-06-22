<?php 
/**
 *	description:ZMAX媒体管理组件 帮助类文件
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

class zmaxcdnHelper
{
	//当到当前表单的时间
	static public function getPostDate()
	{
		date_default_timezone_set('PRC');
		$date= date("Y-m-d H:i:s");
		$now = JDate::getInstance($date);
		return $now->toSql();
	}
	
	//得到文件的扩展名
	static public function getExt($fileName)
	{
		return strtolower ( strrchr($fileName,'.') );
	}
	
	
	//获得文件唯一的名字
	static public function makeName($extName ,$catid)	
	{		
		$year = date("Y");
		$month = date("m");
		$day = date("d");
		$hour = date("H");
		$min = date("i");
		$sec = date("s");
		$random = self::makeRandomNum(6);
		$filename = $catid."_".$year.$month.$day.$hour.$min.$sec."_".$random.$extName;
		return $filename;
	}
	
	  //产生随机数
    static public  function makeRandomNum($length)
    {
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
		$key="";
		for($i=0;$i<$length;$i++)
		{
			$key .= $pattern{rand(0,35)};
		}
		return $key;
   }
	

	
	/**
	 *  该函数的功能是获得一个项目在文章中的显示标签
	 *
	 *   参数 ： $data 项目上传成功后的数据。是一个对象
	 *  
	 *   返回： $tag  字符串 该资源对应的字符串
	 *   
	 */
	static public function getTag($id)
	{
		$item =self::getItemById($id);
		$url = self::getSourecUrl($item);
		$extName = self::getExt($item->name);
		$tag="";
		//如果是图片
		if(self::isImage($extName))
		{
			$tag ='<img class="zmaximage" src="'.$url.'" alt="'.$item->name.'" />';	
		}
		if(self::isVideo($extName))
		{
			$tag ='<a class="zmaxvideo" href="'.$url.'" alt="'.$item->name.'" >'.$item->name.'</a>';		
		}
		if(self::isPackage($extName))
		{
			$tag ='<a class="zmaxpackage" href="#" id="'.$id.'"  alt="'.$item->name.'" >'.$item->name.'</a>';	
		}
		if(self::isDoc($extName))
		{
			$tag ='<a class="zmaxdoc" href="'.$url.'" alt="'.$item->name.'" >'.$item->name.'</a>';		
		}
		if(self::isPdf($extName))
		{
			$tag ='<a class="zmaxpdf" href="'.$url.'" alt="'.$item->name.'" >'.$item->name.'</a>';		
		}
		if(self::isExcel($extName))
		{
			$tag ='<a class="zmaxexcel" href="'.$url.'" alt="'.$item->name.'" >'.$item->name.'</a>';		
		}
		if(self::isText($extName))
		{
			$tag ='<a class="zmaxtext" href="'.$url.'" alt="'.$item->name.'" >'.$item->name.'</a>';		
		}
		return $tag;
	}
	
	static public function getItemPath($item)
	{
		$cdnPath = self::getCDNUrl($item);
		if($cdnPath)
		{
			return $cdnPath;
		}
		$url="";
		if($item->local_path)
		{
			$url = $item->local_path;	
		}
		return $url;
	}
	
	/**
	 * 得到资源的最终调用地址
	 *  计算的方法如下： 如果CDN资源存在，那么优先调用CDN资源，如果CDN资源不存在，那么调用本地资源
	 */
	static public function getSourecUrl($item)
	{
		
		$cdnPath = self::getCDNUrl($item);
		if($cdnPath)
		{
			return $cdnPath;
		}
		$url="";
		if($item->local_path)
		{
			$url = JUri::root().$item->local_path;	
		}
		return $url;
	}
	
	static public function getCDNUrl($item)
	{
		return $item->cdn_path;
	}
	
	static public function getLocalUrl($item)
	{
		return JUri::root().$item->local_path;
	}
	
	
	/**
	 * 通过调用的资源来追踪到最终的项目
	 *
	 */
	static public function loadItemByUrl($url)
	{
		if(!$url)
		{
			return null;
		}
		
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		//如果是以http开头，那么肯定是CDN资源
		if(substr($url,0,strlen("http:"))=="http:") 
		{
			$query ->select("*")->from("#__zmaxcdn_item")->where("cdn_path=".'"'.$url.'"');
			$db->setQuery($query);
			return $db->loadObject();
		}
		else
		{
			$query->select("*")->from("#__zmaxcdn_item")->where("local_path=".'"'.$url.'"');								
			$db->setQuery($query);
			return $db->loadObject();			
		}
		return null;
	}
	
	
	
	static public function getPreview($item)
	{
		$extName = self::getExt($item->title);
		$preView = "";
		$defaultImage = JUri::root()."components/com_zmaxcdn/images/default.png";
		$typeIconPath = JUri::root()."administrator/components/com_zmaxcdn/images/";
		if(self::isImage($extName))
		{
			if($item->cdn_path)
			{
				$preView = $item->cdn_path;
				$html = '<a class="modal" href="'.$preView.'" ><img class="zmaximage" src="'.$preView.'" alt="图片"/></a>';
				return $html;
			}
			else if($item->local_path)
			{
				$preView = self::getLocalUrl($item);
			}
			else {
				$preView = $defaultImage;
			}
			
			
		}
		else{
			$typeIcon = self::getTypeIcon($extName);
			$preView = $typeIconPath.$typeIcon;
		}
		
		$html = '<a class="modal" href="'.$preView.'" ><img class="zmaximage" src="'.$preView.'" alt="图片"/></a>';
		return $html;
	}
	
	static public function getModalPreview($item)
	{
		$extName = self::getExt($item->title);
		$preView = "";		
		$defaultImage = JUri::root()."components/com_zmaxcdn/images/default.png";
		$typeIconPath = JUri::root()."administrator/components/com_zmaxcdn/images/";
		if(self::isImage($extName))
		{
			if($item->cdn_path)
			{
				$preView = $item->cdn_path;
				$html = '<img class="zmaximage" src="'.$preView.'" alt="图片"/>';
				return $html;
			}
			else if($item->local_path)
			{
				$preView = $item->local_path;
			}
			else {
				$preView = $defaultImage;
			}
			
			
		}
		else{
			$typeIcon = self::getTypeIcon($extName);
			$preView = $typeIconPath.$typeIcon;
		}
		
		$html = '<img class="zmaximage" src="'.$preView.'" alt="图片"/>';
		return $html;
	}
	
	static public function getTypeIcon($extName)
	{
		if(self::isImage($extName))
		{
			return "image.png";
		}
		if(self::isVideo($extName))
		{
			return "video.png";
		}
		if(self::isPackage($extName))
		{
			return "package.png";
		}
		if(self::isDoc($extName))
		{
			return "doc.png";
		}
		if(self::isPdf($extName))
		{
			return "pdf.png";
		}
		if(self::isExcel($extName))
		{
			return "excel.png";
		}
		if(self::isText($extName))
		{
			return "text.png";
		}
		return "unknow.png";
	}
	
	//判断一个文件的扩展名是否是图片类型标签
	static public function isImage($extName)
	{
		if($extName ==".png" || $extName ==".jpg" || $extName ==".jpeg" || $extName ==".gif" || $extName ==".bmp" )
		{
			return true;
		}
		return false;
	}
	
	//判断一个文件是否是视频类文件
	static public function isVideo($extName)
	{
		if($extName ==".avi" || $extName ==".mp4" || $extName ==".wma" || $extName ==".rm" || $extName ==".flash" ||   $extName ==".3gp")
		{
			return true;
		}
		return false;
	}
	
	//判断一个文件是否是压缩包
	static public function isPackage($extName)
	{
		if($extName ==".zip" || $extName ==".rar" || $extName ==".gz" || $extName ==".iso" || $extName ==".gzip" ||   $extName ==".tar")
		{
			return true;
		}
		return false;
	}
	
	//判断一个文件是否是Excel
	static public function isExcel($extName)
	{
		if($extName ==".xls" || $extName ==".csv")
		{
			return true;
		}
		return false;
	}
	
	//判断一个文件是否是Doc
	static public function isDoc($extName)
	{
		if($extName ==".doc")
		{
			return true;
		}
		return false;
	}
	//判断一个文件是否是Doc
	static public function isPdf($extName)
	{
		if($extName ==".pdf")
		{
			return true;
		}
		return false;
	}
	//判断一个文件是否是Text
	static public function isText($extName)
	{
		if($extName ==".txt" || $extName ==".html" || $extName ==".htm" || $extName ==".sql")
		{
			return true;
		}
		return false;
	}
	
	
	
	/**
	 * 该函数获得指定分类的所有子分类的id
	 *
	 * 参数：
	 *       $parentId  分类的id 
	 *       $includeSelf  是否包含自己
	 */
	static public function getChildCateIds($id ,$includeSelf = true)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("lft,rgt");
		$query->from("#__categories");
		$query->where("id =".$id);
		$db->setQuery($query);
		$item = $db->loadObject();
		
		$query=$db->getQuery(true);
		$query->select("id");
		$query->from("#__categories");
		$query->where("lft BETWEEN $item->lft AND $item->rgt ");
		$db->setQuery($query);
		$items = $db->loadColumn();
		if(!$includeSelf)
		{
			unset($items[0]);
		}
		return $items;
	}
	
	/**
	 * 文件大小格式化
	 * @param integer $size 初始文件大小，单位为byte
	 * @return array 格式化后的文件大小和单位数组，单位为byte、KB、MB、GB、TB
	 */
	static public function formatFileSize($size = 0, $dec = 2) 
	{
		$unit = array("B", "KB", "MB", "GB", "TB", "PB");
		$pos = 0;
		while ($size >= 1024) 
		{
			$size /= 1024;
			$pos++;
		}
		$result['size'] = round($size, $dec);
		$result['unit'] = $unit[$pos];
		return $result['size'].$result['unit'];
	}

	static public function formatDocType($type)
	{
		$type = strtolower($type);
		$docType="";
		switch($type)
		{
			case "text.png":$docType="文本文件";break;
			case "package.png":$docType="压缩包";break;
			case "image.png":$docType="图片文件";break;
			case "video.png":$docType="视频文件";break;
			case "word.png":$docType="WORD文档";break;
			case "excel.png":$docType="EXCEL文档";break;
			case "pdf.png":$docType="PDF文档";break;
			default:$docType="其他文档";break;
		}
		return $docType;
	}
	
	static public function getItemId($data)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id");
		$query->from("#__zmaxcdn_item");
		$query->where('uid='.$data->uid);
		$query->where('name="'.$data->name.'"');
		$query->where('filename="'.$data->filename.'"');
		$db->setQuery($query);
		$item= $db->loadObject();
		return $item->id;
	}
	
	static public function getItemById($id)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("*");
		$query->from("#__zmaxcdn_item");
		$query->where('id='.$id);
		
		$db->setQuery($query);
		$item= $db->loadObject();
		return $item;
	}
	
	
	static public function loadConfig($configId="")
	{
		if($configId) //如果设置了ID，那么将加载这个ID指定的配置文件，系统并不负责判断当前的config_id是否和当前的客户端相匹配
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select("config")->from("#__zmaxcdn_upload_config");
			$query->where("id=".$configId);
			$db->setQuery($query);
			$config = $db->loadObject();
			if($config)
			{
				return json_decode($config->config);
			}
		}
		return self::loadDefaultConfig();
	}
	
	static public function loadDefaultConfig()
	{
		$app = JFactory::getApplication();
		$client = $app->isAdmin()?"admin":"site";
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("config")->from("#__zmaxcdn_upload_config");
		$query->where('client="'.$client.'"');
		$query->where('`default`=1');
		$db->setQuery($query);
		$config = $db->loadObject();
		return json_decode($config->config);
	}
	
	/**##########################################
	 *  警告：下面的函数需要废除  代码待删除 TODO
	 *###########################################*/
	
	//通过指定的ID，来加载配置文件 ，
	// 如果ID为空，那么就加载默认的配置文件
	static public function loadUploadConfigById($id="")
	{
		if(!$id)
		{
			//需要加载默认的配置文件 TODO
			return null;
		}
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("*")->from("#__zmaxcdn_upload_config");
		$query->where("id=".$id);
		$db->setQuery($query);
		$item = $db->loadObject();
		
		if($item)
		{
			$registry = new JRegistry;
			$registry->loadString($item->config);
			$item->config = $registry->toArray();	
		}
		
		return $item;
	}
}
?>