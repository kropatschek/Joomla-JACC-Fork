<?php
defined('_JEXEC') or die;
/**
* @version		$Id:jacc.php  1 2010-08-15 12:57:56Z Michael Liebler $
* @package		Jacc
* @subpackage 	Models
* @copyright	Copyright (C) 2010, mliebler. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
/**
 * JaccModelJacc
 * @author mliebler
 */


class JaccModelJacc  extends JaccModel {



	protected $_default_filter = 'a.name';

	/**
	 * $var _MvcTable  actual table to create an MVC triple from
	 */
	private $_MvcTable = null;

	/**
	 * $var __MvcTemplate  actual template to create a file from
	 */
	private $_MvcTemplate = null;

	/**
	 * $var _Fields  	the Fields of all tables
	 */
	private $_Fields = array();

	/**
	 * $var _Tables  	all tables
	 */
	private $_Tables = array();

	/**
	 * $var _TablesHas  tables properties
	 */
	private $_TablesHas = array();

	private $_extensionXml = '';

/**
 * Constructor
 */

	public function __construct()
	{
		parent::__construct();

	}

	/**
	* Method to build the query
	*
	* @access private
	* @return string query
	*/

	protected function _buildQuery()
	{
		return parent::_buildQuery();
	}

	/**
	* Method to get the Component
	*
	* @access public
	* @return object item
	*/
	public function getItem()
	{
		static $instance;
		if($instance) return $instance;
		$item = $this->getTable();
		$item->load($this->_id);
		$item->tables = json_decode($item->tables);
		//$item->firsttable = json_decode($item->firsttable);
		$params = json_decode($item->params);
		$item->params = new JObject();
		$item->params ->setProperties(JArrayHelper::fromObject($params));
		$instance = $item;
		return $instance;
	}

	/**
	 *
	 * Method to set the actual table
	 * @param $table
	 */

	public function setMvcTable($table)
	{
		$this->_MvcTable = $table;
	}

	/**
	 *
	 * Method to get the actual table
	 */
	public function getMvcTable()
	{
		return $this->_MvcTable;
	}

		/**
	 *
	 * Method to set the actual template
	 * @param $table
	 */

	public function setMvcTemplate($template)
	{
		$this->_MvcTemplate = $template;
	}

	/**
	 *
	 * Method to get the actual template
	 */
	public function getMvcTemplate()
	{
		return $this->_MvcTemplate;
	}

	/**
	 * Method to set the Fields of a table
	 * @access public
	 * @param string table
	 * @param JObject $fields
	 */
	public function setTableFields($table, $fields)
	{
		$this->_Tables[] = $table;
		$this->_Fields[$table] = $fields;
	}

	/**
	 * Method to get the Fields of a table
	 * @access public
	 * @param string table
	 */
	public function getTableFields($table)
	{
		return $this->_Fields[$table];
	}

	/**
	 * Method to get a property of a field
	 * @access public
	 * @param string table
	 * @param string property
	 */
	public function TableHas($table, $property)
	{
		if (!isset($this->_TablesHas[$table])) return false;
		return $this->_TablesHas[$table]->get($property, false);
	}


	/**
	 * Method to check relations
	 * @access public
	 */
	public function checkTables()
	{
		$item = $this->getItem();
		$lname = strtolower(str_replace('com_', '', $item->name ));
		$this->_Fields['foreigns'] = array();
		foreach ($this->_Tables as $table) {
			$fields = $this->_Fields[$table];

			$fieldlist = $fields->all;
			$this->_TablesHas[$table] = new JObject();
			foreach ($fieldlist  as &$field) {

				$part = substr($field->get('key'), 0, strpos($field->get('key'), '_'));
				$newpart = substr($field->get('key'), 0, -3);

				$sfx = (substr(strrchr($field->get('key'), '_'), 1) == 'id');

				if ($field->get('key') == 'catid' || $field->get('key') == 'category_id')
				{
					$this->_TablesHas[$table]->set('category_id', $field->get('key'));
					$primary =$fieldlist['primary'];

					$this->_extensionXml .= "\n            <extension>\n".
										"                <name>".substr(strrchr($table, '_'), 1)."</name>\n".
										"                <display>".$fieldlist['hident']->get('key')."</display>\n".
										"                <field>".$field->get('key')."</field>\n".
										"                <primary>".$fieldlist['primary']->get('key')."</primary>\n".
										"                <table>".$table."</table>\n".
										"            </extension>\n";

				}
				else
				{
					$this->_TablesHas[$table]->set($field->get('key'), $field->get('key'));
				}

				if ($part && $sfx && ($foreigntable = $this->_findTable(substr($field->get('key'), 0, -3))))
				{

					$field->set('foreignkey', $part);
					$field->set('reltable', $foreigntable);
					$field->set('formfield', $lname. $part);
					$field->set('size', '1');

					//now in helper
					//$field->set('label', ucfirst($part));
					$field->set('fieldtype', $lname. $part);
					$field->set('required', 'true');

					$ffieldslist = $this->_Fields[$foreigntable];
					$ffields = $ffieldslist->get('all');
					$ffield = $ffields['hident'];
					$ffieldprim = $ffields['primary'];
					$this->_Fields['foreigns'][$field->get('key')] = new JObject();
					$this->_Fields['foreigns'][$field->get('key')]->set('key', $field->get('key'));
					$this->_Fields['foreigns'][$field->get('key')]->set('name', $part);
					$this->_Fields['foreigns'][$field->get('key')]->set('hident', $ffield->get('key'));
					$this->_Fields['foreigns'][$field->get('key')]->set('primary', $ffieldprim->get('key'));
					$this->_Fields['foreigns'][$field->get('key')]->set('reltable', $foreigntable);
					$this->_Fields['foreigns'][$field->get('key')]->set('table', $foreigntable);

					//$backupForeign = null;
					//$backupForeign = $field->get('foreign');
					//$foreign->primary = isset($backupForeign->primary) ? $backupForeign->primary : $ffieldprim->get('key');
					//$foreign->hident = isset($backupForeign->hident) ? $backupForeign->hident : $ffield->get('key');
					//$foreign->table = isset($backupForeign->table) ? $backupForeign->table : $foreigntable;
					//last part of table name as (lowercase) name
					//$foreign->name = isset($backupForeign->name) ? $backupForeign->name : InflectorHelper::singularize(substr(strrchr($foreigntable, '_'), 1));

					//$foreign->primary = $ffieldprim->get('key');
					//$foreign->hident = $ffield->get('key');
					//$foreign->table =  $foreigntable;
					//last part of table name as (lowercase) name
					//$foreign->name = InflectorHelper::singularize(substr(strrchr($foreigntable, '_'), 1));

					//$field->set('foreign', $foreign);

					$foreign->primary = $ffieldprim->get('key');
					$foreign->hident = $ffield->get('key');
					$foreign->table =  $foreigntable;
					//last part of table name as (lowercase) name
					$foreign->name = InflectorHelper::singularize(substr(strrchr($foreigntable, '_'), 1));

					//$foreigntext = print_r($foreign, true);

					$field->foreign = $foreign;
					// Fix: Todo: Bug set is not does not work i don't know why
					//$field->set('foreign', $foreign);
					//$field->set('foreigntext', $foreigntext);
				}
			}
		}

	}

