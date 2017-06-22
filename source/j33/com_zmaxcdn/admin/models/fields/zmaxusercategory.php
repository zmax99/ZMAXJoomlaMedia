<?php
/**
 *	description:ZMAX媒体管理 zmaxusercategory字段
 *  author：min.zhang
 *  Email:zhang19min88@163.com
 *	Url:http://www.zmax99.com
 *  copyright:南宁市程序人软件科技有限责任公司保留所有权利
 *  date:2016-05-20
 *  @license GNU General Public License version 3, or later
 *  check date:2016-05-20
 *  checker :min.zhang
 */
 
 
defined('_JEXEC') or die('you can not access this file!');
JFormHelper::loadFieldClass('list');

class JFormFieldZmaxusercategory extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Zmaxusercategory';

	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @return  array    The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$options = array();
		$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $this->element['scope'];
		$published = (string) $this->element['published'];
		$language  = (string) $this->element['language'];

		// Load the category options for a given extension.
		if (!empty($extension))
		{
			// Filter over published state or not depending upon if it is present.
			$filters = array();
			if ($published)
			{
				$filters['filter.published'] = explode(',', $published);
			}

			// Filter over language depending upon if it is present.
			if ($language)
			{
				$filters['filter.language'] = explode(',', $language);
			}

			if ($filters === array())
			{
				$options = JHtml::_('category.options', $extension);
			}
			else
			{
				$options = JHtml::_('category.options', $extension, $filters);
			}

		
			// Displays language code if not set to All
			foreach ($options as $option)
			{
				
				// Create a new query object.
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select($db->quoteName('language'))
					->where($db->quoteName('id') . '=' . (int) $option->value)
					->from($db->quoteName('#__categories'));
					
					

				$db->setQuery($query);
				$language = $db->loadResult();

				if ($language !== '*')
				{
					$option->text = $option->text . ' (' . $language . ')';
				}
			}
			
			// Verify permissions.  If the action attribute is set, then we scan the options.
			if ((string) $this->element['action'])
			{
				// Get the current user object.
				$user = JFactory::getUser();

				foreach ($options as $i => $option)
				{
					/*
					 * To take save or create in a category you need to have create rights for that category
					 * unless the item is already in that category.
					 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
					 */
					if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
					{
						unset($options[$i]);
					}
					
				}
			}
			
			$myCates = $this->getMyCates();
			foreach ($options as $i => $option)
			{
				if( !in_array($option->value,$myCates) )
				{
					unset($options[$i]);
				}
			}
			
			if (isset($this->element['show_root']))
			{
				array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
			}
		}
		else
		{
			JLog::add(JText::_('JLIB_FORM_ERROR_FIELDS_CATEGORY_ERROR_EXTENSION_EMPTY'), JLog::WARNING, 'jerror');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
	
	public function getMyCates()
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id")->from("#__categories");
		$query->where($db->quoteName('created_user_id') . '=' . (int) $user->id);
		$db->setQuery($query);
		$items = $db->loadColumn();
		return $items;
	}
}