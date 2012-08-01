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

/**
 * Utility class for ##nameplural####extra##
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
//abstract class JHtmlCategory
abstract class JHtml##Name##
{
	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since  11.1
	 */
	protected static $items = array();

	/**
	 * Returns an array of ##nameplural####extra####ifdefFieldextensionStart## for the given extension##ifdefFieldextensionEnd##.
	 *
	##ifdefFieldextensionStart##
	 * @param   string  $extension  The extension option e.g. com_something.
	##ifdefFieldextensionEnd##
	 * @param   array   $config     An array of configuration options. By default, only
	 *                              published and unpublished ##nameplural####extra## are returned.
	 *
	 * @return  array
	 *
	 * @since   11.1
	 */
	##ifdefFieldextensionStart##
	//public static function options(##ifdefFieldextensionStart##$extension, ##ifdefFieldextensionEnd##$config = array(##ifdefFieldpublishedStart##'filter.published' => array(0, 1)##ifdefFieldpublishedEnd####ifdefFieldstateStart##'filter.state' => array(0, 1)##ifdefFieldstateEnd##))
	##ifdefFieldextensionEnd##
	public static function options($config = array(##ifdefFieldpublishedStart##'filter.published' => array(0, 1)##ifdefFieldpublishedEnd####ifdefFieldstateStart##'filter.state' => array(0, 1)##ifdefFieldstateEnd##))
	{
		##ifdefFieldextensionStart##
		//$hash = md5(##ifdefFieldextensionStart##$extension . '.' . ##ifdefFieldextensionEnd##serialize($config));
		##ifdefFieldextensionEnd##
		$hash = md5('##com_component##' . '##name##' . '.' . serialize($config));

		if (!isset(self::$items[$hash]))
		{
			$config = (array) $config;
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('a.id, a.##title##, a.level');
			$query->from('##table## AS a');
			$query->where('a.parent_id > 0');

			##ifdefFieldextensionStart##
			// Filter on extension.
			//$query->where('extension = ' . $db->quote($extension));
			##ifdefFieldextensionEnd##

			##ifdefFieldpublishedStart##
			// Filter on the published state
			if (isset($config['filter.published']))
			{
				if (is_numeric($config['filter.published']))
				{
					$query->where('a.published = ' . (int) $config['filter.published']);
				}
				elseif (is_array($config['filter.published']))
				{
					JArrayHelper::toInteger($config['filter.published']);
					$query->where('a.published IN (' . implode(',', $config['filter.published']) . ')');
				}
			}
			##ifdefFieldpublishedEnd##
			##ifdefFieldstateStart##
			// Filter on the published state
			if (isset($config['filter.state']))
			{
				if (is_numeric($config['filter.state']))
				{
					$query->where('a.state = ' . (int) $config['filter.state']);
				}
				elseif (is_array($config['filter.state']))
				{
					JArrayHelper::toInteger($config['filter.state']);
					$query->where('a.state IN (' . implode(',', $config['filter.state']) . ')');
				}
			}
			##ifdefFieldstateEnd##

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();

			// Assemble the list options.
			self::$items[$hash] = array();

			foreach ($items as &$item)
			{
				$repeat = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
				$item->##title## = str_repeat('- ', $repeat) . $item->##title##;
				self::$items[$hash][] = JHtml::_('select.option', $item->id, $item->##title##);
			}
		}

		self::$items[$hash][] = JHtml::_('select.option', '1', JText::_('JGLOBAL_ROOT_PARENT'));

		return self::$items[$hash];
	}

	/**
	 * Returns an array of ##nameplural####extra####ifdefFieldextensionStart## for the given extension##ifdefFieldextensionEnd##.
	 *
	##ifdefFieldextensionStart##
	 * @param   string  $extension  The extension option.
	##ifdefFieldextensionEnd##
	 * @param   array   $config     An array of configuration options. By default, only published and unpublished ##nameplural####extra## are returned.
	 *
	 * @return  array   ##Nameplural####extra####ifdefFieldextensionStart## for the extension##ifdefFieldextensionEnd##
	 *
	 * @since   11.1
	 */
	##ifdefFieldextensionStart##
	//public static function categories(##ifdefFieldextensionStart##$extension, ##ifdefFieldextensionEnd##$config = array(##ifdefFieldpublishedStart##'filter.published' => array(0, 1)##ifdefFieldpublishedEnd####ifdefFieldstateStart##'filter.state' => array(0, 1)##ifdefFieldstateEnd##))
	##ifdefFieldextensionEnd##
	public static function ##nameplural####extra##($config = array(##ifdefFieldpublishedStart##'filter.published' => array(0, 1)##ifdefFieldpublishedEnd####ifdefFieldstateStart##'filter.state' => array(0, 1)##ifdefFieldstateEnd##))
	{
		//$hash = md5(##ifdefFieldextensionStart##$extension . '.' . ##ifdefFieldextensionEnd##serialize($config));
		$hash = md5('##com_component##' . '##name##' . '.' . serialize($config));


		if (!isset(self::$items[$hash]))
		{
			$config = (array) $config;
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('a.id, a.##title##, a.level, a.parent_id');
			$query->from('##table## AS a');
			$query->where('a.parent_id > 0');

			##ifdefFieldextensionStart##
			// Filter on extension.
			//$query->where('extension = ' . $db->quote($extension));

			##ifdefFieldextensionEnd##
			##ifdefFieldpublishedStart##
			// Filter on the published state
			if (isset($config['filter.published']))
			{
				if (is_numeric($config['filter.published']))
				{
					$query->where('a.published = ' . (int) $config['filter.published']);
				}
				elseif (is_array($config['filter.published']))
				{
					JArrayHelper::toInteger($config['filter.published']);
					$query->where('a.published IN (' . implode(',', $config['filter.published']) . ')');
				}
			}
			##ifdefFieldpublishedEnd##
			##ifdefFieldstateStart##
			// Filter on the published state
			if (isset($config['filter.state']))
			{
				if (is_numeric($config['filter.state']))
				{
					$query->where('a.state = ' . (int) $config['filter.state']);
				}
				elseif (is_array($config['filter.state']))
				{
					JArrayHelper::toInteger($config['filter.state']);
					$query->where('a.state IN (' . implode(',', $config['filter.state']) . ')');
				}
			}
			##ifdefFieldstateEnd##

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();

			// Assemble the list options.
			self::$items[$hash] = array();

			foreach ($items as &$item)
			{
				$repeat = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
				$item->##title## = str_repeat('- ', $repeat) . $item->##title##;
				self::$items[$hash][] = JHtml::_('select.option', $item->id, $item->##title##);
			}
			// Special "Add to root" option:
			self::$items[$hash][] = JHtml::_('select.option', '1', JText::_('JLIB_HTML_ADD_TO_ROOT'));
		}

		return self::$items[$hash];
	}
}
##codeend##