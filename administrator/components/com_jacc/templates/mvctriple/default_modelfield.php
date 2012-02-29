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

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class.
 */
class JFormField##Component####Name## extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = '##Component####Name##';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('##primary## AS value, ##hident## AS text');
		$query->from('##table##');
		$query->order('##hident## DESC');

		// Get the options.
		$db->setQuery($query->__toString());

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		// Merge any additional options in the XML definition.
		//$options = array_merge(parent::getOptions(), $options);

		// TODO: maybe to delete
		//array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_BANNERS_NO_CLIENT')));

		return $options;
	}
}
##codeend##