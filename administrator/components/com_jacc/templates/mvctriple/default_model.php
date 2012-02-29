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
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	2.5
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', '##com_component##.##name##.'.
			((int) isset($data[$key]) ? $data[$key] : 0))
			or parent::allowEdit($data, $key);
	}

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
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return ;
			}
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

		// Check for existing article.
		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', '##com_component##.##name##.'.(int) $record->id);
		}
		##ifdefFieldcatidStart##
		// New article, so check against the category.
		elseif (!empty($record->catid)) {
			return $user->authorise('core.edit.state', '##com_component##.##name##.category.'.(int) $record->catid);
		}
		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		// New article, so check against the category.
		elseif (!empty($record->category_id)) {
			return $user->authorise('core.edit.state', '##com_component##.##name##.category.'.(int) $record->category_id);
		}
		##ifdefFieldcategory_idEnd##

		// Default to component settings if neither article nor category known.
		else {
			return parent::canEditState('##com_component##');
		}
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  The table object
	 *
	 * @since   2.5
	 */
	public function getTable($name = '##Name##', $prefix = '##Component##Table', $options = array())
	{
		return JTable::getInstance($name, $prefix, $options);
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
		// TODO: maybe not necessary
		// JForm::addFieldPath('JPATH_ADMINISTRATOR/components/##com_component##/models/fields');

		// Get the form.
		$form = $this->loadForm('##com_component##.##name##', '##name##', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display
			##ifdefFieldfeaturedStart##
			$form->setFieldAttribute('featured', 'disabled', 'true');
			##ifdefFieldfeaturedStart##
			##ifdefFieldorderingStart##
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			##ifdefFieldorderingEnd##
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

			// Prime some default values.
			##ifdefFieldcatidStart##
			if ($this->getState('##name##.id') == 0)
			{
				//Todo plural
				$data->set('catid', $app->input->get('catid', $app->getUserState('com_##component##.##name##s.filter.category_id'), 'int'));
			}

			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##
			if ($this->getState('##name##.id') == 0)
			{
				//Todo plural
				$data->set('category_id', $app->input->get('category_id', $app->getUserState('com_##component##.##name##s.filter.category_id'), 'int'));
			}

			##ifdefFieldcategory_idEnd##
		}

		return $data;
	}

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
		}
		return $item;
	}

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
		##ifdefFieldimagesStart##
		if (JRequest::getVar('task') == 'save2copy') {
			list($title##ifdefFieldaliasStart##, $alias##ifdefFieldaliasEnd##) = $this->generateNewTitle(
				##ifdefFieldcatidStart##$data['catid'], ##ifdefFieldcatidEnd##
				##ifdefFieldcategory_idStart##$data['category_id'], ##ifdefFieldcategory_idEnd##
				##ifdefFieldaliasStart##$data['alias'], ##ifdefFieldaliasEnd##
				$data['##title##']
			);
			$data['##title##']	= $title;
			##ifdefFieldaliasStart##
			$data['alias']	= $alias;
			##ifdefFieldaliasEnd##
		}
		##ifdefFieldimagesEnd##
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
	##ifdefFieldfeaturedStart##


	/**
	 * Method to change the title##ifdefFieldaliasStart## and alias##ifdefFieldaliasEnd##.
	 *
	##ifdefFieldcatidStart##
	 * @param   integer  $category_id  The id of the category.
	##ifdefFieldcatidEnd##
	##ifdefFieldcategory_idStart##
	 * @param   integer  $category_id  The id of the category.
	##ifdefFieldcategory_idEnd##
	##ifdefFieldaliasStart##
	 * @param   string   $alias        The alias.
	##ifdefFieldaliasEnd##
	##ifdefFieldaliasStart##
	 * @param   string   $title        The title.
	##ifdefFieldaliasEnd##
	 *
	 * @return	array  Contains the modified title##ifdefFieldaliasStart## and alias##ifdefFieldaliasEnd##.
	 *
	 * @since	11.1
	 */
	protected function generateNewTitle(##ifdefFieldcatidStart##$category_id, ##ifdefFieldcatidEnd####ifdefFieldcategory_idStart##$category_id, ##ifdefFieldcategory_idEnd####ifdefFieldaliasStart##$alias, ##ifdefFieldaliasEnd##$title)
	{
		// Alter the title & alias
		$table = $this->getTable();
		while ($table->load(array(
			##ifdefFieldaliasStart##'alias' => $alias, ##ifdefFieldaliasEnd##
			##ifnotdefFieldaliasStart##'##title##' => $title, ##ifnotdefFieldaliasEnd##
			##ifdefFieldcatidStart##'catid' => $category_id##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##'category_id' => $category_id##ifdefFieldcategory_idEnd##
		)))
		{
			$title = JString::increment($title);
			##ifdefFieldaliasStart##
			$alias = JString::increment($alias, 'dash');
			##ifdefFieldaliasEnd##
		}
		return array($title##ifdefFieldaliasStart##, $alias##ifdefFieldaliasEnd##);
	}



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
