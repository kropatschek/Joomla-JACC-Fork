<?php
/**
 * @version		$Id: controller.php 92 2011-08-10 15:11:25Z michel $
 * @author	   	mliebler
 * @package     Jacc
 * @subpackage  Controllers
 * @copyright  	Copyright (C) 2010, mliebler. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Jacc Standard Controller
 *
 * @package Jacc
 * @subpackage Controllers
 */
class JaccController extends JController
{

	protected $_viewname = 'jacc';
	protected $_mainmodel = 'jacc';
	protected $_itemname = 'Component';


	/**
	 * Constructor
	 */

	public function __construct($config = array ())
	{

		parent::__construct($config);

		if (isset($config['viewname'])) $this->_viewname = $config['viewname'];
		if (isset($config['mainmodel'])) $this->_mainmodel = $config['mainmodel'];
		JRequest::setVar('view', $this->_viewname);

	}

	/*
	 * Overloaded Method display
	 */
	function display($cachable = false, $urlparams = false)
	{
		switch($this->getTask())
		{
			case 'add'     :
				{
					JRequest::setVar( 'hidemainmenu', 1 );
					JRequest::setVar( 'layout', 'form'  );
					JRequest::setVar( 'view', $this->_viewname);
					JRequest::setVar( 'edit', false );

				} break;
			case 'edit'    :
				{
					JRequest::setVar( 'hidemainmenu', 1 );
					JRequest::setVar( 'layout', 'form'  );
					JRequest::setVar( 'view', $this->_viewname);
					JRequest::setVar( 'edit', true );

				} break;
		}
		parent::display();
	}

	function export()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.file');

