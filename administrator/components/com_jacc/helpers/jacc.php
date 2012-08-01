<?php
/**
 * @version		$Id: jacc.php 98 2011-08-11 10:02:00Z michel $
 * @package		Joomla.Framework
 * @subpackage		HTML
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'query.php');

class JaccHelper
{

		/*
	 * Submenu for Joomla 1.6
	 */
	public static function addSubmenu($vName = 'jacc')
	{

		JSubMenuHelper::addEntry(
			JText::_('Components'),
			'index.php?option=com_jacc&view=jacc',
			($vName == 'jacc')
		);

		JSubMenuHelper::addEntry(
			JText::_('Modules'),
			'index.php?option=com_jacc&view=modules',
			($vName == 'modules')
		);

		JSubMenuHelper::addEntry(
			JText::_('Plugins'),
			'index.php?option=com_jacc&view=plugins',
			($vName == 'plugins')
		);

		JSubMenuHelper::addEntry(
			JText::_('Templates'),
			'index.php?option=com_jacc&view=templates',
			($vName == 'templates')
		);

		JSubMenuHelper::addEntry(
			JText::_('Packages'),
			'index.php?option=com_jacc&view=packages',
			($vName == 'packages')
		);

		JSubMenuHelper::addEntry(
			JText::_('howto'),
			'index.php?option=com_jacc&view=howto',
			($vName == 'howto')
		);

	}

	/**
	 * Method to get version from manifest cache
	 *
	 */
	public static function getVersion()
	{
		static $version;
		if(!empty($version)) return $version;
		$jv = new JVersion();
		if ($jv->RELEASE > 1.5) {
			$me = JComponentHelper::getComponent('com_jacc');
			$db = JFactory::getDbo();
			$query = "SELECT manifest_cache FROM #__extensions WHERE extension_id = ".(int) $me->id;
			$db->setQuery($query);
			$manifest_cache = 	$db->loadResult();
			$manifest = json_decode($manifest_cache);
			$version = isset($manifest->version) ? $manifest->version : 'unknown';
		} else {
			$manifest = JApplicationHelper::parseXMLInstallFile(JPATH_COMPONENT_ADMINISTRATOR.DS.'com_jacc.xml');
			$version = isset($manifest['version']) ? $manifest['version'] : 'unknown';
		}
		return $version;
	}

	/**
	 *
	 * Sorting callback function
	 * @param object $a
	 * @param object $b
	 */
	public static function sortOrdering ($a,$b)
	{
			if ($a->ordering == $b->ordering) {
					return 0;
			}
			return ($a->ordering < $b->ordering) ? -1 : 1;
	}


	/**
	 * Retrieves table information.
	 *
	 * @return  array  An array of tables for the database.
	 *
	 * @since   11.1
	 * @throws  JDatabaseException
	 */
	public function getTablesStatus()
	{
		$result = array();

		// Set the query to get the table fields statement.
		$this->setQuery('SHOW TABLE STATUS');
		$tables = $this->loadObjectList();

		foreach ($tables as $table)
		{
			$result[$table->Name] = $table;
		}

		return $result;
	}

	public function acronym( $string = '', $delimiter = ' ')
	{
		$words = explode($delimiter, $string);

		if ( ! $words )
		{
			return false;
		}

		$result = '';

		foreach ( $words as $word )
		{
			$result .= $word[0];
		}

		return $result;
	}

	/**
	 *
	 * This Method creates an object of several field lists
	 *
	 * Example:
	 * $db = JFactory::getDB0;
	 * $tablefields = $db->getTableFields('#__book', false);
	 * $fields = JaccHelper::getSpecialFields($tablefields['#__book'])
	 * $all = $fields->get('all') // returns a list of all available tables
	 * $listfields = $fields->get('list') // fields which are usefull to display in the list view
	 * $delete = $fields->get('delete') // a list of common fields which are *not* present in the table
	 * $groups = $fields->get('groups') // the fields sorted by the groups which are defined by fields.xml
	 *
	 * @param array $tablefields - fields returned by $db->getTableFields($table, false);
	 * @return JObject $fields
	 */
	public static function getSpecialFields ($tablefields)
	{
		global $alt_libdir;


		JLoader::import('joomla.utilities.xmlelement', $alt_libdir);
		$xml = simplexml_load_file(JPATH_COMPONENT.DS.'models'.DS.'fields.xml', 'JXMLElement');

		$elements = $xml->xpath('fields');
		$fields = $xml->fields->xpath('descendant-or-self::field');
		$specialFields = array();
		foreach ($fields as $field)
		{
			$fieldkey = (string) $field->key;
			$specialFields[$fieldkey] = new JObject();
			$specialFields[$fieldkey]->setProperties($field);
			$specialFields[$fieldkey]->set('delete', true);
		}
		$hident = null;
		$ordering = 10;
		$primfield= null;
		$firstVarcharField = null;
		foreach ($tablefields as $tablefield) {
			$field = $tablefield->Field;

			$legal = '/[^A-Z0-9_]/i';
			if (preg_match_all($legal, $field, $matches))
			{
				$suggest = (string) preg_replace( $legal, '', $field);
				return 'Field '.$field. ' contains illegal characters. It\'s suggested to rename it as '.$suggest;
			}

			$maxlength = 0;
			if(preg_match_all('/[0-9]+/', (string) $tablefield->Type, $matches))
			{
				$maxlength = (int) $matches[0][0] + (isset($matches[0][1]) ? (1 + (int) $matches[0][1]) : 0);
			}
			if (strtolower($tablefield->Type) == 'int(1)' || strtolower($tablefield->Type) == 'tinyint(1)')
			{
				$tablefield->Type = 'boolean';
			}
			else
			{
				$tablefield->Type = strtolower(preg_replace('/\(.*\)/i', '', $tablefield->Type));
			}

			//find something like a title or a name or sku as a human comprehensible identifier for this item
			if (strtolower($field) == 'title' || strtolower($field) == 'domain' || strtolower($field) == 'name' || strtolower($field) == 'sku')
			{
				$hident = $field;
			}

			//find the first varchar. this may be the ident too
			if ($tablefield->Type == 'varchar' && $firstVarcharField === null)
			{
					$firstVarcharField = $field;
			}

			//find it in the special fields
			if (isset($specialFields[$field]))
			{
				//set delete to false. According parts of the templates will not be deleted
				$specialFields[$field]->set('fieldtype', $tablefield->Type);
				$specialFields[$field]->set('default', $tablefield->Default);
				$specialFields[$field]->set('delete', false);
			}
			else
			{
				//this is not a special field. add it to the object.
				if ($tablefield->Key == 'PRI') {
					$primfield = $field;
					$field = 'primary';
					//$specialFields['primary'] = new JObject();

					$specialFields['primary']->set('key', $tablefield->Field);
					$specialFields['primary']->set('group', '');
					$specialFields['primary']->set('formfield', '');
					$specialFields['primary']->set('filter', 'unset');
					$specialFields['primary']->set('alt', '');
					$specialFields['primary']->set('ordering', 99);
					$specialFields['primary']->set('delete', false);
				}
				else
				{
					$specialFields[$field] = new JObject();
					$specialFields[$field]->set('key', $tablefield->Field);
					$specialFields[$field]->set('group', 'details');
					$specialFields[$field]->set('alt', '');
					//$specialFields[$field]->set('required', false);
					//$specialFields[$field]->set('readonly', false);
					//$specialFields[$field]->set('disabled', false);
					$specialFields[$field]->set('delete', false);
					$specialFields[$field]->set('class', 'inputbox');
					$specialFields[$field]->set('list', true);
					$specialFields[$field]->set('fieldtype', $tablefield->Type);
					$specialFields[$field]->set('default', $tablefield->Default);

					//handle some special field types
					switch($tablefield->Type) {
						case 'bool':
						case 'boolean':
							$specialFields[$field]->set('formfield', 'radio');
							$specialFields[$field]->set('filter', 'boolean');
							$specialFields[$field]->set('class', 'inputbox');
							$options = "\t\t\t\t<option\n"
								. "\t\t\t\t\tvalue=\"0\">JNO</option>\n"
								. "\t\t\t\t<option\n"
								. "\t\t\t\t\tvalue=\"1\">JYES</option>\n";
							$specialFields[$field]->set('options', $options);
							break;
						case 'int unsigned':
							$specialFields[$field]->set('formfield', 'text');
							$specialFields[$field]->set('filter', 'uint');
							$specialFields[$field]->set('class', 'inputbox validate-numeric');
							$specialFields[$field]->set('size', $maxlength > 20 ? 20 : $maxlength);
							break;
						case 'int':
						case 'integer':
							$specialFields[$field]->set('formfield', 'text');
							$specialFields[$field]->set('filter', 'integer');
							$specialFields[$field]->set('class', 'inputbox validate-numeric');
							$specialFields[$field]->set('size', $maxlength > 20 ? 20 : $maxlength);
							break;
						case 'float':
						case 'double':
						case 'decimal':
							$specialFields[$field]->set('formfield', 'text');
							$specialFields[$field]->set('filter', 'float');
							$specialFields[$field]->set('class', 'inputbox validate-numeric');
							$specialFields[$field]->set('size', $maxlength > 20 ? 20 : $maxlength);
							break;
						case 'varchar':
							$specialFields[$field]->set('filter', 'string');
							$specialFields[$field]->set('formfield', 'text');
							$specialFields[$field]->set('size', $maxlength > 40 ? 40 : $maxlength);
							break;
						case 'text':
							$specialFields[$field]->set('formfield', 'textarea');
							$specialFields[$field]->set('filter', 'string');
							$specialFields[$field]->set('rows', '3');
							$specialFields[$field]->set('cols', '30');
							break;
						case 'mediumtext':
						case 'longtext':
							$specialFields[$field]->set('formfield', 'editor');
							$specialFields[$field]->set('filter', 'safehtml');
							$specialFields[$field]->set('rows', '3');
							$specialFields[$field]->set('cols', '30');
							break;
						case 'date':
							$specialFields[$field]->set('formfield', 'calendar');
							$specialFields[$field]->set('filter', 'user_utc');
							$specialFields[$field]->set('format', '%Y-%m-%d');
							$specialFields[$field]->set('size', '13');
							break;
						case 'datetime':
							$specialFields[$field]->set('formfield', 'calendar');
							$specialFields[$field]->set('filter', 'user_utc');
							$specialFields[$field]->set('format', '%Y-%m-%d %H:%M:%S');
							$specialFields[$field]->set('size', '22');
							break;
						default:
							$specialFields[$field]->set('formfield', 'text');
							break;
					}

					//set ordering
					$specialFields[$field]->set('ordering', $ordering);

					//set delete to false. According parts of the templates will not be deleted
					//set the type

					if ($maxlength)
					{
						$specialFields[$field]->set('maxlength', $maxlength);
					}
				}
				$ordering++;
			}

			$tablefield->Comment = trim($tablefield->Comment);
			$backupLabel = $specialFields[$field]->get('label');
			$backupDescription = $specialFields[$field]->get('description');
			if ((substr($tablefield->Comment, 0, 1) == '{') && (substr($tablefield->Comment, -1, 1) == '}'))
			{
				$specialFields[$field]->setProperties(json_decode($tablefield->Comment, true));
				if ($specialFields[$field]->get('hident', false))
				{
					$hident = $field;
				}
			}

			$label = $specialFields[$field]->get('label');
			if (!is_array($label))
			{
				$label = array();
			}
			if (!array_key_exists('en-GB', $label))
			{
				$label['en-GB'] = ($backupLabel ? $backupLabel : ucwords(strtr((substr(strrchr($tablefield->Field, '_'), 1) == 'id') ? substr($tablefield->Field, 0, -3) : $tablefield->Field,'_',' ')));
			}
			$specialFields[$field]->set('label', $label);

			$description = $specialFields[$field]->get('description');
			if (!is_array($description))
			{
				$description = array();
			}
			if (!array_key_exists('en-GB', $description))
			{
				$description['en-GB'] = ($backupDescription ? $backupDescription : '');
			}
			$specialFields[$field]->set('description', $description);
		}

		//find the human comprehensible identifier
		if ($hident === null )
		{
			if ($firstVarcharField)
			{
				//we can use the first field of type varchar
				$hident = $firstVarcharField;
				$specialFields['hident'] = clone($specialFields[$hident]);
				unset ($specialFields[$hident]);
			}
			elseif ($primfield )
			{
				$specialFields['hident'] = clone($specialFields['primary']);
				$hident = $primfield;
			}
		}
		else
		{
			$specialFields['hident'] = clone($specialFields[$hident]);
			unset ($specialFields[$hident]);
		}

		//replace hident field
		$specialFields['hident']->set('key', $hident);
		$specialFields['hident']->set('delete', false);
		$specialFields['hident']->set('group', 'details');
		$specialFields['hident']->set('list', true);
		$specialFields['hident']->set('required', true);

		//$label['en-GB'] = ($specialFields['hident']->get('label') ? $specialFields['hident']->get('label') : ucwords(strtr($hident,'_',' ')));
		//$specialFields['hident']->set('label', $label);
		//$description['en-GB'] = ($specialFields['hident']->get('description') ? $specialFields['hident']->get('description') : '');
		//$specialFields['hident']->set('description', $description);

		if ($primfield) {
			//add primary field as "additional" to the end
			$specialFields[$primfield] = clone($specialFields['primary']);
			$specialFields[$primfield]->set('additional', true);
			$specialFields[$primfield]->set('ordering', 99);
			$specialFields[$primfield]->set('list', true);
		}

		//collect fields by groups
		$groups = array();
		foreach ($specialFields as $field) {
			if (($group = $field->get('group')) && (!$field->get('delete'))) {
				if (!isset($groups[$group])) {
					$groups[$group] = array();
				}
				$groups[$field->get('group')][$field->key] = $field;
			}
		}

		//sort groups
		foreach ($groups as &$group) {
			uasort($group, 'JaccHelper::sortOrdering');
		}

		//collect fields for the list view
		$list = array();

		foreach ($specialFields as $field) {
			if ($field->get('list') && (!$field->get('delete'))) {
				$list[$field->key] = $field;
			}
		}

		//sort the list
		uasort($list, 'JaccHelper::sortOrdering');

		//collect not required fields
		$delete = array();
		foreach ($specialFields as $field) {
			if (($field->get('delete'))) {
				$delete[] = $field->key;
			}
		}

		foreach ($specialFields as $field=>$element) {
			if (($element->get('delete'))) {
				unset($specialFields[$field]);
			}
		}

		$fields = new JObject();
		//a list of all present fields
		$fields->set('all', $specialFields);
		//the fields sorted by the groups which are defined by fields.xml
		$fields->set('groups', $groups);
		//the fields which are usefull for a list view
		$fields->set('list', $list);
		//this fields are defined by fields.xml but don't occure in the table
		$fields->set('delete', $delete);

		return $fields;
	}

	/**
	 *
	 * Create a Folder and add index.html
	 * @param string $folder - the folder to create
	 */
	public static function createFolder($folder)
	{
		if (JFolder::create($folder) === false) {
			return false;
		}

		$html = '<html><body bgcolor="#FFFFFF"></body></html>';
		file_put_contents($folder.DS.'index.html', $html);
		return true;

	}
	/**
	 *
	 * Finds existing language files of the component
	 * @param string $name - the component name
	 */
	public static function getLanguagefiles($name)
	{
		$files_site = JFolder::files(JPATH_SITE.DS.'language', $filter = '*.'.$name.'*', $recurse = true, $fullpath = true);
		$files_admin = JFolder::files(JPATH_ADMINISTRATOR.DS.'language', $filter = '*.'.$name.'*', $recurse = true, $fullpath = true);
	}

	/**
	 *
	 * Method to create a CREATE TABLE SQL
	 * @param string $table
	 * @param string $break - OS specific line end
	 */
	public static function export_table_structure($table, $break = "\n")
	{

		$db = JFactory::getDBO();
		$config =JFactory::getConfig();
		$dbprefix= $config->getValue('config.dbprefix');

		$break = ($break == "\n" OR $break == "\r\n" OR $break == "\r") ? $break : "\n";

		$sqlstring = "";

		$query = 'SHOW CREATE TABLE '. $table;
		$db->setQuery('SHOW CREATE TABLE '. $table);
		$data = $db->loadAssoc();

		$sqlstring .= str_replace("\n", $break, $data['Create Table']) . ";$break$break";
		$sqlstring = preg_replace("! AUTO_INCREMENT=(.*);!", ';', $sqlstring);
		$sqlstring = str_replace($dbprefix, '#__', $sqlstring);
		$sqlstring = str_replace("CREATE TABLE", 'CREATE TABLE IF NOT EXISTS', $sqlstring);
		$sqlstring = str_replace(" DEFAULT CHARSET=utf8", '', $sqlstring);

		return $sqlstring;
	}

