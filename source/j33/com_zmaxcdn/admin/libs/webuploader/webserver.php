<?php 
/**
 *	description:ZMAX媒体管理组件 webupload 服务端代码
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
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/common.php");
class zmaxcdnWebserver {
	protected $bLog = false;
	protected $file = null;
	protected $maxTime = 5;
	protected $tempFileMaxAge = 10;
	protected $uploadDir = "";
	protected $cleanupTargetDir=true;	
	public $error =null;
	public $fileInfo = array();
	
	public function enableLog()
	{
		$this->bLog = true;
	}
	public function disableLog()
	{
		$this->bLog = false;
	}
	
	public function __construct()
	{
		$this->file = fopen(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/webuploader/webupload_debug.txt","a+");
		$this->uploadDir = JPATH_ROOT."/media/zmaxcdn/";
	}
	public function getFileInfo()
	{
		return $this->fileInfo;
	}
	
	public function getError()
	{
		return $this->error;
	}
	
	public function writeLog($textInfo)
	{
		if($this->bLog)
		{
			fwrite($this->file,$textInfo);
			fwrite($this->file,"\r\n");
		}
	}
	
	public function setError($message)
	{
		$this->error[] = $message;
	}
	
	
	
	
	
	public function uploadFile()
	{
		//确保没有缓存
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
	
		// 支持CORS跨域资源共享
		// header("Access-Control-Allow-Origin: *");
		// other CORS headers if any...
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') 
		{
			$this->setError("程序不支持CORS跨域资源共享");
			return false;
		}
		

		if ( !empty($_REQUEST[ 'debug' ]) ) 
		{
			$random = rand(0, intval($_REQUEST[ 'debug' ]) );
			if ( $random === 0 ) {
				header("HTTP/1.0 500 Internal Server Error");
				$this->setError("HTTP/1.0 500 Internal Server Error");
				return false;
			}
		}
		$this->disableLog();
		$catid = $_REQUEST["catid"];
		$extension="com_content";
				
		if(isset($_REQUEST["extension"]) &&  $_REQUEST["extension"]!="" )
		{
			$extension = $_REQUEST["extension"];
		}
		
		
		@set_time_limit($this->maxTime * 60);


		// Settings
		$targetDir = JPATH_ROOT."/administrator/components/com_zmaxcdn/zmaxupload_temp";	
		$targetDir = JPath::clean($targetDir);
		$uploadDir = $this->uploadDir;//文件上传到的文件夹
		
		$cleanupTargetDir = $this->cleanupTargetDir; // Remove old files
		$maxFileAge = $this->tempFileMaxAge * 3600; // Temp file age in seconds


		//创建临时文件夹
		if (!file_exists($targetDir)) 
		{
			@mkdir($targetDir);
		}

		//创建目标文件夹
		if (!file_exists($uploadDir)) 
		{
			@mkdir($uploadDir);
		}

		//获得文件的名称
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}
		//进行编码转换，支持中文文件名称
		$fileName=iconv("UTF-8","GBK",$fileName);
		
		
		//这里就获得了文件的名称了
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		$this->writeLog("file path:".$filePath);
		

		//检查是否启用了分片传输		
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

		//清除旧的临时文件
		if ($cleanupTargetDir) 
		{
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				$message = "Failed to open temp directory.";
				$this->writeLog($message);
				$this->setError($message);
				return false;
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
					continue;
				}

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}
		

		//打开一个临时文件
		if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) 
		{
			$message = "{$filePath}_{$chunk}.parttmp"."不能打开输出文件，请检查文件的写权限";
			$this->writeLog($message);
			$this->setError($message);
			return false;
		}
		

		//检查文件数组
		if (!empty($_FILES)) 
		{
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) 
			{
				if($_FILES["file"]["error"])
				{
					$error = $this->formatUploadError($_FILES["file"]["error"]);
					$this->writeLog($error);
					$this->setError($error);
					return false;
				}
				else
				{
					$this->writeLog($_FILES["file"]["tmp_name"]." is_uploaded_file 校验不通过！");
					$this->setError($_FILES["file"]["tmp_name"]." is_uploaded_file 校验不通过！");
					return false;
				}
			}

			
			//读文件
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) 
			{
				$this->writeLog("Failed to open input stream.");
				$this->setError("Failed to open input stream.");
				return false;
			}
			$this->fileInfo["name"] = $_FILES["file"]["name"];
			// v4.1.6版本后将doc_type去掉 将原先的type保留为 文件的扩展名
			//$this->fileInfo["type"] = $_FILES["file"]["type"];
			$extName = zmaxcdnCommonHelper::getExt($this->fileInfo["name"]);
			$newFileName = zmaxcdnCommonHelper::makeName($extName,$catid);
			$uploadPath = $uploadDir . DIRECTORY_SEPARATOR .$newFileName;
			$this->fileInfo["filename"] = $newFileName;
		}
		else 
		{
			if (!$in = @fopen("php://input", "rb")) 
			{
				$message = "Failed to open input stream.";
				$this->writeLog($message);
				$this->setError($message);
				return false;
			}
		}
		
		
		while ($buff = fread($in, 4096))
		{
			fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
		
		$index = 0;
		$done = true;
		
		//开始测试是否所有的分片都存在
		for( $index = 0; $index < $chunks; $index++ ) 
		{
			if ( !file_exists("{$filePath}_{$index}.part") ) //检查分片文件是否存在
			{
				$done = false;
				break;
			}
		}
		
		//如果每一个分片都存在，说明上传成功了。那么下一步就是合并文件了
		if ( $done ) 
		{
			if (!$out = @fopen($uploadPath, "wb")) {
				$message = "Failed to open output stream".$uploadPath;
				$this->writeLog($message);
				$this->setError($message);
				return false;
			}
		

			if ( flock($out, LOCK_EX) ) {
				for( $index = 0; $index < $chunks; $index++ ) {
					if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
						break;
					}

					while ($buff = fread($in, 4096)) {
						fwrite($out, $buff);
					}

					@fclose($in);
					@unlink("{$filePath}_{$index}.part");
				}

				flock($out, LOCK_UN);
			}
			@fclose($out);
			//重新计算文件的大小
			$handle = fopen($uploadPath,"r");
			$fstat = fstat($handle);
			$this->fileInfo["size"] = $fstat["size"];
			$this->fileInfo["done"] = true;
			$this->fileInfo["catid"] =$catid;
			$this->fileInfo["extension"]=$extension;
		}
		
		fclose($this->file);
		
		return true;
	}
	
	public function formatUploadError($errCode)
	{
		switch ($errCode)
		{
			case 0: $msg="没有错误发生,文件成功上传!";break;
			case 1: $msg="上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值";break;
			case 2: $msg="上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值";break;
			case 3: $msg="文件只有部分被上传";break;
			case 4: $msg="没有文件被上传";break;
			default:
				$msg="formatUploadError:未知错误!";break;
					
		}
		
		return $msg;
	}

}
?>