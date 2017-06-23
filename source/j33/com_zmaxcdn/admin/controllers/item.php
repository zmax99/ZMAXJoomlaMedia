<?php
/**
 *	description:ZMAX媒体管理组件 资料控制器
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


jimport('joomla.application.component.controllerform');	
class zmaxcdnControllerItem extends JControllerForm
 { 
	public function loadItem()
	{
		$app = JFactory::getApplication();
		$id = $app->input->get('id',"",'INT');
		if($id=="")
		{
			return null;
		}
		
		$item = zmaxcdnItemHelper::getItemById($id);
		if($item)
		{
			//$item->url  = zmaxcdnItemHelper::getItemUrl($item);
			$item->url  = zmaxcdnItemHelper::getItemValue($item);
		}
		$item = $this->formatItemForAjax($item);
		echo json_encode($item);
		$app->close();
	}
	
	public function formatItemForAjax($item)
	{
		$item->thumb=zmaxcdnItemHelper::getItemPreview($item);
		$item->date = JHtml::_("date",$item->create_date);
		$item->size = zmaxcdnCommonHelper::formatFileSize($item->size);
		$item->dim = "99*109";
		$item->pubUrl = JUri::root().$item->url;
		$item->caption=$item->name;
		$item->alt="";
		return $item;
	}
	
	public function ajaxUpdate()
	{
		$app = JFactory::getApplication();
		$id = $app->input->get("id");
		$name = $app->input->get("name");
		$description = $app->input->get("description");
		$item = zmaxcdnItemHelper::getItemById($id);
		$item->name = $name;
		$item->description = $description;
		$db = JFactory::getDBO();
		$db->updateObject("#__zmaxcdn_item",$item,"id");
		echo "更新信息成功!";
		$app->close();
	}
	
	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   1.6
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('item', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_zmaxcdn&view=items' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
 }	
	

?>