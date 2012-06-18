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
	* @param	text	The section name.
	* @param	int		The category ID.
	* @param	int		The section ID.
	*
	* @return	JObject
	* @since	1.6
	*/
	public static function getActions($section = '##defaultviewsingular##', $categoryId = 0, $sectionId = 0)
	{
		jimport('joomla.access.access');
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($sectionId) && empty($categoryId)) {
			$assetName = '##com_component##';
		}
		elseif (empty($sectionId)) {
			$assetName = '##com_component##.'.$section.'.category.'.(int) $categoryId;
		}
		else {
			$assetName = '##com_component##.'.$section.'.'.(int) $sectionId;
		}

		$actions = JAccess::getActions('##com_component##', 'component');

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}

}