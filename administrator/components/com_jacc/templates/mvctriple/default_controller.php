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

jimport('joomla.application.component.controllerform');

/**
 * ##Component## ##Name## controller class.
 *
 * @package    ##package## Administrator
 * @subpackage ##com_component##
##ifdefVarpackageEnd##
##ifnotdefVarpackageStart##
 * @package    ##com_component## Administrator
##ifnotdefVarpackageEnd##
 */
class ##Component##Controller##Name## extends JControllerForm
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $text_prefix = '##COMPONENT##_##NAME##';

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user = JFactory::getUser();
		$allow = null;

		##ifdefFieldcatidStart##
		$categoryId = JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', '##com_component##.##name##.category.' . $categoryId);
		}
		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		$categoryId = JArrayHelper::getValue($data, 'category_id', JRequest::getInt('filter_category_id'), 'int');
		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', '##com_component##.##name##.category.' . $categoryId);
		}
		##ifdefFieldcategory_idEnd##

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		// Check general edit permission first.
		if ($user->authorise('core.edit', '##com_component##.##name##.' . $recordId))
		{
			return true;
		}

		##ifdefFieldcreated_byStart##
		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', '##com_component##.##name##.' . $recordId))
		{
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId)
			{
				return true;
			}
		}
		##ifdefFieldcreated_byStart##
		##ifdefFieldcreated_user_idStart##
		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', '##com_component##.##name##.' . $recordId))
		{
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_user_id']) ? $data['created_user_id'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_user_id;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId)
			{
				return true;
			}
		}
		##ifdefFieldcreated_user_id##

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}
}
##codeend##