<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 */
abstract class JHtml##Component##
{
	/**
	 * @param	int $value	The featured value
	 * @param	int $i
	 * @param	bool $canChange Whether the value can be changed or not
	 *
	 * @return	string	The anchor tag to toggle featured/unfeatured contacts.
	 * @since	1.6
	 */
	static function featured($value = 0, $i, $canChange = true, $prefix = '')
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png', $prefix.'featured', '##COM_COMPONENT##_UNFEATURED', '##COM_COMPONENT##_TOGGLE_TO_FEATURE'),
			1	=> array('featured.png', $prefix.'unfeatured', 'JFEATURED', '##COM_COMPONENT##_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), NULL, true);
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html .'</a>';
		}

		return $html;
	}
}
