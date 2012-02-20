##codestart##

defined('JPATH_BASE') or die;

//TODO: to delete
//jimport('joomla.html.html');

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

//TODO: to delete
//require_once (JPATH_ADMINISTRATOR.'/components/com_##component##/helpers/query.php' );

/**
 * Form Field class.
 */
class JFormField##Component####name## extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = '##Component####name##';

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