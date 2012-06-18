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
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = ##Component##Helper::getActions('##name##', $this->state->get('filter.category_id'));

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
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
		##ifdefFieldchecked_outStart##
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		##ifdefFieldchecked_outEnd##
		##ifdefFieldcatidStart##
		// Since we don't track these assets at the item level, use the category id.
		$canDo		= ##Component##Helper::getActions('##name##', $this->item->catid,0);
		//$this->canDo	= ##Component##Helper::getActions('##name##', $this->state->get('filter.category_id'));
		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		// Since we don't track these assets at the item level, use the category id.
		$canDo		= ##Component##Helper::getActions('##name##', $this->item->category_id,0);
		//$this->canDo	= ##Component##Helper::getActions('##name##', $this->state->get('filter.category_id'));
		##ifdefFieldcategory_idEnd##

		JToolBarHelper::title($isNew ? JText::_('COM_##COMPONENT##_MANAGER_##NAME##_NEW') : JText::_('COM_##COMPONENT##_MANAGER_##NAME##_EDIT'), 'generic.png');

		// Built the actions for new and existing records.

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('##com_component##', 'core.create')) > 0)) {
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

			##ifdefFieldcatidStart##
			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolBarHelper::save2copy('##name##.save2copy');
			}
			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##
			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolBarHelper::save2copy('##name##.save2copy');
			}
			##ifdefFieldcategory_idEnd##

			JToolBarHelper::cancel('##name##.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_##COMPONENT##_##NAME##_MANAGER_EDIT');
	}
}
##codeend##
