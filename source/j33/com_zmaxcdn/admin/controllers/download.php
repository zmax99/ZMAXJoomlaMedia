<?php
/**
 *	description:ZMAX媒体管理组件 资料下载控制器
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-08-24
 *  check date:2016-07-11
 *  checker :min.zhang
 *  @license GNU General Public License version 3, or later
 */
defined('_JEXEC') or die('you can not access this file!');

jimport('joomla.application.component.controlleradmin');	
class zmaxcdnControllerDownload extends JControllerAdmin
{
 	
	public function download()
	{
		$app =  JFactory::getApplication();
		$id = $app->input->get("id","","INT");
		$model = $this->getModel("item");		
		$result =  $model->checkAuthority($id);
		echo json_encode($result);
		$app->close();
	}

	public  function getFile()
	{
		//调整PHP的设置为下载做准备
		// For a certain unmentionable browser
		if (function_exists('ini_get') && function_exists('ini_set'))
		{
			if (ini_get('zlib.output_compression'))
			{
				ini_set('zlib.output_compression', 'Off');
			}
		}
		
		// Remove php's time limit 
		if (function_exists('ini_get') && function_exists('set_time_limit'))
		{
			if (!ini_get('safe_mode'))
			{
				@set_time_limit(0);
			}
		}
		
		//获得需要下载的资源
		$app =  JFactory::getApplication();
		$id = $app->input->get("id","","INT");
		$item = zmaxcdnitemHelper::getItemById($id);
		$filename = zmaxcdnitemhelper::getItemUrl($item);
		
		//开始执行下载
		$basename	 = @basename($filename);
		$filesize	 = @filesize($filename);
		$extension	 = strtolower(str_replace(".", "", strrchr($filename, ".")));
		
		$basename = $item->name;
		
		while (@ob_end_clean());
			@clearstatcache();
			// Send MIME headers
			header('MIME-Version: 1.0');
			header('Content-Disposition: attachment; filename="' . $basename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');

			switch ($extension)
			{
				case 'zip':
					// ZIP MIME type
					header('Content-Type: application/zip');
					break;

				default:
					// Generic binary data MIME type
					header('Content-Type: application/octet-stream');
					break;
			}

			// Notify of filesize, if this info is available
			if ($filesize > 0)
				header('Content-Length: ' . @filesize($filename));
			// Disable caching
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");
			header('Pragma: no-cache');

			flush();

			if ($filesize > 0)
			{
				// If the filesize is reported, use 1M chunks for echoing the data to the browser
				$blocksize	 = 1048756; //1M chunks
				$handle		 = @fopen($filename, "r");
				// Now we need to loop through the file and echo out chunks of file data
				if ($handle !== false)
					while (!@feof($handle))
					{
						echo @fread($handle, $blocksize);
						@ob_flush();
						flush();
					}

				if ($handle !== false)
				{
					@fclose($handle);
				}
			}
			else
			{
				// If the filesize is not reported, hope that readfile works
				@readfile($filename);
			}

			exit(0);
	}
 
 }	
	

?>