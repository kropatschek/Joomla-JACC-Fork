<?php
/**
* @version		$Id: view.text.php 96 2011-08-11 06:59:32Z michel $
* @package		Jacc
* @subpackage 	Views
* @copyright	Copyright (C) 2010, mliebler. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
//--No direct access
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');

class  JaccViewJacc   extends JView
{
	/**
	 * componenthelp view display method
	 *
	 * @return void
	 **/
	public $language = 'en-GB';

	/**
	 * componenthelp view display method
	 *
	 * @return void
	 **/
	protected $_default_language = 'en-GB';

	/**
	 * componenthelp view display method
	 *
	 * @return void
	 **/
	public function display($tpl = null)
	{

		$this->addTemplatePath(JPATH_COMPONENT.DS.'templates'.DS.'mvctriple');
		$db =  JFactory::getDBO();

		$config =JFactory::getConfig();
		$dbprefix= $config->getValue('config.dbprefix');
		$model= $this->getModel();

		//get the Component to create
		$item= $this->get('Item');
		$this->uses_categories =  $item->params->get('uses_categories');

		//Get Table and Template
		$mvcTable = $this->get('MvcTable');
		$mvcTemplate = $this->get('MvcTemplate');


		//init the strings that replaces the fields and fieldslist pattern
		$freplace = "\n";
		$fdetailsreplace = "\n";
		$fparamsreplace = "\n";
		$fdescreplace = "\n";
		$fsubdescreplace = "\n";

		$flistreplace = "\n";

		$time = $item->created;

		$tableFields = $model->getTableFields($mvcTable);

		$parsedFields = array();

		$allfields = $tableFields->get('all');
		$hidentField = $allfields['hident'];
		$this->hident = $hidentField->get('key');

		$primaryField = $allfields['primary'];
		$this->primary = $primaryField->get('key');

		switch($mvcTemplate) {
				case 'table':
					$parsedFields = $tableFields->get('all');
					break;
				case 'tmpl_site':
					$parsedFields = $tableFields->get('list');
					break;
				case 'modelplural':
					$parsedFields = $tableFields->get('list');
					$foreignFields = $model->getTableFields('foreigns');
					$this->foreignFields = $foreignFields;
				case 'tmpl_admin_edit' :
					$this->formfield = $tableFields->get('groups');
					break;
				case 'language' :
				case 'xmlmodel' :
					$this->formfield = $tableFields->get('all');
					break;
				case 'tmpl_admin_default' :
					$parsedFields = $tableFields->get('list');
					break;
				default: $freplace .='';
		}

		foreach ($parsedFields as $field) {
			$this->field = $field;

			switch($mvcTemplate) {
				case 'table':
					if (!$field->get('additional')) {
						$prim = $field->get('prim', false) ? '- Primary Key' : '';
						$default = $field->get('default') ? '"'.$field->get('default').'"' : 'null' ;
						$freplace .= ''.
								'   /** @var '.$field->get('fieldtype').' '.$field->get('key').$prim."  **/\n".
								'   public $'.$field->get('key').' = '.$default.';'."\n\n";
					}
					break;
				case 'modelplural':
					$freplace .= $this->replace_field($field, 'modelpluralrow');
					//$foreign = $field->get('foreign');
					//$foreigntext = $field->get('foreigntext');
					if (array_key_exists ($field->get('key'), $foreignFields))
					{
						$foreign = $foreignFields[$field->get('key')];
						$freplace .= "\t\t\t\t'" . $foreign->get('name') . "_" . $foreign->get('hident') . "',\n";
						$flistreplace .= "\t\t\$query->select('" . "ft" . JaccHelper::acronym(substr($foreign->get('table'), 3), '_') . "." . $foreign->get('hident') . " AS " . $foreign->get('name') . "_" . $foreign->get('hident') . "');\n";
						$flistreplace .= "\t\t\$query->join('LEFT', '" . $foreign->get('table') . " AS " . "ft" . JaccHelper::acronym(substr($foreign->get('table'), 3), '_') . " ON " . "ft" . JaccHelper::acronym(substr($foreign->get('table'), 3), '_') . "." . $foreign->get('primary') . " = a." . strtolower($field->get('key')) ."');\n";
						$flistreplace .= "\n";
					}

// 					if (is_object($foreign)) {
// 						$freplace .= "\t\t\t\t'" . $foreign->name . "_" . $foreign->hident . "',\n";
// 						$flistreplace .= $foreigntext;
// 						$flistreplace .= "\n\n".print_r($this ,true);
// 						$flistreplace .= "\t\t\$query->select('" . JaccHelper::acronym(substr($foreign->table, 3), '_') . "." . $foreign->hident . " AS " . $foreign->name . "_" . $foreign->hident . "');\n";
// 						$flistreplace .= "\t\t\$query->join('LEFT', '" . $foreign->table . " AS " . JaccHelper::acronym(substr($foreign->table, 3), '_') . " ON " . JaccHelper::acronym(substr($foreign->table, 3), '_') . "." . $foreign->primary . " = a." . strtolower($field->get('key')) ."');\n";
// 						$flistreplace .= "\n";
// 					}
					break;
				case 'tmpl_site' :
					$freplace .= $this->replace_field($field, 'tmpl_site_row');
					break;
				case 'tmpl_admin_default' :
					// Todo JFile::exists not perfect
					if ($field->get('key') == $this->hident)
					{
						$freplace .= $this->replace_field($field, 'tmpl_admin_default_head_hident');
					}
					elseif (JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.'/templates/mvctriple/default_tmpl_admin_default_head_'.$field->get('key').'.php'))
					{
						$freplace .= $this->replace_field($field, 'tmpl_admin_default_head_'.$field->get('key'));
					}
					else
					{
						$freplace .= $this->replace_field($field, 'tmpl_admin_default_head');
					}

					if ($field->get('key') == $this->hident)
					{
						$flistreplace .= $this->replace_field($field, 'tmpl_admin_default_item_hident');
					}
					elseif (JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.'/templates/mvctriple/default_tmpl_admin_default_item_'.$field->get('key').'.php'))
					{
						$flistreplace .= $this->replace_field($field, 'tmpl_admin_default_item_'.$field->get('key'));
					}
					else
					{
						$flistreplace .= $this->replace_field($field, 'tmpl_admin_default_item');
					}
					break;
				default:$freplace .='';
			}
		}

		$com_component = $item->name;
		$date = JFactory::getDate();

		//last part of table name as (lowercase) name
		$name = substr(strrchr($mvcTable, '_'), 1);
		$name = InflectorHelper::singularize($name);
		$namePlural = InflectorHelper::pluralize($name);

		if ($name == $namePlural)
		{
			$extra = 's';
		}
		else
		{
			$extra = '';
		}

		//Component Name as first part of camel case class names
		$ComponentName = ucfirst(strtolower(str_replace('com_', '', $com_component)));

		//Replace the patterns
		$file = $this->loadTemplate($mvcTemplate);
		$file = str_replace("##fields##", $freplace, $file);
		$file = str_replace("##fieldslist##", $flistreplace, $file);
		$file = str_replace("##component##", strtolower($ComponentName), $file);
		$file = str_replace("##Component##", $ComponentName, $file);
		$file = str_replace("##COMPONENT##", strtoupper($ComponentName), $file);

		$file = str_replace("##date##", $date->toFormat(), $file);
		$file = str_replace("##com_component##", $com_component, $file);
		$file = str_replace("##COM_COMPONENT##", strtoupper($com_component), $file);

		if (empty($this->hident))
		{
			$file = str_replace("##title##", $this->primary, $file);
		}
		else
		{
			$file = str_replace("##title##", $this->hident, $file);
		}

		$file = str_replace("##name##", strtolower($name), $file);
		$file = str_replace("##Name##", ucfirst($name), $file);
		$file = str_replace("##NAME##", strtoupper($name), $file);
		$file = str_replace("##nameplural##", strtolower($namePlural), $file);
		$file = str_replace("##Nameplural##", ucfirst($namePlural), $file);
		$file = str_replace("##NAMEPLURAL##", strtoupper($namePlural), $file);
		$file = str_replace("##extra##", strtolower($extra), $file);
		$file = str_replace("##Extra##", ucfirst($extra), $file);
		$file = str_replace("##EXTRA##", strtoupper($extra), $file);

		//$file = str_replace("##fields##", $freplace, $file);
		//$file = str_replace("##fieldslist##", $flistreplace, $file);
		$file = str_replace("##primary##", $this->primary, $file);
		$file = str_replace("##time##", $time, $file);
		$file = str_replace("##codestart##", '<?php', $file);
		$file = str_replace("##codeend##", '?>', $file);
		$file = str_replace("##table##", $mvcTable, $file);

		//remove unneeded code parts
		$deleteList =  $tableFields->get('delete');


		foreach ($deleteList as $field) {
			//(?:\r\n|\r|\n|^)[ \t]*##ifdefFieldfeaturedStart##.*##ifdefFieldfeaturedEnd##(?:[ \t]|[^\n\r])*
			$pattern = '/(?:\r\n|\r|\n|^)[ \t]*##ifdefField'.$field.'Start##.*##ifdefField'.$field.'End##(?:[ \t]|[^\n\r])*/isU';
			$file	= preg_replace($pattern, '', $file);
			$pattern = '/##ifdefField'.$field.'Start##.*##ifdefField'.$field.'End##/isU';
			$file	= preg_replace($pattern, '', $file);
		}

		// TODO ????
		$pattern = '/##ifnotdefField'.$field.'Start##.*##ifnotdefField'.$field.'End##/isU';
		$allFields = $tableFields->get('all');
		foreach ($allFields  as $field) {
			$pattern = '/(?:\r\n|\r|\n|^)[ \t]*##ifnotdefField'.$field->get('key').'Start##.*##ifnotdefField'.$field->get('key').'End##(?:[ \t]|[^\n\r])*/isU';
			$file	= preg_replace($pattern, '', $file);
			$pattern = '/##ifnotdefField'.$field->get('key').'Start##.*##ifnotdefField'.$field->get('key').'End##/isU';
			$file	= preg_replace($pattern, '', $file);
		}

		//$pattern = '/\s+##ifdefField.*[Start|End]##+?/isU';
		$pattern = '/((?:\r\n|\r|\n|^))[ \t]*##if(def|notdef)Field\w*(Start|End)##(?:[ \t]|[^\n\r#])*(?:\r\n|\r|\n|$)/isU';
		$file	= preg_replace($pattern, '$1', $file);

		$pattern = '/##if(def|notdef)Field\w*[Start|End]##/isU';
		$file	= preg_replace($pattern, '', $file);

		//$file = str_replace("(?:\r\n|\r|\n)", "\n", $file);

		if (JRequest::getVar('mode') == 'return') {
			return $file;
		}
		while (@ob_end_clean());

		//Begin writing headers
		header("Cache-Control: max-age=60");
		header("Cache-Control: private");
		header("Content-Description: File Transfer");

		//Use the switch-generated Content-Type
		header("Content-Type: text/plain");

		//Force the download
		header("Content-Disposition: attachment; filename=\"".strtolower(JRequest::getVar('name')).".php\"");
		header("Content-Transfer-Encoding: binary");

		print $file;
	}

	// function
	private function replace_field($field, $tmpl)
	{
		$file = $this->loadTemplate($tmpl);
		$lField = strtolower($field->get('key'));
		$FUField = ucfirst($field->get('key'));
		$UField = strtoupper($field->get('key'));

		/*
		if ($tmpl == 'templlist' and $field->get('key') == $this->hident) {
			$file = str_replace("##codestart##", '<a href="##codestart## echo $link; ##codeend##">##codestart##', $file);
			$file = str_replace("##field## ##codeend##", $field->get('key').' ?></a>', $file);
		} elseif ($tmpl == 'templlist' and strtolower($field->get('key')) == 'ordering') {

			$file = str_replace("\$row->##field##", "\$ordering", $file);

		} elseif ($tmpl == 'templlist' and strtolower($field->get('key')) == 'published') {

			$file = str_replace("\$row->##field##", "\$published", $file);

		} else {

			$file = str_replace("##field##", $field->get('key'), $file);

		}
		*/
		// TODO not shure why this is necessary
		$file = str_replace("##codestart##", '<?php', $file);
		$file = str_replace("##codeend##", '?>', $file);

		$file = str_replace("##field##", $lField, $file);
		$file = str_replace("##FIELD##", $UField, $file);
		return str_replace("##Field##", $FUField, $file);
	}

}// class

