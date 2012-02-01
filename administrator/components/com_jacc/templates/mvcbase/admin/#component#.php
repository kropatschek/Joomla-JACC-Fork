<?php
/**
 * @version ##version##
 * @package    joomla
 * @subpackage ##Component##
 * @author	   	##author##
 *  @copyright  	Copyright (C) ##year##, ##author##. All rights reserved.
 *  @license ##license##
 */

//--No direct access
defined('_JEXEC') or die('Resrtricted Access');
// Require the base controller
require_once( JPATH_COMPONENT.'/controller.php' );

jimport('joomla.application.component.model');
require_once( JPATH_COMPONENT.'/models/model.php' );
// Component Helper
jimport('joomla.application.component.helper');

//add Helperpath to JHtml
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

//include Helper
require_once(JPATH_COMPONENT.'/helpers/##component##.php');

//Use the JForms, even in Joomla 1.5
//-$jv = new JVersion();
//-$GLOBALS['alt_libdir'] = ($jv->RELEASE < 1.6) ? JPATH_COMPONENT_ADMINISTRATOR : null;

//set the default view
$controller = JRequest::getWord('view', '##defaultview##');

//add submenu for 1.6
//-if ($jv->RELEASE > 1.5) {
##Component##Helper::addSubmenu($controller);
//-}

##categorytask##

$ControllerConfig = array();

// Require specific controller if requested
if ( $controller) {
$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
$ControllerConfig = array('viewname'=>strtolower($controller),'mainmodel'=>strtolower($controller),'itemname'=>ucfirst(strtolower($controller)));
if ( file_exists($path)) {
	   require_once $path;
} else {
	$controller = '';
}
}

// Create the controller
$classname    = '##Component##Controller'.$controller;
$controller   = new $classname($ControllerConfig );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();