/**
 *
 * Method to create the SQL for categories
 * @param string $lcomponent - the component name
 * @return string SQL
 */
	public static 	function getCatsql($lcomponent)
	{

		return "
CREATE TABLE IF NOT EXISTS `#__".$lcomponent."_categories` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`asset_id` int(10) unsigned NOT NULL DEFAULT '0',
`parent_id` int(10) unsigned NOT NULL DEFAULT '0',
`lft` int(11) NOT NULL DEFAULT '0',
`rgt` int(11) NOT NULL DEFAULT '0',
`level` int(10) unsigned NOT NULL DEFAULT '0',
`path` varchar(255) NOT NULL,
`extension` varchar(50) NOT NULL,
`title` varchar(255) NOT NULL,
`alias` varchar(255) NOT NULL,
`description` varchar(5120) NOT NULL,
`published` tinyint(1) NOT NULL DEFAULT '0',
`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`access` tinyint(3) unsigned NOT NULL DEFAULT '0',
`params` varchar(2048) NOT NULL,
`metadesc` varchar(1024) NOT NULL,
`metakey` varchar(1024) NOT NULL,
`metadata` varchar(2048) NOT NULL,
`created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
`created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
`modified_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`hits` int(10) unsigned NOT NULL DEFAULT '0',
`language` varchar(7) NOT NULL,
`section` int(11) NOT NULL,
`ordering` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `cat_idx` (`extension`,`published`,`access`),
KEY `idx_access` (`access`),
KEY `idx_checkout` (`checked_out`),
KEY `idx_path` (`path`),
KEY `idx_left_right` (`lft`,`rgt`),
KEY `idx_alias` (`alias`)
) TYPE=MyISAM;
Replace INTO `#__".$lcomponent."_categories`  VALUES(1, 0, 0, 0, 5, 0, '', 'system', 'ROOT', 'root', '', 1, 0, '0000-00-00 00:00:00', 1, '{}', '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', 0, 0);
";
	}



	public static function _replace($file, $item, $options=array())
	{

		$date = JFactory::getDate($item->created);

		$categorytask = ($item->params->get('uses_categories')) ? JaccHelper::getcategorytask () : "";


		$reltable = isset($options['reltable']) ? $options['reltable'] : '';

		if((isset($options['submenu']) && trim($options['submenu']))) {
			$options['submenu'] = "\t\t\t<submenu>\n".$options['submenu']."\t\t\t</submenu>";
		}

		$description = $item->description;
		$version = $item->version;
		$package = $item->package;

		$params = JComponentHelper::getParams('com_jacc');
		$com_component = $item->name;
		$lcomponent = strtolower(str_replace('com_', '', $com_component ));
		$component = ucfirst($lcomponent);
		$Ucomponent = strtoupper($lcomponent);

		$file = str_replace("##table##", $reltable, $file);
		$file = str_replace("##categorytask##", $categorytask, $file);
		$file = str_replace("##codestart##", '<?php', $file);
		$file = str_replace("##codeend##", '?>', $file);

		$file = str_replace("##Component##", $component, $file);
		$file = str_replace("##description##", $description, $file);

		/**
		$file = str_replace("##defaultview##", $defaultview, $file);
		$file = str_replace("##firstname##", $firstName, $file);
		$file = str_replace("##Firstname##", $UFirstName, $file);

		**/
		$file = str_replace("##version##", $version, $file);
		$file = str_replace("##package##", $package, $file);
		$file = str_replace("##table##", $reltable, $file);

		$file = str_replace("##website##", $params->get('website'), $file);
		$file = str_replace("##author##", $params->get('author'), $file);
		$file = str_replace("##sauthor##", $params->get('sauthor'), $file);
		$file = str_replace("##email##", $params->get('email'), $file);
		$file = str_replace("##license##", $params->get('license'), $file);
		$file = str_replace("##component##", $lcomponent, $file);
		$file = str_replace("##COMPONENT##", $Ucomponent, $file);

		$file = str_replace("##date##", $date->toFormat('%Y-%m-%d'), $file);
		$file = str_replace("##year##", $date->toFormat('%Y'), $file);
		$file = str_replace("##com_component##", $com_component, $file);
		$file = str_replace("##COM_COMPONENT##", strtoupper($com_component), $file);

		foreach($options as $key => $value) {
			$value = (string) $value;
			$FUkey = ucfirst($key);
			$FUvalue = ucfirst($value);
			$Ukey = strtoupper($key);
			$Uvalue = strtoupper($value);
			$file = str_replace("##".$key."##", $value, $file);
			$file = str_replace("##".$FUkey."##", $FUvalue, $file);
			$file = str_replace("##".$Ukey."##", $Uvalue, $file);
		}

		$varList[]= "package";

		foreach ($varList as $var)
		{
			// if definend delete just the defined tags
			$pattern = '/((?:\r\n|\r|\n|^))[ \t]*##ifdefVar'.$var.'(Start|End)##(?:[ \t]|[^\n\r#])*(?:\r\n|\r|\n|$)/isU';
			$file	= preg_replace($pattern, '$1', $file);
			$pattern = '/##ifdefVar'.$var.'[Start|End]##/isU';
			$file	= preg_replace($pattern, '', $file);
		}

		// if not definend delete the defined tags and the tag content
		$pattern = '/(?:\r\n|\r|\n|^)[ \t]*##ifdefVar\w+Start##.*##ifdefVar\w+End##(?:[ \t]|[^\n\r])*/isU';
		$file	= preg_replace($pattern, '', $file);
		$pattern = '/##ifdefVar\w+Start##.*##ifdefVar\w+End##/isU';
		$file	= preg_replace($pattern, '', $file);

		foreach ($varList as $var)
		{
			$pattern = '/(?:\r\n|\r|\n|^)[ \t]*##ifnotdefVar'.$var.'Start##.*##ifnotdefVar'.$var.'End##(?:[ \t]|[^\n\r])*/imsU';
			$file	= preg_replace($pattern, '', $file);
			$pattern = '/##ifnotdefVar'.$var.'Start##.*##ifnotdefVar'.$var.'End##/isU';
			$file	= preg_replace($pattern, '', $file);
		}

		$pattern = '/((?:\r\n|\r|\n|^))[ \t]*##ifnotdefVar\w+(Start|End)##(?:[ \t]|[^\n\r#])*(?:\r\n|\r|\n|$)/isU';
		$file	= preg_replace($pattern, '$1', $file);
		$pattern = '/##ifnotdefVar\w+[Start|End]##/isU';
		$file	= preg_replace($pattern, '', $file);


// 		foreach ($varList as $var) {


// 			$pattern = '/(?:\r\n|\r|\n|^)[ \t]*##ifdefVar'.$var.'Start##.*##ifdefVar'.$var.'End##(?:[ \t]|[^\n\r])*/isU';
// 			$file	= preg_replace($pattern, '', $file);
// 			$pattern = '/##ifdefVar'.$var.'Start##.*##ifdefVar'.$var.'End##/isU';
// 			$file	= preg_replace($pattern, '', $file);
// 		}

// 		foreach ($allVars  as $Var) {
// 			$pattern = '/(?:\r\n|\r|\n|^)[ \t]*##ifnotdefVar'.$Var->get('key').'Start##.*##ifnotdefVar'.$Var->get('key').'End##(?:[ \t]|[^\n\r])*/imsU';
// 			$file	= preg_replace($pattern, '', $file);
// 			$pattern = '/##ifnotdefVar'.$Var->get('key').'Start##.*##ifnotdefVar'.$Var->get('key').'End##/isU';
// 			$file	= preg_replace($pattern, '', $file);
// 		}

// 		$pattern = '/((?:\r\n|\r|\n|^))[ \t]*##if(def|notdef)Var\w+(Start|End)##(?:[ \t]|[^\n\r#])*(?:\r\n|\r|\n|$)/isU';
// 		$file	= preg_replace($pattern, '$1', $file);

// 		$pattern = '/##if(def|notdef)Var\w+[Start|End]##/isU';
// 		$file	= preg_replace($pattern, '', $file);


		/**
		$file = str_replace("##name##", $name, $file);
		$file = str_replace("##Name##", $UName, $file);
		$file = str_replace("##key##", $key, $file);
		$file = str_replace("##hident##", $hident, $file);
		$file = str_replace("##primary##", $primary, $file);
		$file = str_replace("##menuhelper##", $menuhelper, $file);
		$file = str_replace("##routerswitch##", $routerswitch, $file);
		$file = str_replace("##syslanguage##", $syslanguage, $file);
		**/
		return $file;
	}


	public static function getcategorytask ()
	{
		return "
if (JRequest::getWord('task') == 'categoryedit') {
	".'$'."controller = 'category';
	JRequest::setVar('task', 'edit');
	JRequest::setVar('view', 'category');
	".'$'."task = 'edit';
}
		";
	}
}

