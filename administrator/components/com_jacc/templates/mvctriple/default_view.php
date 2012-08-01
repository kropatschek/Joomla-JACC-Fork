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

defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class ##Component##View##Name## extends JView
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->script	= $this->get('Script');
		##ifdefFieldparent_idStart##
		$this->canDo = ##Component##Helper::getActions('##nameplural####extra##.##name##', $this->state->get('filter.##name##_id'));
		##ifdefFieldparent_idEnd##
		##ifnotdefFieldparent_idStart##
		##ifdefFieldcatidStart##
		if (empty($this->item->id))
		{
			$this->canDo = ##Component##Helper::getActions('##nameplural####extra##.category', $this->state->get('filter.category_id'));
		}
		else
		{
		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		if (empty($this->item->id))
		{
			$this->canDo = ##Component##Helper::getActions('##nameplural####extra##.category', $this->state->get('filter.category_id'));
		}
		else
		{
		##ifdefFieldcategory_idEnd##
		##ifdefFieldcatidStart##	##ifdefFieldcatidEnd####ifdefFieldcategory_idStart##	##ifdefFieldcategory_idEnd##
		$this->canDo = ##Component##Helper::getActions('##name##', $this->item->id);
		##ifdefFieldcatidStart##}##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##}##ifdefFieldcategory_idEnd##
		##ifnotdefFieldparent_idEnd##

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolbar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$canDo		= $this->canDo;
		##ifdefFieldchecked_outStart##
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		##ifdefFieldchecked_outEnd##

		JToolBarHelper::title($isNew ? JText::_('COM_##COMPONENT##_##NAME##_MANAGER_NEW') : JText::_('COM_##COMPONENT##_##NAME##_MANAGER_EDIT'), 'generic.png');

		// Built the actions for new and existing records.

		// For new records, check the create permission.
		##ifnotdefFieldparent_idStart##
		if ($isNew##ifdefFieldcatidStart## && (count($user->getAuthorisedCategories('##com_component##.##nameplural####extra##', 'core.create')) > 0)##ifdefFieldcatidEnd####ifdefFieldcategory_idStart## && (count($user->getAuthorisedCategories('##com_component##.##nameplural####extra##', 'core.create')) > 0)##ifdefFieldcategory_idEnd##) {
		##ifnotdefFieldparent_idEnd##
		##ifdefFieldparent_idStart##
		if ($isNew && (count(##Component##Helper::getAuthorised##Nameplural####extra##('##com_component##.##nameplural####extra##', 'core.create')) > 0)) {
		##ifdefFieldparent_idEnd##
			JToolBarHelper::apply('##name##.apply');
			JToolBarHelper::save('##name##.save');
			JToolBarHelper::save2new('##name##.save2new');
			JToolBarHelper::cancel('##name##.cancel');
		}
		else
		{
			##ifdefFieldchecked_outStart##
			// Can't save the record if it's checked out.
			if (!$checkedOut) {
			##ifdefFieldchecked_outEnd##
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				##ifdefFieldcatidStart##
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own')##ifdefFieldcreated_byStart## && $this->item->created_by == $userId##ifdefFieldcreated_byEnd####ifdefFieldcreated_user_idStart## && $this->item->created_user_id == $userId##ifdefFieldcreated_user_idEnd##)) {
				##ifdefFieldcatidEnd##
				##ifdefFieldcategory_idStart##
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own')##ifdefFieldcreated_byStart## && $this->item->created_by == $userId##ifdefFieldcreated_byEnd####ifdefFieldcreated_user_idStart## && $this->item->created_user_id == $userId##ifdefFieldcreated_user_idEnd##)) {
				##ifdefFieldcategory_idEnd##
					JToolBarHelper::apply('##name##.apply');
					JToolBarHelper::save('##name##.save');

					##ifdefFieldcatidStart##
					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolBarHelper::save2new('##name##.save2new');
					}
					##ifdefFieldcatidEnd##
					##ifdefFieldcategory_idStart##
					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolBarHelper::save2new('##name##.save2new');
					}
					##ifdefFieldcategory_idEnd##
				##ifdefFieldcatidStart##
				}
				##ifdefFieldcatidEnd##
				##ifdefFieldcategory_idStart##
				}
				##ifdefFieldcategory_idEnd##
			##ifdefFieldchecked_outStart##
			}
			##ifdefFieldchecked_outEnd##

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolBarHelper::save2copy('##name##.save2copy');
			}

			JToolBarHelper::cancel('##name##.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_##COMPONENT##_##NAME##_MANAGER_EDIT');
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$isNew = ($this->item->id == 0);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_##COMPONENT##_##NAME##_MANAGER_NEW') : JText::_('COM_##COMPONENT##_##NAME##_MANAGER_EDIT'));
		//$script = array();
		//$script[] = '	';
		//JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		//$document->addScript(JURI::root(true) . '/media/com_units/js/jquery-1.7.2.min.js');
		$document->addScript(JURI::root(true) . $this->script);
		$document->addScript(JURI::root(true) . '/media/##com_component##/js/##name##/submitbutton.js');
		//$document->addScript(JURI::root(true) . '/media/##com_component##/js/jquery-1.7.2.min.js');
		//JText::script('COM_##COMPONENT##_##NAME##_ERROR_UNACCEPTABLE');
	}
}
##codeend##
