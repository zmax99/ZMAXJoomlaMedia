<?php
/**
 *	description:ZMAX媒体管理 zmaxfield字段
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2015-11-03
 *  重写日期： 2015-12-25
 *  重写原因：优化字段的显示效果
 *  @license GNU General Public License version 3, or later
 *  check date:2016-05-19
 *  checker :min.zhang
 
  *  重写日期： 2017-02-15
 *   重写原因：#854
 *   @license GNU General Public License version 3, or later
 */
 
 
defined('_JEXEC') or die('You Can Not Access This File!');
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/config.php");
require_once(JPATH_ROOT."/administrator/components/com_zmaxcdn/libs/zmaxcdn/item.php");
jimport( 'joomla.utilities.arrayhelper' );

class JFormFieldZmaxfile extends JFormField
{
	  protected $type="zmaxfile";
	 
	  //设置当前字段的参数
	  protected function setFieldParams()
	  {
		$setting = (array)$this->element;  
		$fieldCfg = $setting["@attributes"]; //得到该元素的所有配置

		$configId=""; //默认的配置ID 为空
		if(isset($fieldCfg["config_id"]) && $fieldCfg["config_id"])
		{
			$configId = $fieldCfg["config_id"];
		}
		
		//通过id来装载配置
		$cfg = zmaxcdnConfigHelper::loadConfig($configId); //通过配置ID装载配置
		if(!$cfg)
		{
			$cfg = zmaxcdnConfigHelper::loadDefaultConfig(); //加载默认的配置
		}
		
		$cfgArray = JArrayHelper::fromObject($cfg);
		
		foreach($cfgArray as $key=>$value)  //xml的配置优先
		{
			if(isset($fieldCfg[$key]) && $fieldCfg[$key]!="")
			{
				$cfgArray[$key] = $fieldCfg[$key];
			}
		}
		
		$config = JArrayHelper::toObject($cfgArray);
		
		
		
		//使用session来传递设置信息
		$session = JFactory::getSession();
		$cfgName = "zmaxcdn.caller.config_".$this->id;
		$cfgValue = json_encode($config);
		
		$session->set($cfgName,$cfgValue);
		
		
		
		//有可能存在session过时的，而无法获得信息的情况，后背方案使用cookie
		$inputCookie = JFactory::getApplication()->input->cookie;
		$inputCookie->set($cfgName,$cfgValue);//浏览器的回话时间
		
	  }
	  
