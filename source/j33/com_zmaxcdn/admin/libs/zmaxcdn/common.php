<?php 
/**
 *	description:ZMAX媒体管理组件 通用功能库文件
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

class zmaxcdnCommonHelper{
	
	//当到当前表单的时间
	static public function getPostDate()
	{
		date_default_timezone_set('PRC');
		$date= date("Y-m-d H:i:s");
		$now = JDate::getInstance($date);
		return $now->toSql();
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
	
	//得到文件的扩展名
	//返回带点的格式 如：.pdf ,.word ,.text
	static public function getExt($fileName)
	{
		return strtolower ( strrchr($fileName,'.') );
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
	

	static public function formatDocType($type)
	{
		$type = strtolower($type);
		$docType="";
		switch($type)
		{
			case "text":$docType="文本文件";break;
			case "package":$docType="压缩包";break;
			case "image":$docType="图片文件";break;
			case "video":$docType="视频文件";break;
			case "word":$docType="WORD文档";break;
			case "excel":$docType="EXCEL文档";break;
			case "pdf":$docType="PDF文档";break;
			default:$docType="其他文档";break;
		}
		return $docType;
	}
}
 
 ?>

