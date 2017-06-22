<?php 
/**
 *	description:ZMAX媒体管理组件 媒体资料库文件
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

class zmaxcdnMediaHelper{
	
	//判断一个文件的扩展名是否是图片类型标签
	//这里需要处理extname 带点获得不带点的情况
	static public function isImage($extName)
	{
		$extName = strtolower($extName);
		$extName = trim($extName ,'.');
		if($extName =="png" || $extName =="jpg" || $extName =="jpeg" || $extName =="gif" || $extName =="bmp" )
		{
			return true;
		}
		//兼容以前的版本
		if(FALSE !== strpos($extName ,"image") || FALSE !== strpos($extName ,"png") || FALSE !== strpos($extName ,"jpeg") || FALSE !== strpos($extName ,"jpg") || FALSE !== strpos($extName ,"gif") || FALSE !== strpos($extName ,"bmp") )
		{
			return true;
		}
		
		return false;
	}
	
	//判断一个文件是否是视频类文件
	static public function isVideo($extName)
	{
		$extName = trim($extName ,'.');
		if($extName =="avi" || $extName =="mp4" || $extName =="wma" || $extName =="rm" || $extName =="flash" ||   $extName =="3gp")
		{
			return true;
		}
		return false;
	}
	
	//判断一个文件是否是压缩包
	static public function isPackage($extName)
	{
		$extName = trim($extName ,'.');
		if($extName =="zip" || $extName =="rar" || $extName =="gz" || $extName =="iso" || $extName =="gzip" ||   $extName =="tar")
		{
			return true;
		}		
		//兼容以前的版本
		if(FALSE !== strpos($extName ,"applicatio")  )
		{
			return true;
		}
		return false;
	}
	
	//判断一个文件是否是Excel
	static public function isExcel($extName)
	{
		$extName = trim($extName ,'.');
		if($extName =="xls" || $extName =="csv")
		{
			return true;
		}
		return false;
	}
	
	//判断一个文件是否是Doc
	static public function isDoc($extName)
	{
		$extName = trim($extName ,'.');		
		if($extName =="doc")
		{
			return true;
		}
		return false;
	}
	//判断一个文件是否是Doc
	static public function isPdf($extName)
	{
		$extName = trim($extName ,'.');
		if($extName =="pdf")
		{
			return true;
		}
		return false;
	}
	//判断一个文件是否是Text
	static public function isText($extName)
	{
		$extName = trim($extName ,'.');
		if($extName =="txt" || $extName =="html" || $extName =="htm" || $extName =="sql")
		{
			return true;
		}
		return false;
	}
	
	//得到代表该资源的图标
	static public function getTypeIcon($extName ,$fullpath=true)
	{
		$path="";
		if($fullpath)
		{
			$path = JUri::root()."administrator/components/com_zmaxcdn/images/";
		}
	
		if(self::isImage($extName))
		{
			return $path."image.png";
		}
		if(self::isVideo($extName))
		{
			return $path."video.png";
		}
		if(self::isPackage($extName))
		{
			return $path."package.png";
		}
		if(self::isDoc($extName))
		{
			return $path."doc.png";
		}
		if(self::isPdf($extName))
		{
			return $path."pdf.png";
		}
		if(self::isExcel($extName))
		{
			return $path."excel.png";
		}
		if(self::isText($extName))
		{
			return $path."text.png";
		}
		return $path."unknow.png";
	}
}
 
 ?>