		$link = 'index.php?option=com_jacc&view=jacc';
		$model = $this->getModel($this->_mainmodel);
		$item = $model->getItem();
		$export_dir = $item->params->get('export');
		if (!$export_dir) {
			$msg = JText::_('Please define export directory in the component details');
			$this->setRedirect($link, $msg);
			return;
		}
		if (!JFolder::exists(JPATH_SITE.DS.$export_dir)) {
			if (!JFolder::create(JPATH_SITE.DS.$export_dir)) {
				$msg = JText::_('Could not create '.JPATH_SITE.DS.$export_dir);
				$this->setRedirect($link, $msg);
				return;
			}
		}
		$zipdir = JPATH_COMPONENT.DS.'archives';
		$archive = $item->name.'-'.$item->version.'.zip';
		if (!JFile::exists($zipdir.DS.$archive)) {
			$msg = $archive. JText::_(' is not present');
			$this->setRedirect($link, $msg);
			return;
		}
		if (JArchive::extract($zipdir.DS.$archive, JPATH_SITE.DS.$export_dir )) {
			$msg = JText::_('Component successfully exported to '.JPATH_SITE.DS.$export_dir);
		} else {
			$msg = JText::_('Error while extracting the archive');
		}
		$this->setRedirect($link, $msg);

	}





	public function vremove()
	{
		// Check for request forgeries
		jimport('joomla.filesystem.file');
		JRequest::checkToken() or jexit('Invalid Token');
		$zipdir = JPATH_COMPONENT.DS.'archives';

		$model = $this->getModel();

		$vremove  = JRequest::getVar('vremove', null, 'post', 'string');
		if (JFile::exists($zipdir.DS.$vremove)) {
			JFile::delete($zipdir.DS.$vremove);
		}
		$link = 'index.php?option=com_jacc&view='.$this->_viewname.'&task=edit&cid[]='.$model->getId() ;
		$this->setRedirect($link);
	}

	/*
	 * Creates the archive from live component
	 */
	public function zipLiveComponent($model)
	{

		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.file');


		$item =  $model->getItem();


		//Create TempFolders
		if (JFolder::exists($model->getTempPath(true))) {
			JFolder::delete($model->getTempPath(true));
		}
		JFolder::create($model->getTempPath());

		//Copy the component
		if (JFolder::exists(JPATH_SITE.DS.'components'.DS.$item->name)) {
			JFolder::copy(JPATH_SITE.DS.'components'.DS.$item->name, $model->getTempPath(true).'site');
		}
		if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.$item->name)) {
			JFolder::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.$item->name, $model->getTempPath(true).'admin');
		} else {
			JFolder::delete($model->getTempPath());
			return -2;
		}
		//Create Language Folders
		if (!JFolder::exists($model->getTempPath().DS.'site'.DS.'language')) {
			JFolder::create($model->getTempPath().DS.'site'.DS.'language');
		}
		if (!JFolder::exists($model->getTempPath().DS.'admin'.DS.'language')) {
			JFolder::create($model->getTempPath().DS.'admin'.DS.'language');
		}
		//Find Language Files
		$langs_site = JLanguage::getKnownLanguages(JPATH_SITE);
		$langs_admin = JLanguage::getKnownLanguages(JPATH_ADMINISTRATOR);
		$langfiles_site = array();
		$langfiles_admin = array();




		foreach ($langs_site as $lang) {
			$langfiles_site  = array_merge(JFolder::files(JPATH_SITE.DS.'language'.DS.$lang['tag'], $item->name, true, true), $langfiles_site);
		}
		foreach ($langs_admin as $lang) {
			$langfiles_admin  = array_merge(JFolder::files(JPATH_ADMINISTRATOR.DS.'language'.DS.$lang['tag'], $item->name, true, true), $langfiles_admin  );
		}

		//Copy Language Files
		if (count($langfiles_site))
		foreach ($langfiles_site as $file) {
			JFile::copy($file, $model->getTempPath().DS.'site'.DS.'language'.DS.JFile::getName($file));
		}
		if (count($langfiles_admin))
		foreach ($langfiles_admin as $file) {
			JFile::copy($file, $model->getTempPath().DS.'admin'.DS.'language'.DS.JFile::getName($file));
		}
		//Move the manifest
		$manifest = $item->name.'.xml';
		JFile::move($model->getTempPath().DS.'admin'.DS.$manifest, $model->getTempPath().DS.$manifest);

		$vcpairs = $item->params->get('views');

		if(is_array($vcpairs) && count($vcpairs) ) {
			foreach($vcpairs as $options) {
				if(!trim($options['name'])) continue;
				$options['name'] = JFilterOutput::stringURLSafe($options['name']);
				//create the files
				$this->copyCVPair($options);
			}
		}

		$model->readinFiles();

		$model->buildArchiveContent();

		//create archive
		$model->createArchive();
		//delete tmp folder
		if (JFolder::exists(JPATH_SITE.DS.'tmp'.DS.'jctmp')) {
			JFolder::delete(JPATH_SITE.DS.'tmp'.DS.'jctmp');
		}

	}

	/*
	 * Creates the archive from the scratch
	 */
	public function zip()
	{

		require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jacc.php';
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.file');
		jimport('joomla.filtere.filteroutput');

		$db = JFactory::getDBO();

		$zipdir = JPATH_COMPONENT.DS.'archives';

		$model = $this->getModel($this->_mainmodel);

		//Get Model
		$item = $model->getItem();

		$archive = $item->name.'-'.$item->version.'.zip';

		$model->addPath('archive',$zipdir.DS.$archive);

		$mvcdyn_dir = JPATH_COMPONENT.DS.'templates'.DS.'mvctriple'.DS;

		$elements_dir = JPATH_COMPONENT.DS.'templates'.DS.'elements'.DS;

		//some basic variables to replace the template patterns
		//now
		$date = JFactory::getDate();

		$com_component = $item->name;
		$Ucom_component = strtoupper($com_component);
		JRequest::setVar('component', $com_component );

		//Component name for camel case functions
		$component = ucfirst(strtolower(str_replace('com_', '', $com_component )));

		//lower Component name
		$lcomponent = strtolower(str_replace('com_', '', $com_component ));

		$model->addRenaming($lcomponent,'#component#');

		//User wants to create archive from installed component
		if ($item->use >1 ) {
			return $this->zipLiveComponent(&$model) ;
		}

		//get db prefix
		$config =JFactory::getConfig();

		$dbprefix= $config->getValue('config.dbprefix');

		//categories included?
		$uses_categories =  $item->params->get('uses_categories');

		if (!$uses_categories)
		{
			$model->addExcludes(array('.svn', 'CVS','category','categories','category.php','categories.php','tablenested.php'));
		}

		//Create temp folder
		if (JFolder::exists($model->getTempPath()))
		{
			JFolder::delete($model->getTempPath());
		}

		// copy Basic MVC to tmp/jctmp
		JFolder::copy(JPATH_COMPONENT.DS.'templates'.DS.'mvcbase', $model->getTempPath());


		//the text view to read the mvc triple templates
		$view = $this->getView( $this->_viewname, 'text');

		//initiate variables
		$sqlstring = "";
		$dropsqlstring = "";
		$mainlink = "";
		$submenu = "";
		$addsubmenu = "";
		$helpersubmenu = "";
		$menuhelper = "";
		$accesshelper = "";
		$defaultview = "";
		$extensions = "";
		$routerswitch = "";
		$languages	= JLanguageHelper::getLanguages();


		$filesCopy = array();
		//the view/model/controller name of the first table
		$firstName  = "";

		$adminlanguagefile = array();
		$adminsyslanguagefile = array();
		$sitelanguagefile = array();

		$adminsyslanguage = array();
		$adminlanguage = array();
		$sitelanguage = array();


		foreach ($languages as $language)
		{
			$adminsyslanguage[''][$language->lang_code] = ucfirst($component);
			$adminsyslanguage['XML_DESCRIPTION'][$language->lang_code] = "Component for " . ucfirst($component) . " management";
		}

		foreach ($languages as $language)
		{
			$adminlanguage[''][$language->lang_code] = ucfirst($component);
			$adminlanguage['XML_DESCRIPTION'][$language->lang_code] = "Component for " . ucfirst($component) . " management";
		}

		$componenthelper = "";

		$menu_tmpl = file_get_contents($elements_dir .'menu.php');
		$categoriesmenu_tmpl = file_get_contents($elements_dir .'categoriesmenu.php');
		$accessxml_tmpl = file_get_contents($elements_dir .'accessxml_section.php');
		$categoryaccessxml_tmpl = file_get_contents($elements_dir .'accessxml_category.php');
		$parentaccessxml_tmpl = file_get_contents($elements_dir .'accessxml_parent.php');

		$router_tmpl = file_get_contents($elements_dir .'router.php');

		if (count($item->tables)) {
			$firstName = substr(strrchr($item->firsttable, '_'), 1);
			$firstName = InflectorHelper::singularize($firstName);
			$firstNamePlural = InflectorHelper::pluralize($firstName);

			if ($firstName == $firstNamePlural)
			{
				$firstExtra = 's';
			}
			else
			{
				$firstExtra = '';
			}


			//TODO: to delete
			if ($uses_categories) {
				//need a submenu for categories
				//TODO: check if catid or category_id in table
				//$addsubmenu =  "		  <menu  link=\"option==\"$com_component."&amp;view=categories\">Categories</menu>\n";
				//$options = array('firstname'=>'categories');
				//$helpersubmenu = JaccHelper::_replace($menu_tmpl, $item, $options );
				//$adminsyslanguage[''] .= "CATEGORIES=\"Categories\"\n";
			}


			foreach ($item->tables as $table) {

				//Get the fields
				$fields = $db->getTableFields($table, false);
				$model->setTableFields($table, JaccHelper::getSpecialFields($fields[$table]));
			}

			//Check tables for relations - foreign keys etc
			$model->checkTables();

			$foreignkeys = $model->getTableFields('foreigns');

			$ftmpl = file_get_contents($mvcdyn_dir.'default_modelfield.php');

			foreach ($foreignkeys as $field) {
				// create formfield
				$data = JaccHelper::_replace($ftmpl, $item, (array)  $field);
				file_put_contents($model->getTempPath(true).'admin'.DS.'models'.DS.'fields'.DS.$lcomponent.$field->get('name').'.php', $data);

			}

			$hasnoImages = true;

			foreach ($item->tables as $table) {

				if($model->TableHas($table, 'image')) {
					$hasnoImages  = false;
				}

				//last part of table name as class name
				$name = substr(strrchr($table, '_'), 1);
				$name = InflectorHelper::singularize($name);
				$FUName = ucfirst($name);
				$UName = strtoupper($name);

				$namePlural = InflectorHelper::pluralize($name);
				$FUNamePlural = ucfirst($namePlural);
				$UNamePlural = strtoupper($namePlural);

				if ($name == $namePlural)
				{
					$extra = 's';
				}
				else
				{
					$extra = '';
				}
				$UExtra = strtoupper($extra);

				//the table name used as last part of camel case class names
				$model->setMvcTable($table);

				//the install sql
				$sqlstring .=  JaccHelper::export_table_structure($table);

				//uninstall sql
				//TODO: just for testing
				//$dropsqlstring .= 'DROP TABLE IF EXISTS '.str_replace($dbprefix, '#_', $table).";\n";

				//create temp folders
				JaccHelper::createFolder($model->getTempPath(true).'site'.DS.'views'.DS.$name);
				JaccHelper::createFolder($model->getTempPath(true).'site'.DS.'views'.DS.$name.DS.'tmpl');
				// TODO: add plural function instead
				JaccHelper::createFolder($model->getTempPath(true).'site'.DS.'views'.DS.$namePlural.$extra);
				JaccHelper::createFolder($model->getTempPath(true).'site'.DS.'views'.DS.$namePlural.$extra.DS.'tmpl');
				JaccHelper::createFolder($model->getTempPath(true).'admin'.DS.'views'.DS.$name);
				JaccHelper::createFolder($model->getTempPath(true).'admin'.DS.'views'.DS.$name.DS.'tmpl');
				// TODO: add plural function instead
				JaccHelper::createFolder($model->getTempPath(true).'admin'.DS.'views'.DS.$namePlural.$extra);
				JaccHelper::createFolder($model->getTempPath(true).'admin'.DS.'views'.DS.$namePlural.$extra.DS.'tmpl');
				JaccHelper::createFolder($model->getTempPath(true).'admin'.DS.'sql');

				JaccHelper::createFolder($model->getTempPath(true).'media'.DS.'js'.DS.$name);


				foreach ($languages as $language)
				{
					JaccHelper::createFolder($model->getTempPath(true).'site'.DS.'language'.DS.$language->lang_code);
					JaccHelper::createFolder($model->getTempPath(true).'admin'.DS.'language'.DS.$language->lang_code);
				}

				//First table as main triple
				if (!$mainlink) {
					$mainlink = 'option='.$com_component.'&amp;view='.$firstNamePlural.$firstExtra;
					$defaultview = $firstNamePlural.$firstExtra;
					$defaultviewsingular = $firstName;
				}

				$options = array(
					'firstname' => $name,
					'firstnameplural' => $namePlural,
					'firstextra' => $extra
				);


				$menuhelper .= JaccHelper::_replace($menu_tmpl, $item, $options);

				if ($model->TableHas($table, 'parent_id'))
				{
					$accesshelper .= JaccHelper::_replace($parentaccessxml_tmpl, $item, $options);
				}
				else
				{
					$accesshelper .= JaccHelper::_replace($accessxml_tmpl, $item, $options);
				}

				if ($model->TableHas($table, 'category_id') )
				{
					$menuhelper .= JaccHelper::_replace($categoriesmenu_tmpl, $item, $options);
					$accesshelper .= JaccHelper::_replace($categoryaccessxml_tmpl, $item, $options);
				}



				$routerswitch .= JaccHelper::_replace($router_tmpl, $item, $options);


				//Submenu for admin, if more than one table selected
				if (count($item->tables) >1)
				{
					$submenu .=  "\t\t\t\t<menu link=\"option=".$com_component."&amp;view=".$namePlural.$extra."\" view=\"".$namePlural.$extra."\" img=\"class:".$lcomponent."-".$namePlural.$extra."\" alt=\"".$component."/".$FUNamePlural.$extra."\" >".$com_component."_".$namePlural.$extra."</menu>\n";
				}

				if ($model->TableHas($table, 'category_id') )
				{
					$submenu .= "\t\t\t\t<menu link=\"option=com_categories&amp;extension=".$com_component.".".$namePlural.$extra."\" view=\"categories\" img=\"class:".$lcomponent."-".$namePlural.$extra."-cat\" alt=\"".$component."/".$FUName." Categories\" >".$com_component."_".$name."_categories</menu>\n";
				}

				foreach ($languages as $language)
				{
					$adminsyslanguage[$UName][$language->lang_code] = $FUName;
					$adminlanguage[$UNamePlural . $UExtra . '_SUBMENU'][$language->lang_code] = $FUNamePlural;//no extra
					$adminlanguage[$UNamePlural . $UExtra . '_MANAGER'][$language->lang_code] = ucfirst($component) . " Manager: " . $FUNamePlural;
					$adminlanguage[$UName . '_MANAGER'][$language->lang_code] = ucfirst($component)." Manager: " . $FUName;
					$adminlanguage[$UName . '_MANAGER_NEW'][$language->lang_code] = ucfirst($component)." Manager: Add New " . $FUName;
					$adminlanguage[$UName . '_MANAGER_EDIT'][$language->lang_code] = ucfirst($component)." Manager: Edit " . $FUName;
					$adminlanguage[$UName . '_DETAILS'][$language->lang_code] = "Details";
					$adminlanguage[$UName . '_NEW'][$language->lang_code] = "New " . $FUName;
					$adminlanguage[$UName . '_EDIT'][$language->lang_code] = "Edit " . $FUName;
					$adminlanguage[$UName . '_BATCH_OPTIONS'][$language->lang_code] = "";
					$adminlanguage[$UName . '_BATCH_TIP'][$language->lang_code] = "";
					if (count($item->tables) >1)
					{
						$adminsyslanguage[$UNamePlural . $UExtra][$language->lang_code] = $FUNamePlural;

					}
					if ($model->TableHas($table, 'category_id') )
					{
						$adminsyslanguage[$UName . '_CATEGORIES'][$language->lang_code] = $FUNname . " Categories";
						$adminlanguage[$UName . '_CATEGORIES_SUBMENU'][$language->lang_code] = $FUName . " Categories";
						// Name for the com_category component
						$adminlanguage[$UNamePlural . $extra][$language->lang_code] = $FUNamePlural;
					}
				}

				JRequest::setVar('type', 'language');
				$model->setMvcTemplate('language');
				$view->setModel($model, true);
				foreach ($languages as $language)
				{
					$view->language = $language->lang_code;
					$adminlanguagefile[$language->lang_code] .= $view->display();
					$sitelanguagefile[$language->lang_code] .= $view->display();
				}

				//Create an array for the MVC elements
				$mvcfiles = array(
					'model'=>array('folders'=>array('admin'.DS.'models'),'ext'=>'php','name'=>$name),
					'modelplural'=>array('folders'=>array('admin'.DS.'models'),'ext'=>'php','name'=>$namePlural.$extra),
					'modelfrontend'=>array('folders'=>array('site'.DS.'models'),'ext'=>'php','name'=>$name),
					'xmlmodel'=>array('folders'=>array('admin'.DS.'models'.DS.'forms'),'ext'=>'xml','name'=>$name),
					//'language'=>array('folders'=>array('admin'.DS.'language'),'ext'=>'ini','name'=>'en-GB.'.$com_component),
					//'language'=>array('folders'=>array('admin'.DS.'language'),'ext'=>'ini','name'=>'de-DE.'.$com_component),
					//'element'=>array('folders'=>array('admin'.DS.'elements'),'ext'=>'php','name'=>$name),
					'table'=>array('folders'=>array('admin'.DS.'tables'),'ext'=>'php','name'=>$name),
					'controller'=>array('folders'=>array('admin'.DS.'controllers'),'ext'=>'php','name'=>$name),
					'controllerplural'=>array('folders'=>array('admin'.DS.'controllers'),'ext'=>'php','name'=>$namePlural.$extra),
					'viewfrontend'=>array('folders'=>array('site'.DS.'views'.DS.$name),'ext'=>'php','name'=>'view.html'),
					'view'=>array('folders'=>array('admin'.DS.'views'.DS.$name),'ext'=>'php','name'=>'view.html'),
					'viewplural'=>array('folders'=>array('admin'.DS.'views'.DS.$namePlural.$extra),'ext'=>'php','name'=>'view.html'),
					'metadata'=>array('folders'=>array('site'.DS.'views'.DS.$name),'ext'=>'xml','name'=>'metadata'),
					'tmpl_site'=>array('folders'=>array('site'.DS.'views'.DS.$name.DS.'tmpl'),'ext'=>'php','name'=>'default'),
					'tmpl_admin_edit'=>array('folders'=>array('admin'.DS.'views'.DS.$name.DS.'tmpl'),'ext'=>'php','name'=>'edit'),
					'tmpl_admin_edit_submitbutton_js'=>array('folders'=>array('media'.DS.'js'.DS.$name),'ext'=>'js','name'=>'submitbutton'),
					'tmpl_admin_default'=>array('folders'=>array('admin'.DS.'views'.DS.$namePlural.$extra.DS.'tmpl'),'ext'=>'php','name'=>'default'),
					'tmpl_admin_default_batch'=>array('folders'=>array('admin'.DS.'views'.DS.$namePlural.$extra.DS.'tmpl'),'ext'=>'php','name'=>'default_batch'),
					'defaultxml'=>array('folders'=>array('site'.DS.'views'.DS.$name.DS.'tmpl'),'ext'=>'xml','name'=>'default'),
					//'accessxml'=>array('folders'=>array('admin'),'ext'=>'xml','name'=>'access')
					//'tmpl_admin_'=>array('folders'=>array('admin'.DS.'views'.DS.$name.DS.'tmpl'),'ext'=>'php','name'=>'form')
				);



				if ($model->TableHas($table, 'parent_id'))
				{
					JRequest::setVar('type', 'helpers_component_parent');
					$model->setMvcTemplate('helpers_component_parent');
					$view->setModel($model, true);
					$componenthelper .= $view->display();

					$mvcfiles[model_field_categoryedit] = array('folders'=>array('admin'.DS.'models'.DS.'fields'),'ext'=>'php','name'=>$name.'edit');
					$mvcfiles[helpers_html_category] = array('folders'=>array('admin'.DS.'helpers'.DS.'html'),'ext'=>'php','name'=>$name);

				}

				//If there is no category_id present, we use a simple list view
				if (!$model->TableHas($table, 'category_id') ) {
					$mvcfiles['viewlist'] =  array('folders'=>array('site'.DS.'views'.DS.$name.'list'),'ext'=>'php','name'=>'view.html');
					$mvcfiles['modellist'] =  array('folders'=>array('site'.DS.'models'),'ext'=>'php','name'=>$name.'list');
					$mvcfiles['listtmpl'] = array('folders'=>array('site'.DS.'views'.DS.$name.'list'.DS.'tmpl'),'ext'=>'php','name'=>'default');
					$mvcfiles['defaultlistxml']=array('folders'=>array('site'.DS.'views'.DS.$name.'list'.DS.'tmpl'),'ext'=>'xml','name'=>'default');
					JaccHelper::createFolder($model->getTempPath(true).'site'.DS.'views'.DS.$name.'list');
					JaccHelper::createFolder($model->getTempPath(true).'site'.DS.'views'.DS.$name.'list'.DS.'tmpl');
				} else {
					$extensions[] = $name;
				}

				//Read the template for each mvc element
				foreach ($mvcfiles as $template => $options) {
					JRequest::setVar('type', $template);
					$model->setMvcTemplate($template);
					$view->setModel($model, true);

					$data = $view->display();

					foreach ($options['folders'] as $folder) {
						file_put_contents($model->getTempPath(true).$folder.DS.$options['name'].'.'.$options['ext'], $data);
					}
				}
			}
		}
		if($hasnoImages) {
			$model->addExcludes(array('imageup'));
		}


		//additional user defined Controller/View Pairs, that do not relate to a table

		$vcpairs = $item->params->get('views');

		if(is_array($vcpairs) && count($vcpairs) ) {
			foreach($vcpairs as $options) {
				if(!trim($options['name'])) continue;
				$options['name'] = JFilterOutput::stringURLSafe($options['name']);
				$replaceoptions = array('firstname' => $options['name']);
				//create the files
				$this->copyCVPair($options);
				//Make a submenu, if the pair is created for backend
				if($options['option'] == 'backend' || $options['option'] == 'both' ) {
					$menuhelper .= JaccHelper::_replace($menu_tmpl, $item, $replaceoptions);
					$addsubmenu .=  "		  <menu  link=\"option=".$com_component."&amp;view=".$options['name']."\">".ucfirst($options['name'])."</menu>\n";
					$routerswitch .= JaccHelper::_replace($router_tmpl, $item, $replaceoptions);
					foreach ($languages as $language)
					{
						$adminsyslanguage[strtoupper($options['name'])][$language->lang_code] = ucfirst($options['name']);
					}
				}
			}
		}



		//TODO: to delete
		//write the extension.xml
		//$extensionxmlfile = file_get_contents($model->getTempPath(true).'admin'.DS.'elements'.DS.'extensions.xml');
		//file_put_contents($model->getTempPath(true).'admin'.DS.'elements'.DS.'extensions.xml', str_replace('##extensionsxml##', $model->getExtensionXml(), $extensionxmlfile));

		//Get Install sql for categories if required
		//TODO: to delete
		//if ($uses_categories) $sqlstring .=  JaccHelper::getCatsql($lcomponent);
		//TODO: change to delete rows in #__categories
		//if ($uses_categories) $dropsqlstring .= 'DROP TABLE IF EXISTS #__'.$lcomponent."_categories;\n";

		//write install.mysql.sql and uninstall.mysql.sql'
		file_put_contents($model->getTempPath(true).'admin'.DS.'sql'.DS.'install.mysql.sql', $sqlstring);
		file_put_contents($model->getTempPath(true).'admin'.DS.'sql'.DS.'uninstall.mysql.sql', $dropsqlstring);

		$submenu .= $addsubmenu;

		foreach ($languages as $language)
		{
			foreach ($sitelanguage as $languagekey => $value)
			{
				$sitelanguagefile[$language->lang_code] .= $Ucom_component . "_" . (empty($languagekey) ? "" : "_" . $languagekey) . "=\"" . $value[$language->lang_code] . "\"\n";
			}

			foreach ($adminlanguage as $languagekey => $value)
			{
				$adminlanguagefile[$language->lang_code] .= $Ucom_component . (empty($languagekey) ? "" : "_" . $languagekey) . "=\"" . $value[$language->lang_code] . "\"\n";
			}

			foreach ($adminsyslanguage as $languagekey => $value)
			{
				$adminsyslanguagefile[$language->lang_code] .= $Ucom_component . (empty($languagekey) ? "" : "_" . $languagekey) . "=\"" . $value[$language->lang_code] . "\"\n";
			}

			$languages_site .= "\t\t<language tag=\"".$language->lang_code."\">language".DS.$language->lang_code.DS.$language->lang_code.".".$com_component.".ini</language>\n";
			$languages_admin .= "\t\t\t<language tag=\"".$language->lang_code."\">language".DS.$language->lang_code.DS.$language->lang_code.".".$com_component.".ini</language>\n";
			$languages_admin .= "\t\t\t<language tag=\"".$language->lang_code."\">language".DS.$language->lang_code.DS.$language->lang_code.".".$com_component.".sys.ini</language>\n";

			file_put_contents($model->getTempPath(true).'site'.DS.'language'.DS.$language->lang_code.DS.$language->lang_code.'.'.$com_component.'.ini', $sitelanguagefile[$language->lang_code]);
			file_put_contents($model->getTempPath(true).'admin'.DS.'language'.DS.$language->lang_code.DS.$language->lang_code.'.'.$com_component.'.ini', $adminlanguagefile[$language->lang_code]);
			file_put_contents($model->getTempPath(true).'admin'.DS.'language'.DS.$language->lang_code.DS.$language->lang_code.'.'.$com_component.'.sys.ini', $adminsyslanguagefile[$language->lang_code]);
		}

		//Read the template for each mvc element
		$options = array('menuhelper' => $menuhelper.$helpersubmenu,
						'submenu' => $submenu,
						'accesshelper' => $accesshelper,
						'routerswitch' => $routerswitch,
						//'syslanguage' => $adminsyslanguage[''],
						//'language' => $language,
						'languages_site' => $languages_site,
						'languages_admin' => $languages_admin,
						'defaultview' => $defaultview,
						'defaultviewsingular' => $defaultviewsingular,
						'componenthelper' => $componenthelper,
						'firstname' =>$firstName,
						'firstname' =>$firstName,
						'firstnameplural' => $firstNamePlural,
						'firstextra' => $firstExtra,
					);

		$model->readinFiles();

		$model->customizeFiles($options);

		//Delete old archive if exists
		if (JFile::exists($zipdir.DS.$archive)) {
			JFile::delete($zipdir.DS.$archive);
		}
		$model->readinFiles();

		$model->buildArchiveContent();

		//create archive
		$model->createArchive();
		//delete tmp folder
		if (JFolder::exists(JPATH_SITE.DS.'tmp'.DS.'jctmp')) {
			JFolder::delete(JPATH_SITE.DS.'tmp'.DS.'jctmp');
		}
	}


	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $item
	 * @param unknown_type $options
	 */

	function copyCVPair($options) {

		$model = $this->getModel($this->_mainmodel);

		$item = $model->getItem();
		if(!trim($options['name'])) return;


		$name = JFilterOutput::stringURLSafe($options['name']);

		$admin = array();
		$site = array();
		if($options['option'] == 'backend' || $options['option'] == 'both' ) {
			if(!JFolder::exists($model->getTempPath(true).'admin'.DS.'views'.DS.$name)) {
				JFolder::copy(JPATH_COMPONENT.DS.'templates'.DS.'vcpair'.DS.'admin'.DS.'#name#', $model->getTempPath(true).'admin'.DS.'views'.DS.$name,'',true);
				$admin = JFolder::files($model->getTempPath(true).'admin'.DS.'views'.DS.$name, '.', true, true);
			}
			if(!JFile::exists($model->getTempPath(true).'admin'.DS.'controllers'.DS.$name.'.php')) {
				JFile::copy(JPATH_COMPONENT.DS.'templates'.DS.'vcpair'.DS.'admin'.DS.'#name#.php', $model->getTempPath(true).'admin'.DS.'controllers'.DS.$name.'.php');
			}

		}
		if($options['option'] == 'frontend' || $options['option'] == 'both' ) {
			if(!JFolder::exists($model->getTempPath(true).'site'.DS.'views'.DS.$name)) {
				JFolder::copy(JPATH_COMPONENT.DS.'templates'.DS.'vcpair'.DS.'site'.DS.'#name#', $model->getTempPath(true).'site'.DS.'views'.DS.$name,'',true);
				$site = JFolder::files($model->getTempPath(true).'site'.DS.'views'.DS.$name, '.', true, true);
			}
			if(!JFile::exists($model->getTempPath(true).'site'.DS.'controllers'.DS.$name.'.php')) {
				JFile::copy(JPATH_COMPONENT.DS.'templates'.DS.'vcpair'.DS.'site'.DS.'#name#.php', $model->getTempPath(true).'site'.DS.'controllers'.DS.$name.'.php');
			}
		}

		$files = array_merge($admin,$site);
		if($options['option'] == 'frontend' || $options['option'] == 'both' ) {
			$files[] =  $model->getTempPath(true).'site'.DS.'controllers'.DS.$name.'.php';
		}
		if($options['option'] == 'backend' || $options['option'] == 'both' ) {
			$files[] =  $model->getTempPath(true).'admin'.DS.'controllers'.DS.$name.'.php';
		}
		for ($i=0;$i<count($files);$i++) {
			$data = file_get_contents($files[$i]);
			$data = JaccHelper::_replace($data, $item , array('name'=>$name));
			file_put_contents($files[$i], $data);
		}
		return $files;
	}



	/**
	 *stores the item and returnss to previous page
	 *
	 */

	public function apply()
	{
		$this-> save();
	}

	/**
	 * stores the item
	 */
	public function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		$app = JFactory::getApplication('administrator');
		$db =  JFactory::getDBO();

		$post = JRequest::getVar('jform', array(), 'post', 'array');
		$cid = JRequest::getVar('cid', array (
		0
		), 'post', 'array');
		$post['id'] = (int) $cid[0];

		$post['tables'] = json_encode($post['tables']);
		$model = $this->getModel($this->_mainmodel);
		JRequest::setVar('mode', 'return');
		// Validate the posted data.
		$form	= $model->getForm();

		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}

		$post	= $model->validate($form, $post);

		// Check for validation errors.
		if ($post === false) {


			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				} else {
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}
			$link = 'index.php?option=com_jacc&view='.$this->_viewname.'&task=edit&cid[]='.$model->getId() ;
			$this->setRedirect($link, $msg);
			return;
		}

		if ($model->store($post)) {
			$msg = JText::_($this->_itemname .' Saved');
			JRequest::setVar("cid",array($model->getId()));
		} else {
			$msg = $model->getError();
		}

		switch ($this->getTask())
		{
			case 'apply':
				$link = 'index.php?option=com_jacc&view='.$this->_viewname.'&task=edit&cid[]='.$model->getId() ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_jacc&view='.$this->_viewname;
				break;
		}

		if ($this->zip($model) == -2) {
			$msg = JText::_('Could not save. Component backend not present');
			$this->setRedirect($link, $msg);
		} else {
			$this->setRedirect($link, $msg);
		}
	}


	/**
	 * remove an item
	 */
	function remove()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$db =  JFactory::getDBO();
		$cid = JRequest::getVar('cid', array (), 'post', 'array');
		JArrayHelper::toInteger($cid);
		$msg = JText::_($this->_itemname.' deleted');
		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a '.$this->_itemname.' to delete'));
		}
		$model = $this->getModel($this->_mainmodel);
		if (!$model->delete($cid)) {
			$msg = $model->getError();
		}
		$link = 'index.php?option=com_jacc&view='.$this->_viewname;
		$this->setRedirect($link, $msg);
	}


	/**
	 * publish an item
	 */
	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$cid = JRequest::getVar('cid', array (), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a '.$this->_itemname.' to publish'));
		}

		$model = $this->getModel($this->_mainmodel);
		if (!$model->publish($cid, 1)) {
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_jacc&view='.$this->_viewname);
	}

	/**
	 * unpublish an item
	 */
	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$cid = JRequest::getVar('cid', array (), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a '.$this->_itemname.' to unpublish'));
		}

		$model = $this->getModel($this->_mainmodel);
		if (!$model->publish($cid, 0)) {
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_jacc&view='.$this->_viewname);
	}
	/**
	 * cancel the current form
	 */

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');


		$this->setRedirect('index.php?option=com_jacc&view='.$this->_viewname);
	}

	/**
	 * move item down
	 */
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$model = $this->getModel($this->_mainmodel);
		$model->move(-1);

		$this->setRedirect('index.php?option=com_jacc&view='.$this->_viewname);
	}

	/**
	 * move item up
	 */

	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$model = $this->getModel($this->_mainmodel);
		$model->move(1);

		$this->setRedirect('index.php?option=com_jacc&view='.$this->_viewname);
	}
	/**
	 * save the ordering list
	 */
	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$cid = JRequest::getVar('cid', array (), 'post', 'array');
		$order = JRequest::getVar('order', array (), 'post', 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel($this->_mainmodel);
		$model->saveorder($cid, $order);

		$msg = JText::_('New ordering saved');
		$this->setRedirect('index.php?option=com_jacc&view='.$this->_viewname, $msg);
	}
}// class

?>