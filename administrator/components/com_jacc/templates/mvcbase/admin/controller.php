<?php
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

// TODO nicht notwendig
//jimport('joomla.application.component.controller');

/**
 * Component Controller
 *
##ifdefVarpackageStart##*
 * @package    ##package## Administrator
 * @subpackage ##com_component##
##ifdefVarpackageEnd##
##ifnotdefVarpackageStart##
 * @package    ##com_component## Administrator
##ifnotdefVarpackageEnd##
 */
class ##Component##Controller extends JController
{
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = '##defaultview##';

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		//TODO nur Temp für test nachher löschen
		require_once JPATH_COMPONENT.'/helpers/##component##.php';

		// Load the submenu.
		##Component##Helper::addSubmenu(JRequest::getCmd('view', '##defaultview##'));

		$view		= JRequest::getCmd('view', '##defaultview##');
		$layout 	= JRequest::getCmd('layout', 'default');
		$id			= JRequest::getInt('id');

		// Check for edit form.
		if ($view == '##defaultviewsingular##' && $layout == 'edit' && !$this->checkEditId('##com_component##.edit.##defaultviewsingular##', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=##com_component##&view=##defaultview##', false));

			return false;
		}

		//parent::display();

		//return $this;
		return parent::display();
	}
}

?>