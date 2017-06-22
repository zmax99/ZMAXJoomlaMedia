<?php
/**
 *	description:ZMAX媒体管理组件 安装脚本
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2014-11-22
 * @license GNU General Public License version 3, or later
 *  check date:2016-05-20
 *  checker :min.zhang
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');	
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

/**
 *	Script file of captcha component
 **/
 class com_zmaxcdnInstallerScript
 {
	/**
	 *	method to install the component
	 *  @return void
	 */
	 function install($parent)
	 {
		// $parent is the class calling this method
		$this->installExtension();
		$this->installDemo();
	 }
	 
	 /**
	  *	method to uninstall the component
	  *	@return void
	  **/
	  function uninstall($parent)
	  {
		// $parent is the calss calling this method
	  }
	  
	  /**
	   *	method to update the component
	   *	@return void
	   **/
	  function update($parent)
	  {		
		$this->installExtension();
		// $parent is the class calling this method
		//echo "<p>".JText::sprintf('COM_CAPTCHA_UPDATE_TEXT' ,$parent->get('manifest')->version).'</p>';

	  }
	  
	  /**
	   *	method to runbefore on install/update/unistall method
	   *	@return void
	   **/
	  function preflight($type ,$parent)
	  {
		// $parent is the class calling this method
		// $type is the type change (install ,update or discover_install)
		
	  }
	  
	  /**
	   *	method to run after on install/update/uninstall method
	   *	@return void
	   */
	   function postflight($type ,$parent)
	   {
			//$parent is the class calling this method
			//$type is the type of change (install ,update or discover_install)
			//$this->hideAdminMenu();
			
	   }
	   
	   protected function installExtension()
	   {
			jimport('joomla.installer.helper');
			$installer = new JInstaller();
			
			//扩展的安装文件目录
			$pk_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_zmaxcdn'.DS.'extensions'; 
			
			$files = array();
			if(is_dir($pk_path))
			{
				if($hDir = opendir($pk_path))
				{
					while( ($file = readdir($hDir) ) !== false)
					{
						if($file !="." && $file !="..")
						{
							if(is_file($pk_path.DS.$file))
							{
									 $ext = JFile::getExt($file);
									 if($ext=="zip")
									 {
										$files[] = $file;
									}
							}
						}
					}
				}
			}
			
			if(count($files) != 0)
			{
				$installInfos = array();
				foreach($files as $pk_file)
				{
					$info = new stdclass();
					$info->extension_name="name:";
					
					$package = JInstallerHelper::unpack($pk_path.DS.$pk_file);	
					if($installer->install( $package['dir']))
					{
						$info->install_state ="安装成功!";
						$info->extension_state="启用";
						//安装成功
					}
					else
					{
						$info->install_state ="安装失败!";
						$info->extension_state="禁用";
						//安装失败
					}
					
					array_push($installInfos ,$info);
					JInstallerHelper::cleanupInstall($pk_path.DS.$pk_file ,$package['dir']);
					JFolder::delete($package["extractdir"]);
				}
				$msg = $this->formatInstallMsg($installInfos);
				echo $msg;
			}
			//这里稍后在修改了
			//JFolder::delete($pk_path);
			$this->enablePlugin();
			
			
	   }
	   
	   //将安装信息格式化
	   protected function formatInstallMsg($extensions)
	   {
		   $msg= array();
		   $msg[]='<h4>已安装的扩展列表</h4>';
		   $msg[]='<table class="table table-bordered">
						<thead>
							<tr>
								<th>扩展</th>
								<th>描述</th>
								<th>状态</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>ZMAX插入图片插件</td>
								<td>让你快速的上传并且插入资源。支持中文名称。</td>
								<td class="text-success">已启用</td>
							</tr>
							<tr>
								<td >ZMAX插入视频插件</td>
								<td>该插件可以对插入的视频资源进行解析，在前台播放视频文件。</td>
								<td class="text-success">已启用</td>
							</tr>
							<tr>
								<td>ZMAX插入附件插件</td>
								<td>该插件可以对插入的附件资源进行解析，在前台提供下载文件的功能。支持中文名称</td>
								<td><span class="text-success">已启用</td>
							</tr>
						<tbody>
					</table>';
			return  implode("\r\n" ,$msg);
	   }
	   protected function hideAdminMenu()
	   {
			// Do Nothing
	   }
	   
	   protected function installDemo()
	   {
		    // Create categories for our component
			$basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
			require_once $basePath . '/models/category.php';
			$config = array( 'table_path' => $basePath . '/tables');
			$catmodel = new CategoriesModelCategory( $config);
			
			//创建默认分类
			$catData = array( 'id' => 0, 'parent_id' => 1, 'level' => 1, 'path' => 'zmaxcdn-default-category', 'extension' => 'com_zmaxcdn'
			, 'title' => '默认分类', 'alias' => 'zmaxcdn-default-category', 'description' => '<p>ZMAX媒体组件默认资源分类</p>', 'published' => 1, 'language' => '*');
		
			$status = $catmodel->save( $catData);			
			
			if(!$status) 
			{
				JError::raiseWarning(500, JText::_('ZMAX媒体组件创建默认分类失败!'));
			}		   
	   }
	   
	   protected function enablePlugin()
	   {
			//STEP 1自动启用媒体管理按钮插件
			$db = JFactory::getDBO();
			$sql = "UPDATE #__extensions SET enabled = '1' WHERE type = 'plugin' AND name ='plg_editors-xtd_zmaxcdn_insertbtn'";
			$db->setQuery($sql);
			$db->query();
			
			//STEP 2自动启用媒体管理视频解析插件
			
			$sql = "UPDATE #__extensions SET enabled = '1' WHERE type = 'plugin' AND name ='plg_content_zmaxplayvideo'";
			$db->setQuery($sql);
			$db->query();
			
			//STEP 3自动启用媒体管理下载解析插件
			
			
			$sql = "UPDATE #__extensions SET enabled = '1' WHERE type = 'plugin' AND name ='plg_content_zmaxdownload'";
			$db->setQuery($sql);
			$db->query();
	   }
 }
?>