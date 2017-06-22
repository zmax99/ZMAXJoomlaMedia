<?php 
/**
 *	description:ZMAXCDN 字段组视图文件
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-08-07
 * @license GNU General Public License version 3, or later
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class zmaxcdnViewCategory extends JViewLegacy
{

     function display($tpl = null)	 
	 {
		//Get data from the model
		$this->item =  $this->get('Item');
		$this->form =  $this->get('Form');
		
		//display the template
		parent::display($tpl);
	 }
	 
}