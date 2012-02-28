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
 * ##Component##Model##Name##
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
	protected $text_prefix = '##COM_COMPONENT##';

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
	// Todo:
	public function getTable($name = '##Name##', $prefix = '##Component##Table', $options = array())
	//public function getTable($name = '##Name##', $prefix = 'Table', $options = array())
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
		$form = $this->loadForm('com_##component##.##name##', '##name##', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		// TODO: Only if access controls are used
		// Modify the form based on access controls.
		##ifdefFieldaccessStart##
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
		##ifdefFieldaccessEnd##
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
		$table-><?php echo $this->hident ?> = htmlspecialchars_decode($table-><?php echo $this->hident ?>, ENT_QUOTES);
		##ifdefFieldaliasStart##
		$table->alias = JApplication::stringURLSafe($table->alias);

		if (empty($table->alias))
		{
			$table->alias = JApplication::stringURLSafe($table-><?php echo $this->hident ?>);
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
			$table->reorder('category_id = '.(int) $table->catid##ifdefFieldstateStart##.' AND state >= 0'##ifdefFieldstateEnd##);
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
		// ToDo: save2copy
		//if (JRequest::getVar('task') == 'save2copy') {
		//	list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
		//	$data['title']	= $title;
			##ifdefFieldaliasStart##
		//	$data['alias']	= $alias;
			##ifdefFieldaliasEnd##
		//}

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
}
##codeend##
