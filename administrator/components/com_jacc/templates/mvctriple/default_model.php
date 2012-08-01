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

jimport('joomla.application.component.modeladmin');

/**
 * Item Model for an ##Component## ##Name##
 *
 * @author ##author##
 * @package     Joomla.Administrator
 * @subpackage  com_##component##
 * @since       2.5
 */
class ##Component##Model##Name## extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	//TODO vielleicht nicht notendig!!!
	protected $text_prefix = '##COM_COMPONENT##.##NAME##';

	/**
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   11.1
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		##ifdefFieldparent_idStart##
		if (!empty($commands['##name##_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands['##name##_id'], $pks, $contexts);
				if (is_array($result))
				{
					$pks = $result;
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands['##name##_id'], $pks, $contexts))
			{
				return false;
			}
			$done = true;
		}

		##ifdefFieldparent_idEnd##
		##ifdefFieldcategory_idStart##
		if (!empty($commands['category_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands['category_id'], $pks, $contexts);
				if (is_array($result))
				{
					$pks = $result;
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands['category_id'], $pks, $contexts))
			{
				return false;
			}
			$done = true;
		}

		##ifdefFieldcategory_idEnd##
		##ifdefFieldcatidStart##
		if (!empty($commands['category_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands['category_id'], $pks, $contexts);
				if (is_array($result))
				{
					$pks = $result;
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands['category_id'], $pks, $contexts))
			{
				return false;
			}
			$done = true;
		}

		##ifdefFieldcatidEnd##
		##ifdefFieldaccessStart##
		if (!empty($commands['assetgroup_id']))
		{
			if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		##ifdefFieldaccessEnd##
		##ifdefFieldlanguageStart##
		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		##ifdefFieldlanguageEnd##
		##ifdefFielduser_idStart##
		if (strlen($commands['user_id']) > 0)
		{
			if (!$this->batchUser($commands['user_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		##ifdefFielduser_idEnd##
		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	2.5
	 */
	##ifnotdefFieldparent_idStart##
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', '##com_component##.##name##.'.
			((int) isset($data[$key]) ? $data[$key] : 0))
			or parent::allowEdit($data, $key);
	}

	##ifnotdefFieldparent_idEnd##
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			##ifdefFieldstateStart##
			if ($record->state != -2)
			{
				return ;
			}
			##ifdefFieldstateEnd##
			##ifdefFieldpublishedStart##
			if ($record->published != -2)
			{
				return ;
			}
			##ifdefFieldpublishedEnd##
			$user = JFactory::getUser();

			return $user->authorise('core.delete', '##com_component##.##name##.'.(int) $record->id);
		}
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		##ifnotdefFieldparent_idStart##
		// Check for existing ##name##.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', '##com_component##.##name##.' . (int) $record->id);
		}
		##ifdefFieldcatidStart##
		// New ##name##, so check against the category.
		elseif (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', '##com_component##.##nameplural####extra##.category.' . (int) $record->catid);
		}
		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		// New ##name##, so check against the category.
		elseif (!empty($record->category_id))
		{
			return $user->authorise('core.edit.state', '##com_component##.##nameplural####extra##.category.' . (int) $record->category_id);
		}
		##ifdefFieldcategory_idEnd##
		// Default to component settings if neither ##name## nor category known.
		else
		{
			return parent::canEditState('##com_component##');
		}
		##ifnotdefFieldparent_idEnd##
		##ifdefFieldparent_idStart##
		// Check for existing ##name##.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', '##com_component##.##nameplural####extra##.##name##.' . (int) $record->id);
		}
		// New ##name##, so check against the parent.
		elseif (!empty($record->parent_id))
		{
			return $user->authorise('core.edit.state', '##com_component##.##nameplural####extra##.##name##.' . (int) $record->parent_id);
		}
		// Default to component settings if neither ##name## nor parent known.
		else
		{
			return $user->authorise('core.edit.state', '##com_component##');
		}
		##ifdefFieldparent_idEnd##
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  The ##Component##Table object
	 *
	 * @since   2.5
	 */
	public function getTable($name = '##Name##', $prefix = '##Component##Table', $options = array())
	{
		return JTable::getInstance($name, $prefix, $options);
	}

	##ifdefFieldparent_idStart##
	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		$parentId = JRequest::getInt('parent_id');
		$this->setState('##name##.parent_id', $parentId);

		// Load the User state.
		$pk = (int) JRequest::getInt('id');
		$this->setState($this->getName() . '.id', $pk);

		##ifdefFieldextensionStart##
		//$extension = JRequest::getCmd('extension', 'com_content');
		//$this->setState('category.extension', $extension);
		//$parts = explode('.', $extension);

		// Extract the component name
		//$this->setState('category.component', $parts[0]);

		// Extract the optional section name
		//$this->setState('category.section', (count($parts) > 1) ? $parts[1] : null);

		##ifdefFieldextensionEnd##
		// Load the parameters.
		$params = JComponentHelper::getParams('##com_component##');
		$this->setState('params', $params);
	}

	##ifdefFieldparent_idEnd##
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   2.5
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			##ifdefFieldparent_idStart##
			if (empty($item->id))
			{
				$item->parent_id = $this->getState('##name##.parent_id');
				##ifdefFieldextensionStart##
				//$item->extension = $this->getState('category.extension');
				##ifdefFieldextensionEnd##
			}

			##ifdefFieldparent_idEnd##
			##ifdefFieldattribsStart##
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->attribs);
			$item->attribs = $registry->toArray();

			##ifdefFieldattribsEnd##
			##ifdefFieldmetadataStart##
			// Convert the metadata field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			##ifdefFieldmetadataEnd##
			##ifdefFieldimagesStart##
			// Convert the images field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();

			##ifdefFieldimagesEnd##
			##ifdefFieldurlsStart##
			// Convert the urls field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->urls);
			$item->urls = $registry->toArray();

			##ifdefFieldurlsEnd##
			// Convert the created and modified dates to local user time for display in the form.
			jimport('joomla.utilities.date');
			$tz = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
			##ifdefFieldcreated_timeStart##
			if (intval($item->created_time))
			{
				$date = new JDate($item->created_time);
				$date->setTimezone($tz);
				$item->created_time = $date->toSql(true);
			}
			else
			{
				$item->created_time = null;
			}

			##ifdefFieldcreated_timeEnd##
			##ifdefFieldcreatedStart##
			if (intval($item->created))
			{
				$date = new JDate($item->created);
				$date->setTimezone($tz);
				$item->created = $date->toSql(true);
			}
			else
			{
				$item->created = null;
			}

			##ifdefFieldcreatedEnd##
			##ifdefFieldmodified_timeStart##
			if (intval($item->modified_time))
			{
				$date = new JDate($item->modified_time);
				$date->setTimezone($tz);
				$item->modified_time = $date->toSql(true);
			}
			else
			{
				$item->modified_time = null;
			}
			##ifdefFieldmodified_timeEnd##
			##ifdefFieldmodifiedStart##
			if (intval($item->modified))
			{
				$date = new JDate($item->modified);
				$date->setTimezone($tz);
				$item->modified = $date->toSql(true);
			}
			else
			{
				$item->modified = null;
			}
			##ifdefFieldmodifiedEnd##
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   2.5
	 */
	public function getForm($data = array(), $loadData = true)
	{
		##ifdefFieldextensionStart##
		// Initialise variables.
		//$extension = $this->getState('category.extension');
		//$jinput = JFactory::getApplication()->input;

		// A workaround to get the extension into the model for save requests.
		//if (empty($extension) && isset($data['extension']))
		//{
			//$extension = $data['extension'];
			//$parts = explode('.', $extension);

			//$this->setState('category.extension', $extension);
			//$this->setState('category.component', $parts[0]);
			//$this->setState('category.section', @$parts[1]);
		//}

		##ifdefFieldextensionEnd##
		// Get the form.
		##ifdefFieldextensionStart##
		//$form = $this->loadForm('##com_component##.##name##' . $extension, '##name##', array('control' => 'jform', 'load_data' => $loadData));
		##ifdefFieldextensionEnd##
		$form = $this->loadForm('##com_component##.##name##', '##name##', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		// Modify the form based on Edit State access controls.
		##ifdefFieldextensionStart##
		//if (empty($data['extension']))
		//{
			//$data['extension'] = $extension;
		//}
		##ifdefFieldextensionEnd##
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display
			##ifdefFieldfeaturedStart##
			$form->setFieldAttribute('featured', 'disabled', 'true');
			##ifdefFieldfeaturedEnd##
			##ifdefFieldorderingStart##
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			##ifdefFieldorderingEnd##
			##ifdefFieldlftStart##
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			##ifdefFieldlftEnd##
			##ifdefFieldpublishedStart##
			$form->setFieldAttribute('published', 'disabled', 'true');
			##ifdefFieldpublishedEnd##
			##ifdefFieldstateStart##
			$form->setFieldAttribute('state', 'disabled', 'true');
			##ifdefFieldstateEnd##
			##ifdefFieldpublish_upStart##
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			##ifdefFieldpublish_upEnd##
			##ifdefFieldpublish_downStart##
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			##ifdefFieldpublish_downEnd##

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			##ifdefFieldfeaturedStart##
			$form->setFieldAttribute('featured', 'filter', 'unset');
			##ifdefFieldfeaturedEnd##
			##ifdefFieldorderingStart##
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			##ifdefFieldorderingEnd##
			##ifdefFieldlftStart##
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			##ifdefFieldlftEnd##
			##ifdefFieldpublishedStart##
			$form->setFieldAttribute('published', 'filter', 'unset');
			##ifdefFieldpublishedEnd##
			##ifdefFieldstateStart##
			$form->setFieldAttribute('state', 'filter', 'unset');
			##ifdefFieldstateEnd##
			##ifdefFieldpublish_upStart##
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			##ifdefFieldpublish_upEnd##
			##ifdefFieldpublish_downStart##
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			##ifdefFieldpublish_downEnd##
		}

		return $form;
	}

	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript()
	{
		return '/media/##com_component##/##name##/##name##.js';
	}

	##ifdefFieldparent_idStart##
	##ifdefFieldextensionStart##
	/**
	 * A protected method to get the where clause for the reorder
	 * This ensures that the row will be moved relative to a row with the same extension
	 *
	 * @param   JCategoryTable  $table  Current table instance
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 *
	 * @since   1.6
	 */
	//protected function getReorderConditions($table)
	//{
	//	return 'extension = ' . $this->_db->Quote($table->extension);
	//}

	##ifdefFieldextensionEnd##
	##ifdefFieldparent_idEnd##
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Get the application
		$app = JFactory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_##component##.edit.##name##.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
			##ifdefFieldcatidStart##

			// Prime some default values.
			if ($this->getState('##name##.id') == 0)
			{
				$data->set('catid', $app->input->get('catid', $app->getUserState('##com_component##.##nameplural####extra##.filter.category_id'), 'int'));
			}
			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##

			// Prime some default values.
			if ($this->getState('##name##.id') == 0)
			{
				$data->set('category_id', $app->input->get('category_id', $app->getUserState('##com_component##.##nameplural####extra##.filter.category_id'), 'int'));
			}
			##ifdefFieldcategory_idEnd##
		}

		return $data;
	}

	##ifnotdefFieldparent_idStart##
	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param	JTable	A JTable object.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		##ifdefField<?php echo $this->hident ?>Start##
		$table->##title## = htmlspecialchars_decode($table->##title##, ENT_QUOTES);
		##ifdefFieldaliasStart##
		$table->alias = JApplication::stringURLSafe($table->alias);

		if (empty($table->alias))
		{
			$table->alias = JApplication::stringURLSafe($table->##title##);
		}

		##ifdefFieldaliasEnd##
		##ifdefField<?php echo $this->hident ?>End##
		##ifdefFieldpublish_upStart##
		// Set the publish date to now
		$db = $this->getDbo();
		if(##ifdefFieldstateStart##$table->state == 1 && ##ifdefFieldstateEnd##intval($table->publish_up) == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

		##ifdefFieldpublish_upEnd##
		##ifdefFieldversionStart##
		// Increment the content version number.
		$table->version++;
		##ifdefFieldversionEnd##
		##ifdefFieldcatidStart##
		// Reorder the ##name## within the category so the new ##name## is first
		if (empty($table->id))
		{
			$table->reorder('catid = '.(int) $table->catid##ifdefFieldstateStart##.' AND state >= 0'##ifdefFieldstateEnd##);
		}
		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		// Reorder the ##name## within the category so the new ##name## is first
		if (empty($table->id))
		{
			$table->reorder('category_id = '.(int) $table->category_id##ifdefFieldstateStart##.' AND state >= 0'##ifdefFieldstateEnd##);
		}
		##ifdefFieldcategory_idEnd##
		##ifdefFieldorderingStart##
		if (empty($table->id))
		{
			// Set the values

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM ##table##');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else
		{
			// Set the values
		}
		##ifdefFieldorderingEnd##
	}

	##ifnotdefFieldparent_idEnd##
	##ifdefFieldcatidStart##
	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		return $condition;
	}

	##ifdefFieldcatidEnd##
	##ifdefFieldcategory_idStart##
	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'category_id = '.(int) $table->category_id;
		return $condition;
	}

	##ifdefFieldcategory_idEnd##
	##ifnotdefFieldparent_idStart##
	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data)
	{
		##ifdefFieldimagesStart##
		if (isset($data['images']) && is_array($data['images']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['images']);
			$data['images'] = (string)$registry;
		}

		##ifdefFieldimagesEnd##
		##ifdefFieldurlsStart##
		if (isset($data['urls']) && is_array($data['urls']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['urls']);
			$data['urls'] = (string)$registry;
		}
		##ifdefFieldurlsEnd##

		if (JRequest::getVar('task') == 'save2copy') {
			list($title##ifdefFieldaliasStart##, $alias##ifdefFieldaliasEnd##) = $this->generateNewTitle(##ifdefFieldcatidStart##$data['catid'], ##ifdefFieldcatidEnd####ifdefFieldcategory_idStart##$data['category_id'], ##ifdefFieldcategory_idEnd####ifnotdefFieldcategory_idStart####ifnotdefFieldcatidStart##'', ##ifnotdefFieldcatidEnd####ifnotdefFieldcategory_idEnd####ifdefFieldaliasStart##$data['alias'], ##ifdefFieldaliasEnd####ifnotdefFieldaliasStart##'', ##ifnotdefFieldaliasEnd##$data['##title##']);
			$data['##title##']	= $title;
			##ifdefFieldaliasStart##$data['alias']	= $alias;##ifdefFieldaliasEnd##
		}

		if (parent::save($data)) {
			##ifdefFieldfeaturedStart##
			if (isset($data['featured']))
			{
				$this->featured($this->getState($this->getName().'.id'), $data['featured']);
			}

			##ifdefFieldfeaturedEnd##
			return true;
		}

		return false;
	}
	##ifnotdefFieldparent_idEnd##
	##ifdefFieldparent_idStart##
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   11.1
	 */
	public function save($data)
	{
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table = $this->getTable();
		$key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Allow an exception to be thrown.
		try
		{
			// Load the row if saving an existing record.
			if ($pk > 0)
			{
				$table->load($pk);
				$isNew = false;
			}

			// Set the new parent id if parent id not matched OR while New/Save as Copy .
			if ($table->parent_id != $data['parent_id'] || $data[$key] == 0)
			{
				$table->setLocation($data['parent_id'], 'last-child');
			}

			// Alter the title for save as copy
			if (JRequest::getVar('task') == 'save2copy') {
				list($title##ifdefFieldaliasStart##, $alias##ifdefFieldaliasEnd##) = $this->generateNewTitle($data['parent_id'],##ifdefFieldaliasStart##$data['alias'], ##ifdefFieldaliasEnd####ifnotdefFieldaliasStart##'', ##ifnotdefFieldaliasEnd##$data['##title##']);
				$data['##title##']	= $title;
				##ifdefFieldaliasStart##$data['alias']	= $alias;##ifdefFieldaliasEnd##
			}

			// Bind the data.
			if (!$table->bind($data))
			{
				$this->setError($table->getError());
				return false;
			}

			// Bind the rules.
			if (isset($data['rules']))
			{
				$rules = new JAccessRules($data['rules']);
				$table->setRules($rules);
			}

			// Prepare the row for saving
			//$this->prepareTable($table);

			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			// Trigger the onContentBeforeSave event.
			$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, &$table, $isNew));
			if (in_array(false, $result, true))
			{
				$this->setError($table->getError());
				return false;
			}

			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			$pkName = $table->getKeyName();
			##ifdefFieldpathStart##
			// Rebuild the path for the #name#:
			if (!$table->rebuildPath($table->$pkName))
			{
				$this->setError($table->getError());
				return false;
			}

			##ifdefFieldpathEnd##
			// Rebuild the paths of the #name#'s children:
			if (!$table->rebuild($table->$pkName, $table->lft, $table->level##ifdefFieldpathStart##, $table->path##ifdefFieldpathEnd##))
			{
				$this->setError($table->getError());
				return false;
			}

			//$this->setState($this->getName() . '.id', $table->$pkName);

			// Clean the cache.
			$this->cleanCache();

			// Trigger the onContentAfterSave event.
			$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$pkName = $table->getKeyName();

		if (isset($table->$pkName))
		{
			$this->setState($this->getName() . '.id', $table->$pkName);
		}
		$this->setState($this->getName() . '.new', $isNew);

		return true;
	}

	##ifdefFieldparent_idEnd##

	##ifdefFieldparent_idStart##
	/**
	 * Method rebuild the entire nested set tree.
	 *
	 * @return  boolean  False on failure or error, true otherwise.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->rebuild())
		{
			$this->setError($table->getError());
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	##ifdefFieldparent_idEnd##
	##ifdefFieldparent_idStart##
	/**
	 * Method to save the reordered nested set tree.
	 * First we save the new order values in the lft values of the changed ids.
	 * Then we invoke the table rebuild to implement the new ordering.
	 *
	 * @param   array    $idArray    An array of primary key ids.
	 * @param   integer  $lft_array  The lft value
	 *
	 * @return  boolean  False on failure or error, True otherwise
	 *
	 * @since   1.6
	*/
	public function saveorder($idArray = null, $lft_array = null)
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->saveorder($idArray, $lft_array))
		{
			$this->setError($table->getError());
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	##ifdefFielduser_idStart##
	/**
	 * Batch change a linked user.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchUser($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($user->authorise('core.edit', $contexts[$pk]))
			{
				$table->reset();
				$table->load($pk);
				$table->user_id = (int) $value;

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	##ifdefFielduser_idEnd##
	##ifdefFieldparent_idEnd##
	##ifdefFieldparent_idStart##
	/**
	 * Batch copy categories to a new category.
	 *
	 * @param   integer  $value     The new category.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since   1.6
	 */
	protected function batchCopy($value, $pks, $contexts)
	{
		// $value comes as {parent_id}.{extension}
		$parts = explode('.', $value);
		$parentId = (int) JArrayHelper::getValue($parts, 0, 1);

		$table = $this->getTable();
		$db = $this->getDbo();
		$user = JFactory::getUser();
		##ifdefFieldextensionStart##
		//$extension = JFactory::getApplication()->input->get('extension', '', 'word');
		##ifdefFieldextensionEnd##
		$i = 0;

		// Check that the parent exists
		if ($parentId)
		{
			if (!$table->load($parentId))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Non-fatal error
					$this->setError(JText::_('JGLOBAL_BATCH_MOVE_PARENT_NOT_FOUND'));
					$parentId = 0;
				}
			}

			// Check that user has create permission for parent category
			##ifdefFieldextensionStart##
			//$canCreate = ($parentId == $table->getRootId()) ? $user->authorise('core.create', $extension) : $user->authorise('core.create', $extension . '.category.' . $parentId);
			##ifdefFieldextensionEnd##
			$canCreate = ($parentId == $table->getRootId()) ? $user->authorise('core.create', '##com_component##') : $user->authorise('core.create', '##com_component##.##nameplural####extra##.##name##.' . $parentId);
			if (!$canCreate)
			{
				// Error since user cannot create in parent category
				$this->setError(JText::_('COM_CATEGORIES_BATCH_CANNOT_CREATE'));
				return false;
			}
		}

		// If the parent is 0, set it to the ID of the root item in the tree
		if (empty($parentId))
		{
			if (!$parentId = $table->getRootId())
			{
				$this->setError($db->getErrorMsg());
				return false;
			}
			// Make sure we can create in root
			##ifdefFieldextensionStart##
			//elseif (!$user->authorise('core.create', $extension))
			##ifdefFieldextensionEnd##
			elseif (!$user->authorise('core.create', '##com_component##'))
			{
				$this->setError(JText::_('COM_CATEGORIES_BATCH_CANNOT_CREATE'));
				return false;
			}
		}

		// We need to log the parent ID
		$parents = array();

		// Calculate the emergency stop count as a precaution against a runaway loop bug
		$query = $db->getQuery(true);
		$query->select('COUNT(id)');
		$query->from($db->quoteName('##table##'));
		$db->setQuery($query);
		$count = $db->loadResult();

		if ($error = $db->getErrorMsg())
		{
			$this->setError($error);
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks) && $count > 0)
		{
			// Pop the first id off the stack
			$pk = array_shift($pks);

			$table->reset();

			// Check that the row actually exists
			if (!$table->load($pk))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Copy is a bit tricky, because we also need to copy the children
			$query->clear();
			$query->select('id');
			$query->from($db->quoteName('##table##'));
			$query->where('lft > ' . (int) $table->lft);
			$query->where('rgt < ' . (int) $table->rgt);
			$db->setQuery($query);
			$childIds = $db->loadColumn();

			// Add child ID's to the array only if they aren't already there.
			foreach ($childIds as $childId)
			{
				if (!in_array($childId, $pks))
				{
					array_push($pks, $childId);
				}
			}

			// Make a copy of the old ID and Parent ID
			$oldId = $table->id;
			$oldParentId = $table->parent_id;

			// Reset the id because we are making a copy.
			$table->id = 0;

			// If we a copying children, the Old ID will turn up in the parents list
			// otherwise it's a new top level item
			$table->parent_id = isset($parents[$oldParentId]) ? $parents[$oldParentId] : $parentId;

			// Set the new location in the tree for the node.
			$table->setLocation($table->parent_id, 'last-child');

			// TODO: Deal with ordering?
			//$table->ordering	= 1;
			$table->level = null;
			$table->asset_id = null;
			$table->lft = null;
			$table->rgt = null;

			// Alter the ##title## & alias
			list($title##ifdefFieldaliasStart##, $alias##ifdefFieldaliasEnd##) = $this->generateNewTitle($table->parent_id, ##ifdefFieldaliasStart##$table->alias, ##ifdefFieldaliasEnd####ifnotdefFieldaliasStart##'', ##ifnotdefFieldaliasEnd##$table->##title##);
			$table->##title## = $title;
			##ifdefFieldaliasStart##
			$table->alias = $alias;
			##ifdefFieldaliasEnd##

			// Store the row.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Get the new item ID
			$newId = $table->get('id');

			// Add the new ID to the array
			$newIds[$i] = $newId;
			$i++;

			// Now we log the old 'parent' to the new 'parent'
			$parents[$oldId] = $table->id;
			$count--;
		}

		// Rebuild the hierarchy.
		if (!$table->rebuild())
		{
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the tree path.
		if (!$table->rebuildPath($table->id))
		{
			$this->setError($table->getError());
			return false;
		}

		return $newIds;
	}

	##ifdefFieldparent_idEnd##
	##ifnotdefFieldparent_idStart##
	/**
	 * Batch copy items to a new category or current.
	 *
	 * @param   integer  $value     The new category.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since	11.1
	 */
	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId = (int) $value;

		$table = $this->getTable();
		$i = 0;

		// Check that the category exists
		if ($categoryId)
		{
			$categoryTable = JTable::getInstance('Category');
			if (!$categoryTable->load($categoryId))
			{
				if ($error = $categoryTable->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
					return false;
				}
			}
		}

		if (empty($categoryId))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
			return false;
		}

		// Check that the user has create permission for the component
		$extension = JFactory::getApplication()->input->get('option', '');
		$user = JFactory::getUser();
		if (!$user->authorise('core.create', $extension . '.category.' . $categoryId))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$table->reset();

			// Check that the row actually exists
			if (!$table->load($pk))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Alter the ##title####ifdefFieldaliasStart## & alias##ifdefFieldaliasEnd##
			$data = $this->generateNewTitle(##ifdefFieldcatidStart##$categoryId, ##ifdefFieldcatidEnd####ifdefFieldcategory_idStart##$categoryId, ##ifdefFieldcategory_idEnd####ifnotdefFieldcategory_idStart####ifnotdefFieldcatidStart##'', ##ifnotdefFieldcatidEnd####ifnotdefFieldcategory_idEnd####ifdefFieldaliasStart##$table->alias, ##ifdefFieldaliasEnd####ifnotdefFieldaliasStart##'', ##ifnotdefFieldaliasEnd##$table->##title##);
			$table->##title## = $data['0'];
			##ifdefFieldaliasStart##
			$table->alias = $data['1'];
			##ifdefFieldaliasEnd##

			// Reset the ID because we are making a copy
			$table->id = 0;

			// New category ID
			##ifdefFieldcatidStart##
			$table->catid = $categoryId;
			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##
			$table->category_id = $categoryId;
			##ifdefFieldcategory_idEnd##


			// TODO: Deal with ordering?
			//$table->ordering	= 1;

			// Check the row.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			// Store the row.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Get the new item ID
			$newId = $table->get('id');

			// Add the new ID to the array
			$newIds[$i]	= $newId;
			$i++;
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	##ifnotdefFieldparent_idEnd##
	##ifdefFieldparent_idStart##
	/**
	 * Batch move categories to a new category.
	 *
	 * @param   integer  $value     The new ##name## ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	protected function batchMove($value, $pks, $contexts)
	{
		$parentId = (int) $value;

		$table = $this->getTable();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$extension = JFactory::getApplication()->input->get('extension', '', 'word');

		// Check that the parent exists.
		if ($parentId)
		{
			if (!$table->load($parentId))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Non-fatal error
					$this->setError(JText::_('JGLOBAL_BATCH_MOVE_PARENT_NOT_FOUND'));
					$parentId = 0;
				}
			}
			// Check that user has create permission for parent category
			$canCreate = ($parentId == $table->getRootId()) ? $user->authorise('core.create', $extension) : $user->authorise('core.create', $extension . '.category.' . $parentId);
			if (!$canCreate)
			{
				// Error since user cannot create in parent category
				$this->setError(JText::_('COM_CATEGORIES_BATCH_CANNOT_CREATE'));
				return false;
			}

			// Check that user has edit permission for every category being moved
			// Note that the entire batch operation fails if any category lacks edit permission
			foreach ($pks as $pk)
			{
				if (!$user->authorise('core.edit', $extension . '.category.' . $pk))
				{
					// Error since user cannot edit this category
					$this->setError(JText::_('COM_CATEGORIES_BATCH_CANNOT_EDIT'));
					return false;
				}
			}
		}

		// We are going to store all the children and just move the category
		$children = array();

		// Parent exists so we let's proceed
		foreach ($pks as $pk)
		{
			// Check that the row actually exists
			if (!$table->load($pk))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Set the new location in the tree for the node.
			$table->setLocation($parentId, 'last-child');

			// Check if we are moving to a different parent
			if ($parentId != $table->parent_id)
			{
				// Add the child node ids to the children array.
				$query->clear();
				$query->select('id');
				$query->from($db->quoteName('#__categories'));
				$query->where($db->quoteName('lft' ) .' BETWEEN ' . (int) $table->lft . ' AND ' . (int) $table->rgt);
				$db->setQuery($query);
				$children = array_merge($children, (array) $db->loadColumn());
			}

			// Store the row.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Rebuild the tree path.
			if (!$table->rebuildPath())
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Process the child rows
		if (!empty($children))
		{
			// Remove any duplicates and sanitize ids.
			$children = array_unique($children);
			JArrayHelper::toInteger($children);

			// Check for a database error.
			if ($db->getErrorNum())
			{
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	##ifdefFieldparent_idEnd##
	##ifnotdefFieldparent_idStart##
	##ifdefFieldcategory_idStart##
	/**
	 * Batch move items to a new category
	 *
	 * @param   integer  $value     The new category ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since	11.1
	 */
	protected function batchMove($value, $pks, $contexts)
	{
		$categoryId = (int) $value;

		$table = $this->getTable();

		// Check that the category exists
		if ($categoryId)
		{
			$categoryTable = JTable::getInstance('Category');
			if (!$categoryTable->load($categoryId))
			{
				if ($error = $categoryTable->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
					return false;
				}
			}
		}

		if (empty($categoryId))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
			return false;
		}

		// Check that user has create and edit permission for the component
		$extension = JFactory::getApplication()->input->get('option', '');
		$user = JFactory::getUser();
		if (!$user->authorise('core.create', $extension . '.category.' . $categoryId))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}

		// Parent exists so we let's proceed
		foreach ($pks as $pk)
		{
			if (!$user->authorise('core.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}

			// Check that the row actually exists
			if (!$table->load($pk))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Set the new category ID
			$table->category_id = $categoryId;

			// Check the row.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			// Store the row.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	##ifdefFieldcategory_idEnd##
	##ifnotdefFieldparent_idEnd##
	##ifdefFieldparent_idStart##
	##ifdefFieldextensionStart##
	/* Custom clean the cache of com_content and content modules
	 *
	 * @since	1.6
	 */
	//protected function cleanCache($group = null, $client_id = 0)
	//{
	//	$extension = JRequest::getCmd('extension');
	//	switch ($extension)
	//	{
	//		default:
	//			parent::cleanCache($extension);
	//			break;
	//	}
	//}

	##ifdefFieldextensionEnd##
	##ifdefFieldparent_idEnd##
	/**
	 * Method to change the title##ifdefFieldaliasStart## and alias##ifdefFieldaliasEnd##.
	 *
	##ifnotdefFieldparent_idStart##
	##ifdefFieldcatidStart## * @param   integer  $category_id  The id of the category.##ifdefFieldcatidEnd##
	##ifdefFieldcategory_idStart## * @param   integer  $category_id  The id of the category.##ifdefFieldcategory_idEnd##
	##ifnotdefFieldparent_idEnd##
	##ifdefFieldparent_idStart##
	 * @param   integer  $parent_id  The id of the parent.
	##ifdefFieldparent_idEnd##
	##ifdefFieldaliasStart## * @param   string   $alias        The alias.##ifdefFieldaliasEnd##
	 * @param   string   $title        The title.
	 *
	 * @return	array  Contains the modified title##ifdefFieldaliasStart## and alias##ifdefFieldaliasEnd##.
	 *
	 * @since	11.1
	 */
	protected function generateNewTitle(##ifnotdefFieldparent_idStart####ifdefFieldcatidStart##$category_id, ##ifdefFieldcatidEnd####ifdefFieldcategory_idStart##$category_id, ##ifdefFieldcategory_idEnd####ifnotdefFieldcategory_idStart####ifnotdefFieldcatidStart##$dummy1 = '', ##ifnotdefFieldcatidEnd####ifnotdefFieldcategory_idEnd####ifnotdefFieldparent_idEnd####ifdefFieldparent_idStart##$parent_id, ##ifdefFieldparent_idEnd####ifdefFieldaliasStart##$alias, ##ifdefFieldaliasEnd####ifnotdefFieldaliasStart##$dummy2 = '', ##ifnotdefFieldaliasEnd##$title)
	{
		// Alter the title##ifdefFieldaliasStart## & alias##ifdefFieldaliasEnd##
		$table = $this->getTable();
		while ($table->load(array(
			##ifdefFieldaliasStart##'alias' => $alias, ##ifdefFieldaliasEnd##
			##ifnotdefFieldaliasStart##'##title##' => $title, ##ifnotdefFieldaliasEnd##
			##ifdefFieldparent_idStart##'parent_id' => $parent_id##ifdefFieldparent_idEnd##
			##ifnotdefFieldparent_idStart##
			##ifdefFieldcatidStart##'catid' => $category_id##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##'category_id' => $category_id##ifdefFieldcategory_idEnd##
			##ifnotdefFieldparent_idEnd##
		)))
		{
			$title = JString::increment($title);
			##ifdefFieldaliasStart##
			$alias = JString::increment($alias, 'dash');
			##ifdefFieldaliasEnd##
		}
		return array($title##ifdefFieldaliasStart##, $alias##ifdefFieldaliasEnd##);
	}

	##ifdefFieldfeaturedStart##
	/**
	 * Method to toggle the featured setting of contacts.
	 *
	 * @param	array	$pks	The ids of the items to toggle.
	 * @param	int		$value	The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('##COM_COMPONENT##_NO_ITEM_SELECTED'));
			return false;
		}

		$table = $this->getTable();

		try
		{
			$db = $this->getDbo();

			$db->setQuery(
				'UPDATE ##table##' .
				' SET featured = '.(int) $value.
				' WHERE id IN ('.implode(',', $pks).')'
			);
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		$table->reorder();

		// Clean component's cache
		$this->cleanCache();

		return true;
	}
	##ifdefFieldfeaturedEnd##
}
##codeend##