/**
 * Utility class for categories
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
abstract class JHtmlJacc
{
	/**
	 * @var	array	Cached array of the category items.
	 */
	protected static $items = array();

	/**
	 * Returns an array of categories for the given extension.
	 *
	 * @param	string	The extension option.
	 * @param	array	An array of configuration options. By default, only published and unpulbished categories are returned.
	 *
	 * @return	array
	 */
	public static function categories($extension, $cat_id, $name="categories", $title="Select Category", $config = array('attributes'=>'class="inputbox"','filter.published' => array(0,1)))
	{

		$config	= (array) $config;
		$db		= JFactory::getDbo();

		jimport('joomla.database.query');
		$query	= new JQuery;

		$query->select('a.id, a.title, a.level');
		$query->from('#__jacc_categories AS a');
		$query->where('a.parent_id > 0');

		// Filter on extension.
		$query->where('extension = '.$db->quote($extension));

		$attributes = "";

		if (isset($config['attributes'])) {
			$attributes = $config['attributes'];
		}

		// Filter on the published state
		if (isset($config['filter.published'])) {
			if (is_numeric($config['filter.published'])) {
				$query->where('a.published = '.(int) $config['filter.published']);
			} else if (is_array($config['filter.published'])) {
				JArrayHelper::toInteger($config['filter.published']);
				$query->where('a.published IN ('.implode(',', $config['filter.published']).')');
			}
		}

		$query->order('a.lft');

		$db->setQuery($query);
		$items = $db->loadObjectList();

		// Assemble the list options.
		self::$items = array();
		self::$items[] = JHtml::_('select.option', '', JText::_($title));
		foreach ($items as &$item) {

			$item->title = str_repeat('- ', $item->level - 1).$item->title;
			self::$items[] = JHtml::_('select.option', $item->id, $item->title);

		}

		return  JHtml::_('select.genericlist', self::$items, $name, $attributes, 'value', 'text', $cat_id, $name);
		//return self::$items;
	}
}