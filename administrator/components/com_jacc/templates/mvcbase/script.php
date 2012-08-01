<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of ##Component## component
 */
class ##com_component##InstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		// Create categories for our component
		$basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
		require_once $basePath . '/models/category.php';
		$config = array( 'table_path' => $basePath . '/tables');
		$catmodel = new CategoriesModelCategory($config);
		$catData = array( 'id' => 0, 'parent_id' => 0, 'level' => 1, 'path' => 'testentry', 'extension' => '##com_component##'
				, 'title' => 'TestEntry', 'alias' => 'testentry', 'description' => '<p>This is a default test entry categorie</p>', 'published' => 1, 'language' => '*');
		$status = $catmodel->save($catData);

		if(!$status)
		{
			JError::raiseWarning(500, JText::_('Unable to create default content category!'));
		}
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=##com_component##');
	}

	/**
	 * method to uninstall the ##Component## component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// $parent is the class calling this method
		echo '<p>' . JText::_('##COM_COMPONENT##_UNINSTALL_TEXT') . '</p>';
	}

	/**
	 * method to update the ##Component## component
	 *
	 * @return void
	 */
	function update($parent)
	{
		// $parent is the class calling this method
		echo '<p>' . JText::sprintf('##COM_COMPONENT##_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('##COM_COMPONENT##_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('##COM_COMPONENT##_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
}