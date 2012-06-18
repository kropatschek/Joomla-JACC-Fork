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

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * ##Nameplural####extra## controller class.
 *
##ifdefVarpackageStart##
 * @package    ##package## Administrator
 * @subpackage ##com_component##
##ifdefVarpackageEnd##
##ifnotdefVarpackageStart##
 * @package    ##com_component## Administrator
##ifnotdefVarpackageEnd##
 */
class ##Component##Controller##Nameplural####extra## extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $text_prefix = '##COM_COMPONENT##_##NAMEPLURAL####EXTRA##';

	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.
	 *
	 * @return	ContactControllerContacts
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		##ifdefFieldfeaturedStart##

		$this->registerTask('unfeatured',	'featured');
		##ifdefFieldfeaturedEnd##
	}

	/**
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.5
	 */
	public function getModel($name = '##Name##', $prefix = '##Component##Model', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	##ifdefFieldparent_idStart##

	/**
	 * Rebuild the nested set tree.
	 *
	 * @return	bool	False on failure or error, true on success.
	 * @since	1.6
	 */
	public function rebuild()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		##ifdefFieldextensionStart##
		//$extension = JRequest::getCmd('extension');
		//$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories&extension='.$extension, false));
		##ifdefFieldextensionEnd##

		$this->setRedirect(JRoute::_('index.php?option=##com_component##&view=##nameplural####extra##', false));

		// Initialise variables.
		$model = $this->getModel();

		if ($model->rebuild()) {
			// Rebuild succeeded.
			$this->setMessage(JText::_('##COM_COMPONENT##_REBUILD_SUCCESS'));
			return true;
		} else {
			// Rebuild failed.
			$this->setMessage(JText::_('##COM_COMPONENT##_REBUILD_FAILURE'));
			return false;
		}
	}
	##ifdefFieldparent_idEnd##
	##ifdefFieldfeaturedStart##

	/**
	 * Method to toggle the featured setting of a list of ##nameplural####extra##.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function featured()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$user	= JFactory::getUser();
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('featured' => 1, 'unfeatured' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');
		// Get the model.
		$model = $this->getModel();

		// Access checks.
		foreach ($ids as $i => $id)
		{
			$item = $model->getItem($id);

			##ifdefFieldcatidStart##
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##
			if (!$user->authorise('core.edit.state', '##com_component##.##nameplural####extra##.category.'.(int) $item->category_id)) {
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
			##ifdefFieldcategory_idEnd##
		}

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('##COM_COMPONENT##_NO_ITEM_SELECTED'));
		} else {
			// Publish the items.
			if (!$model->featured($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=##com_component##&view=##nameplural####extra##');
	}
	##ifdefFieldfeaturedEnd##
}
##codeend##
