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
	##ifdefFieldextensionStart##
	/**
	 * The extension for which the categories apply.
	 *
	 * @var    string
	 * @since  1.6
	 */
	//protected $extension;
	##ifdefFieldextensionEnd##

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $text_prefix = '##COM_COMPONENT##_##NAME##';

	##ifdefFieldextensionStart##
	/**
	 * Constructor.
	 *
	 * @param  array  $config  An optional associative array of configuration settings.
	 *
	 * @since  1.6
	 * @see    JController
	 */
	//public function __construct($config = array())
	//{
		//parent::__construct($config);

		//// Guess the JText message prefix. Defaults to the option.
		//if (empty($this->extension))
		//{
			//$this->extension = JRequest::getCmd('extension', 'com_content');
		//}
	//}

	##ifdefFieldextensionEnd##

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
		##ifnotdefFieldparent_idStart##

		##ifdefFieldcatidStart##
		$categoryId = JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', '##com_component##.##nameplural####extra##.category.' . $categoryId);
		}

		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		$categoryId = JArrayHelper::getValue($data, 'category_id', JRequest::getInt('filter_category_id'), 'int');
		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', '##com_component##.##nameplural####extra##.category.' . $categoryId);
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
		##ifnotdefFieldparent_idEnd##
		##ifdefFieldparent_idStart##
		##ifdefFieldextensionStart##
		//$allow = ($user->authorise('core.create', $this->extension) || count($user->getAuthorisedCategories($this->extension, 'core.create')));
		##ifdefFieldextensionEnd##
		$allow = ($user->authorise('core.create', '##com_component##') || count($this->getAuthorised##Nameplural####extra##('core.create')));
		return $allow;
		##ifdefFieldparent_idEnd##
	}

	##ifdefFieldparent_idStart##
	##ifdefFieldasset_idStart##
	/**
	 * Method to return a list of all categories that a user has permission for a given action
	 *
	 * @param   string  $action     The name of the section within the component from which to retrieve the actions.
	 *
	 * @return  array  List of categories that this group can do this action to (empty array if none). Categories must be published.
	 *
	 * @since   11.1
	 */
	public function getAuthorised##Nameplural####extra##($action)
	{
		// Brute force method: get all published ##name## rows for the component and check each one
		// TODO: Modify the way permissions are stored in the db to allow for faster implementation and better scaling
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('b.id AS id, a.name AS asset_name')->from('##table## AS b')
			->innerJoin('#__assets AS a ON b.asset_id = a.id')##ifdefFieldpublishedStart##->where('b.published = 1');##ifdefFieldpublishedEnd####ifdefFieldstateStart##->where('b.state = 1');##ifdefFieldstateEnd##
		$db->setQuery($query);
		$all##Nameplural####extra## = $db->loadObjectList('id');
		$allowed##Nameplural####extra## = array();
		foreach ($all##Nameplural####extra## as $##name##)
		{
			if ($this->authorise($action, $##name##->asset_name))
			{
				$allowed##Nameplural####extra##[] = (int) $##name##->id;
			}
		}
		return $allowed##Nameplural####extra##;
	}

	##ifdefFieldasset_idEnd##
	##ifdefFieldparent_idEnd##
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
	protected function allowEdit($data = array(), $key = '##ifdefFieldparent_idStart##parent_##ifdefFieldparent_idEnd##id')
	{
		// Initialise variables.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		// Check general edit permission first.
		##ifdefFieldextensionStart##
		//if ($user->authorise('core.edit', $this->extension))
		##ifdefFieldextensionEnd##
		if ($user->authorise('core.edit', '##com_component##'))
		{
			return true;
		}

		// Check specific edit permission.
		##ifdefFieldextensionStart##
		//if ($user->authorise('core.edit', $this->extension . '.category.' . $recordId))
		##ifdefFieldextensionEnd##
		if ($user->authorise('core.edit', '##com_component####ifdefFieldparent_idStart##.##nameplural####extra####ifdefFieldparent_idEnd##.##name##.' . $recordId))
		{
			return true;
		}

		##ifdefFieldcreated_byStart##
		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', '##com_component####ifdefFieldparent_idStart##.##nameplural####extra####ifdefFieldparent_idEnd##.##name##.' . $recordId))
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

		##ifdefFieldcreated_byEnd##
		##ifdefFieldcreated_user_idStart##
		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', '##com_component####ifdefFieldparent_idStart##.##nameplural####extra####ifdefFieldparent_idEnd##.##name##.' . $recordId))
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

		##ifdefFieldcreated_user_idEnd##
		##ifdefFieldparent_idStart##
		return false;
		##ifdefFieldparent_idEnd##
		##ifnotdefFieldparent_idStart##
		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
		##ifnotdefFieldparent_idEnd##
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean	 True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('##Name##', '', array());

		// Preset the redirect
		##ifdefFieldextensionStart##
		//$this->setRedirect('index.php?option=##com_component##&view=##nameplural####extra##);// TODO Fix: &extension=' . $this->extension);
		##ifdefFieldextensionEnd##
		$this->setRedirect(JRoute::_('index.php?option=##com_component##&view=##nameplural####extra##' . $this->getRedirectToListAppend(), false));
		return parent::batch($model);
	}

	##ifdefFieldextensionStart##
	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.6
	 */
	//protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	//{
		//$append = parent::getRedirectToItemAppend($recordId);
		//$append .= '&extension=' . $this->extension;
		//
		//return $append;
	//}

	/**
	 * Gets the URL arguments to append to a list redirect.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.6
	 */
	//protected function getRedirectToListAppend()
	//{
		//$append = parent::getRedirectToListAppend();
		//$append .= '&extension=' . $this->extension;
		//
		//return $append;
	//}
	##ifdefFieldextensionEnd##
}
##codeend##