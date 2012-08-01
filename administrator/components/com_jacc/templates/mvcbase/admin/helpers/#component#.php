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

class ##Component##Helper
{
	/**
	 * @var    JObject  A cache for the available actions.
	 * @since  1.6
	 */
	protected static $actions;

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName = '##defaultview##')
	{
##menuhelper##
	}

	/**
	* Gets a list of the actions that can be performed.
	* @param	text	The name.
	* @param	int		The ID.
	*
	* @return	JObject
	* @since	1.6
	*/
	public static function getActions($name = '', $id = 0)
	{
		jimport('joomla.access.access');
		$user	= JFactory::getUser();
		$result	= new JObject;

		// You can find the reasson for the foollowing wired behavor in
		// administrator/components/com_categories/model/category.php
		// in the function preprocessForm(...)
		// ...
		// $name = 'category' . ($section ? ('.' . $section) : '');
		// ...
		// $form->setFieldAttribute('rules', 'component', $component);
		// $form->setFieldAttribute('rules', 'section', $name);
		//
		// Access control for one note:
		// the $assetName for an 'com_user.note.' . $id
		// but $rulesComponent as 'com_users' and $rulesSection as 'note'
		//
		// Access control for one category of notes:
		// $assetName is definend as 'com_users.notes.category.' . $id
		// but $rulesComponent as 'com_users' and $rulesSection as 'category.notes'
		//
		$parts = null;
		$name = strtolower(preg_replace('#[\s\-]+#', '.', trim($name)));
		if (!empty($name))
		{
			$parts = explode('.', $name);
		}

		if ($parts[0] == 'com_component')
		{
			array_shift($parts);
		}

		$c = count($parts);
		if ($c > 0 && is_numeric(end($parts)))
		{
			$c--;
			if (empty($id))
			{
				$id = array_pop($parts);
			}
			else
			{
				array_pop($parts);
			}
		}

		if (2 >= $c && 1 <= $c)
		{
			$assetName = '##com_component##' . '.' . implode('.', $parts) . (empty($id) ? '' : '.' . (int) $id);
			$rulesSection = implode('.', array_reverse($parts));
		}
		else
		{
			$assetName = '##com_component##' . (empty($id) ? '' : '.' . (int) $id);
			$rulesSection = 'component';
		}

		$actions = JAccess::getActions('##com_component##', $rulesSection);

		foreach ($actions as $action) {
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
##componenthelper##
}