<?php defined('_JEXEC') or die; ?>
##codestart##
/**
##ifdefVarpackageStart##
 * @package    ##package## Administrator
 * @subpackage ##com_component##
##ifdefVarpackageEnd##
##ifnotdefVarpackageStart##
 * @package    ##com_component## Administrator
##ifnotdefVarpackageEnd##
 * @version    ##version##
 * @copyright  Copyright (C) ##year##, ##author##. All rights reserved.
 * @license    ##license##
*/

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @package     Joomla.Platform
 * @subpackage  ##com_component##
 * @since       11.1
 */
class JFormField##Name##Edit extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = '##Name##Edit';


	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @return  array    The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options = array();
		##ifdefFieldextensionStart##
		//$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $this->element['scope'];
		##ifdefFieldextensionEnd##
		$published = (string) $this->element['published'];
		$name = (string) $this->element['name'];

		##ifdefFieldextensionStart##
		// Load the category options for a given extension.
		//if (!empty($extension))
		//{
		##ifdefFieldextensionEnd##
		// Filter over published state or not depending upon if it is present.
		if ($published)
		{
			//$options = JHtml::_('##name##.options'##ifdefFieldextensionStart##, $extension##ifdefFieldextensionEnd##, array('filter.published' => explode(',', $published)));
			$options = JHtml::_('##name##.options', array('filter.published' => explode(',', $published)));

		}
		else
		{
			//$options = JHtml::_('##name##.options'##ifdefFieldextensionStart##, $extension ##ifdefFieldextensionEnd##);
			$options = JHtml::_('##name##.options');
		}

		// Verify permissions.  If the action attribute is set, then we scan the options.
		if ((string) $this->element['action'])
		{

			// Get the current user object.
			$user = JFactory::getUser();

			// For new items we want a list of categories you are allowed to create in.
			if (!$this->form->getValue($name))
			{
				foreach ($options as $i => $option) {
					// To take save or create in a category you need to have create rights for that category
					// unless the item is already in that category.
					// Unset the option if the user isn't authorised for it. In this field assets are always categories.
					if ($user->authorise('core.create', '##com_component##' . '.##name##.' . $option->value) != true )
					//if ($user->authorise('core.create', ##ifdefFieldextensionStart##$extension##ifdefFieldextensionEnd####ifnotdefFieldextensionStart##'##com_component##'##ifnotdefFieldextensionEnd## . '.##name##.' . $option->value) != true )
					//if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true )
					{
						unset($options[$i]);
					}
				}
			}
			// If you have an existing category id things are more complex.
			else
			{
				$categoryOld = $this->form->getValue($name);
				foreach ($options as $i => $option)
				{
					// If you are only allowed to edit in this category but not edit.state, you should not get any
					// option to change the category.
					if ($user->authorise('core.edit.state', '##com_component##' . '.##name##.' . $categoryOld) != true )
					//if ($user->authorise('core.edit.state', ##ifdefFieldextensionStart##$extension##ifdefFieldextensionEnd####ifnotdefFieldextensionStart##'##com_component##'##ifnotdefFieldextensionEnd## . '.##name##.' . $categoryOld) != true )
					//if ($user->authorise('core.edit.state', $extension . '.category.' . $categoryOld) != true)
					{
						if ($option->value != $categoryOld)
						{
							unset($options[$i]);
						}
					}
					// However, if you can edit.state you can also move this to another category for which you have
					// create permission and you should also still be able to save in the current category.
					elseif
						(($user->authorise('core.create', '##com_component##' . '.##name##.' . $option->value) != true )
						//(($user->authorise('core.create', ##ifdefFieldextensionStart##$extension##ifdefFieldextensionEnd####ifnotdefFieldextensionStart##'##com_component##'##ifnotdefFieldextensionEnd## . '.##name##.' . $option->value) != true )
						//(($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
						&& $option->value != $categoryOld)
					{
						unset($options[$i]);
					}
				}
			}
		}

		if (isset($this->element['show_root']))
		{
			array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
		}
		##ifdefFieldextensionStart##
		//}
		//else
		//{
		//	JError::raiseWarning(500, JText::_('JLIB_FORM_ERROR_FIELDS_CATEGORY_ERROR_EXTENSION_EMPTY'));
		//}
		##ifdefFieldextensionEnd##

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
##codeend##