	public function getExtensionXml()
	{
		return $this->_extensionXml;
	}

	private function _findTable($tablename)
	{
		$tablenameparts = array_reverse(explode("_", $tablename));
		$return = false;

		foreach ($this->_Tables as $table) {
			$tableparts = array_reverse(explode("_", substr($table, 3)));
			$tableparts[0] = InflectorHelper::singularize($tableparts[0]);

			$namefirstrun = true;
			foreach ($tablenameparts as $namepart)
			{
				if ($namefirstrun)
				{
					$tableNameSearch = $namepart;
					$namefirstrun = false;
				}
				else
				{
					$tableNameSearch = $namepart.'_'.$tableNameSearch;
				}
				$firstrun = true;
				$tableSearch = "";
				foreach ($tableparts as $part)
				{
					if ($firstrun)
					{
						$tableSearch = $part;
						$firstrun = false;
					}
					else
					{
						$tableSearch = $part.'_'.$tableSearch;
					}
					if ($tableSearch == $tableNameSearch)
					{
						$return = $table;
					}
				}
			}
// 			if (substr(strrchr($table, '_'), 1) == $tablename)
// 			{
// 				return $table;
// 			}
// 			elseif (substr(strrchr($table, '_'), 1) == InflectorHelper::pluralize($tablename))
// 			{
// 				return $table;
// 			}
		}
		return $return;
	}

	/**
	 * Method to store the Item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	public function store($data)
	{
		$row =$this->getTable();
		/**
		 * Example: get text from editor
		 * $Text  = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW );
		 */

		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		/**
		 * Clean text for xhtml transitional compliance
		 * $row->text		= str_replace( '<br>', '<br />', $Text );
		 */

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$this->setId($row->{$row->getKeyName()});
		return $row->{$row->getKeyName()};
	}

	/**
	* Method to build the Order Clause
	*
	* @access private
	* @return string orderby
	*/

	protected function _buildContentOrderBy()
	{
		$app = JFactory::getApplication('');
		$context			= $this->option.'.'.strtolower($this->getName()).'.list.';
		$filter_order = $app ->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'a.name', 'cmd');
		$filter_order_Dir = $app ->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$this->_query->order($filter_order . ' ' . $filter_order_Dir );

	}

	/**
	* Method to build the Where Clause
	*
	* @access private
	* @return string orderby
	*/

	protected function _buildContentWhere()
	{

		$app = JFactory::getApplication('');
		$context			= $this->option.'.'.strtolower($this->getName()).'.list.';
		$filter_state = $app ->getUserStateFromRequest($context . 'filter_state', 'filter_state', '', 'word');
		$filter_order = $app ->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'a.name', 'cmd');
		$filter_order_Dir = $app ->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'desc', 'word');
		$search = $app ->getUserStateFromRequest($context . 'search', 'search', '', 'string');

		if ($search) {
			$this->_query->where('LOWER(a.name) LIKE ' . $this->_db->Quote('%' . $search . '%'));
		}
		if ($filter_state) {
			if ($filter_state == 'P') {
				$this->_query->where("published = 1");
			} elseif ($filter_state == 'U') {
					$this->_query->where("published = 0");
			} else {
				$this->_query->where("published > -2");
			}
		} else {
			$this->_query->where("published > -2");
		}
	}

}
?>