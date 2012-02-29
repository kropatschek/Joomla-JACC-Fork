<?php defined('_JEXEC') or die;?>
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

jimport('joomla.database.tableasset');
jimport('joomla.database.table');

/**
* ##Component## ##Name##s table class
*
##ifdefVarpackageStart##
 * @package    ##package## Administrator
 * @subpackage ##com_component##
##ifdefVarpackageEnd##
##ifnotdefVarpackageStart##
 * @package    ##com_component## Administrator
##ifnotdefVarpackageEnd##
*/
class ##Component##Table##Name## extends JTable
{
	/**
	 * Constructor
	 *
	 * @param  JDatabase  &$db  Database object
	 *
	 * @since  2.5
	 */
	public function __construct(&$db)
	{
		parent::__construct('##table##', '##primary##', $db);
	}

	/**
	 * Overloaded store method for the notes table.
	 *
	 * @param   boolean  $updateNulls  Toggle whether null values should be updated.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   2.5
	 */
	public function store($updateNulls = false)
	{
		// Initialise variables.
		$date = JFactory::getDate()->toMySQL();
		$userId = JFactory::getUser()->get('id');


		if (empty($this->id))
		{
			// New record.
			##ifdefFieldcreatedStart##
			$this->created = $date;
			##ifdefFieldcreatedEnd##
			##ifdefFieldcreated_timeStart##
			$this->created_time = $date;
			##ifdefFieldcreated_timeEnd##
			##ifdefFieldcreated_user_idStart##
			$this->created_user_id = $userId;
			##ifdefFieldcreated_user_idEnd##
			##ifdefFieldcreated_byStart##
			$this->created_by = $userId;
			##ifdefFieldcreated_byEnd##
		}
		else
		{
			// Existing record.
			##ifdefFieldmodifiedStart##
			$this->modified = $date;
			##ifdefFieldmodifiedEnd##
			##ifdefFieldmodified_timeStart##
			$this->modified_time = $date;
			##ifdefFieldmodified_timeEnd##
			##ifdefFieldmodified_user_idStart##
			$this->modified_user_id = $userId;
			##ifdefFieldmodified_user_idEnd##
			##ifdefFieldmodified_byStart##
			$this->modified_by = $userId;
			##ifdefFieldmodified_byEnd##
		}

		// Attempt to store the data.
		return parent::store($updateNulls);
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to check-in rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/publish
	 * @since   2.5
	 */
	/*
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		$query = $this->_db->getQuery(true);
		$query->update($this->_db->quoteName($this->_tbl));
		$query->set($this->_db->quoteName('state') . ' = ' . (int) $state);

		// Build the WHERE clause for the primary keys.
		$query->where($k . '=' . implode(' OR ' . $k . '=', $pks));

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = false;
		}
		else
		{
			$query->where('(checked_out = 0 OR checked_out = ' . (int) $userId . ')');
			$checkin = true;
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery($query);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
	*/

	/**
	 * Overloaded bind function
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  An optional array or space separated list of properties
	 * to ignore while binding.
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
	 *
	 * @see     JTable::bind
	 * @since   11.1
	 */
	public function bind($array, $ignore = '')
	{
		##ifdefFieldparamsStart##
		if (isset( $array['params'] ) && is_array( $array['params']))
		{
			$array['params'] = json_encode( $array['params'] );
		}
		##ifdefFieldparamsEnd##
		##ifdefFieldattribsStart##
		if (isset($array['attribs']) && is_array($array['attribs']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['attribs']);
			$array['attribs'] = (string) $registry;
		}
		##ifdefFieldattribsEnd##
		##ifdefFieldmetadataStart##
		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
		##ifdefFieldmetadataEnd##
		/*
		##ifdefFieldimagesStart##
		if (isset($array['images']) && is_array($array['images']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['images']);
			$array['images'] = (string) $registry;
		}
		##ifdefFieldimagesEnd##
		##ifdefFieldurlsStart##
		if (isset($array['urls']) && is_array($array['urls']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['urls']);
			$array['urls'] = (string) $registry;
		}
		##ifdefFieldurlsEnd##
		*/

		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	public function check()
	{
		##ifdefFieldorderingStart##
		if ($this->##primary## === 0)
		{
			//get next ordering
			##ifdefFieldcatidStart##
			$condition = ' catid = '.(int) $this->catid ##ifdefFieldpublishedStart## . ' AND published >= 0 ' ##ifdefFieldpublishedEnd##;
			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##
			$condition = ' category_id = '.(int) $this->category_id ##ifdefFieldpublishedStart## . ' AND published >= 0 ' ##ifdefFieldpublishedEnd##;
			##ifdefFieldcategory_idEnd##
			$this->ordering = $this->getNextOrder( ##ifdefFieldcatidStart## $condition ##ifdefFieldcatidEnd##);
		}
		##ifdefFieldorderingEnd##
		##ifdefFieldcreatedStart##
		if (!$this->created)
		{
			$date = JFactory::getDate();
			$this->created = $date->toFormat("%Y-%m-%d %H:%M:%S");
		}
		##ifdefFieldcreatedEnd##
		##ifdefFieldcreated_timeStart##
		if (!$this->created_time)
		{
			$date = JFactory::getDate();
			$this->created_time = $date->toFormat("%Y-%m-%d %H:%M:%S");
		}
		##ifdefFieldcreated_timeEnd##
		/** check for valid name */
		/**
		##ifdefField<?php echo $this->hident ?>Start##
		if (trim($this->##title##) == '')
		{
			$this->setError(JText::_('Your ##Name## must contain a ##title##.'));
			return false;
		}
		##ifdefField<?php echo $this->hident ?>End##
		**/
		##ifdefFieldaliasStart##
		if (empty($this->alias))
		{
			$this->alias = $this->##title##;
		}

		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		/** check for existing alias */
		$query = 'SELECT '.$this->getKeyName().' FROM '.$this->_tbl.' WHERE alias = '.$this->_db->Quote($this->alias);
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());

		if ($xid && $xid != intval($this->{$this->getKeyName()}))
		{
			$this->setError(JText::_('Can\'t save to ##Name##. Name already exists'));
			return false;
		}

		##ifdefFieldaliasEnd##
		return true;
	}

	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form `table_name.id`
	 * where id is the value of the primary key of the table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return '##com_component##.##name##.'.(int) $this->$k;
	}

	/**
	 * Method to return the title to use for the asset table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetTitle()
	{
		return $this->##title##;
	}

	/**
	 * Get the parent asset id for the record
	 *
	 * @return	int
	 * @since	2.5
	 */
	protected function _getAssetParentId($table = null, $id = null)
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('##com_component##');
		return $asset->id;
	}
}
##codeend##
