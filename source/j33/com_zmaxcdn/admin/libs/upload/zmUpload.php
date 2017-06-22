<?php 
/**
 *	description:ZMAX媒体管理组件 ZMAX上传类 
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

//该类已经废弃不用
class zmUpload 
 {
	protected $max_size = 10240000 ; // 10 M
	protected $name;// HTML表单上的form名称
	protected $type = array('.zip','.rar','.exe','.jpg','.png','.bmp','.gif');
	protected $fileName ;//文件在服务器上的名称，默认为文件上传的名称
	protected $path ;//server path
	
	
	protected $file_name;//上传文件客户机上的名称
	protected $file_tmp_name ;//文件上传后在服务器上临时文件名
	protected $file_size;//上传文件的大小 ，单位为字节
	protected $file_type;//上传文件的类型
	// file upload state 
	// 0: 没有发生错误
    // 1: 上传的文件超过了php.ini中upload_max_filesize选项限制的值
    // 2: 上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值
    // 3: 文件只有部分被上传
    // 4: 没有文件被上传	
	protected $file_state;
	
	static protected  $error_msg;
	
	static public function getExt($fileName)
	{
		return strrchr($fileName,'.');
	}
	
	public function debugPrint()
	{
		echo "FILENAME: ".$this->file_name."<br />";
		echo "FILE_TMP_ANME: ".$this->file_tmp_name."<br />";
		echo "FILE_SIZE: ".$this->file_size."<br />";
		echo "FILE_STATE: ".$this->file_state."<br />";
		
	}
	
	public function zmUpload($name)
	{
		$this->init($name);	
	}
	/**
	 *	将$_FILE变量的值初始化
	 *  参数：需要一个产生文件变量的数组
	 */
	protected function init($uploadfile)
	{
			$this->file_name = $uploadfile['name'];
			//尝试解决中文乱码的问题
			//$this->file_name = iconv("utf-8","gbk",$this->file_name);
			$this->file_size = $uploadfile['size'];
			$this->file_tmp_name = $uploadfile['tmp_name'];
			$this->file_state = $uploadfile['error'];
			
			$curDir = getcwd();
			$this->setPath($curDir);
			
			$this->setFileName($this->file_name);
			
			$this->file_type=zmUpload::getExt($this->fileName);
	}
	
	protected function checkType($type)
	{
		// NOT USE JUST RETURN TRUE
		//return in_array($this->file_type, $this->type);
		return true;
	}
	
	protected function checkSize($size)
	{
		//NOT USE JUST RETURN TURE
		//if($this->max_size >= $size)
		//{
		//	return true;
		//}
		return true;
	}
	
	protected function checkDir($dir)
	{
		if(!file_exists($dir))
		{
			if(!mkdir($dir ,0777))
			{
				return false;
			}
		}
		return true;
	}
	
	static protected function setWrongMessage($msg)
	{
		zmUpload::$error_msg = $msg;
	}
	
	static public function getWrongMessage()
	{
		return zmUpload::$error_msg ;
	}
	
	public function doUpload()
	{
		if(!$this->checkType($this->file_type))
		{
			zmUpload::setWrongMessage("你不能上传类型为$this->file_type 的文件!");
			return false;
		}
		
		if(!$this->checkSize($this->file_size))
		{
			zmUpload::setWrongMessage("你上传的文件大小$this->file_size kb超过最大限制$this->max_size kb!");
			return false;
		}
		
		if(!$this->checkDir($this->path))
		{
			zmUpload::setWrongMessage("你指定的目录($this->path )不存在且无法创建!");
			return false;
		}
		
		if(file_exists($this->path."/".$this->fileName))
		{
			zmUpload::setWrongMessage("$this->path / $this->fileName 已经存在!");
			//文件已经存在不需要退出执行，只需要给出一个警告就行了
			//return false;
		}
		
		if(!move_uploaded_file($this->file_tmp_name ,$this->path."/".$this->fileName))
		{
			zmUpload::setWrongMessage("$this->file_tmp_name 不是合法的上传文件!");
			return false;
		}
		return true;
		
		
	}
	
	//设置在表单字段中 file类型的name
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	
	public function getSize()
	{
		return $this->file_size;
	}
	
	public function setFileName( $fileName)
	{
		$this->fileName = $fileName;
	}
	
	public function getFileName ()
	{
		return $this->fileName;
	}
	
	//需要考虑是绝对路径还是相对路径
	//如果是以 /开头表示绝对路径，为网站的根目录
	//否则为相对路径，为本文件所在的路径
	public function setPath($path)
	{
		$this->path = $path;
	}
	public function getPath()
	{
		return $this->path;
	}
	
	public function getState()
	{
		return $this->file_state;
	}
	
	//$type接受字符串参数 不同的类型用分号隔开。如：zip;rar;exe;jpg
	public function setType($type)
	{
		//测试是不是数组 
		//如果不是就转换为数组
	}
	//确保转换出去的一定是数组
	public function getType()
	{
		return $this->type;
	}
	public function getFileType()
	{
		return $this->file_type;
	}

	public function makeName($type ,$catid)	
	{		
		$year = date("Y");
		$month = date("m");
		$day = date("d");
		$hour = date("H");
		$min = date("i");
		$sec = date("s");
		$filename = $catid."_".$year.$month.$day.$hour.$min.$sec.$type;
		return $filename;
	}
 }

?>