	  protected function getInput()
	  {
		//设置字段参数 
		$this->setFieldParams();
		
		JHtml::_('jquery.framework');
		JHtml::_('behavior.modal' ,'a.modal');
		JFactory::getDocument()->addScript(JUri::root()."administrator/components/com_zmaxcdn/models/fields/tip.js");	
		JFactory::getDocument()->addStyleSheet(JUri::root()."administrator/components/com_zmaxcdn/models/fields/tip.css");	
		
		// STEP 1 ://加载语言文件
		JFactory::getLanguage()->load('com_zmaxcdn',JPATH_ADMINISTRATOR);
		
		// STEP 2 ：//获得当前的值
		$value = $this->value > "" ?  $this->value : '';
		
		
		
		//STEP 3 ://构造JS代码
		$script = array();
		$script[] = ' SITE_URL="'.JUri::root().'"';//全局JS变量
		
		// STEP 3-1 ://点击选择按钮后响应的事件
		$script[] = ' function SelectItem_'.$this->id.'(id ){';
		$script[] = '		jQuery.ajax({
									type:"post",
									dataType:"json",
									url:"index.php?option=com_zmaxcdn&task=item.loadItem",
									data:{
											id:id
										},
									cache:false,
									success:function(data){
										document.getElementById("'.$this->id.'_id").value = data.url; 
										document.getElementById("'.$this->id.'_name").value = data.name;
										document.getElementById("'.$this->id.'_title").value = data.name;
										document.getElementById("'.$this->id.'_preview").value = data.url;
									}
							});';
							
		//STEP 3-2 ://清除按钮响应的事件
		$script[] = '			jQuery("#'.$this->id.'_clear").removeClass("hidden");';
		$script[] = '		    jQuery("#itemSelect' . $this->id . 'Modal").modal("hide");';
		
		//STEP 3-3 "//校验
		if ($this->required)
		{
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_id"));';
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_name"));';
		}
		
		$script[] = '}';
		
		
		//清除按钮的代码
		static $scriptClear;
		if(!$scriptClear)
		{
			$scriptClear = true;
			
			$script[] = '	function jClearItem(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "' .
				htmlspecialchars(JText::_('选择一个项目', true), ENT_COMPAT, 'UTF-8') . '";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
			
		}
		
		// STEP 4 ：//将脚本引入到文档
		JFactory::getDocument()->addScriptDeclaration(implode("\n" ,$script));
		
		// STEP 5: //设置显示的变量
		$html = array();
		$linkItems = 'index.php?option=com_zmaxcdn&amp;view=items&amp;caller=field&amp;layout=field&amp;tmpl=component'
			. '&amp;function=SelectItem_' . $this->id. '&amp;id=' . $this->id;

		$urlSelect = $linkItems . '&amp;' . JSession::getFormToken() . '=1';
		$preview="";
		if ($value)
		{
			$item = zmaxcdnItemHelper::getItemByUrl($value); //获得正真的项目
			if($item)
			{
				$title=$item->name;
				$preview=$value; //目前就让预览图和本图一样	
			}
		}

		if (empty($title))
		{
			$title = JText::_('请选择一个项目');
		}
		
		$title = htmlspecialchars($title ,ENT_QUOTES ,'UTF-8');
		
		
		//显示字段
		
		$html[] = '<div class="input-prepend">';
		$html[] = '<span class="media-preview add-on" >';
		$html[] = '		<span title="" data="'.$this->id.'" class="zmax-hasTipPreview"><span class="icon-eye"></span></span>';
		$html[] = '</span>';
		$html[] = '<input class=" zmax-filed-input input-medium" id="' . $this->id . '_name" type="text" value="' . $title . '" disabled="disabled" size="35" />';
		
		//选择产品按钮
		$html[] = '<a'
			. ' class="btn hasTooltip"'
			. ' data-toggle="modal"'
			. ' role="button"'
			. ' href="#itemSelect' . $this->id . 'Modal"'
			. ' title="' . JHtml::tooltipText('请选择一个项目') . '">'
			. '<span class="icon-file"></span> ' . JText::_('选择')
			. '</a>';
		//清除按钮
		$html[] = '<button'
				. ' class="btn' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_clear"'
				. ' onclick="return jClearItem(\'' . $this->id . '\')">'
				. '<span class="icon-remove"></span>' . JText::_('清除')
				. '</button>';		
		$html[] = '</div>';
		
		
				
		// 选择产品的模态对话框	
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			'itemSelect' . $this->id . 'Modal',
			array(
				'title'       => JText::_('请选择一个项目'),
				'url'         => $urlSelect,
				'height'      => '400px',
				'width'       => '800px',
				'bodyHeight'  => '70',
				'modalWidth'  => '80',
				'footer'      => '<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
						. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>',
			)
		);
		
		//客户端校验的
		$class = $this->required ? ' class="required modal-value"' : '';
		$html[]= '<input type="hidden" id="'.$this->id.'_title" name="'.$this->name .'_title" value="'.$title.'" readonly="readonly" />';
		$html[]= '<input type="hidden" id="'.$this->id.'_preview" name="'.$this->name .'_preview" value="'.$preview.'" readonly="readonly" />';
		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n" ,$html);
	  }
	  
	   protected function getLabel()
	 {
		return str_replace($this->id, $this->id . '_id', parent::getLabel());
	 }
	  
}