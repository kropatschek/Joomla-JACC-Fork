<?php
/**
 * @version $Id: #component#.php 96 2011-08-11 06:59:32Z michel $ 1 ##date##Z ##sauthor## $
* @package	##Component##
* @copyright	Copyright (C) ##year##, ##author##. All rights reserved.
* @license ###license##
 */

//--No direct access
defined('_JEXEC') or die('=;)');

// Require the base controller
require_once( JPATH_COMPONENT.'/controller.php' );

jimport('joomla.application.component.model');
require_once( JPATH_COMPONENT.'/models/model.php' );
jimport('joomla.application.component.helper');
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers' );
//set the default view
$task = JRequest::getWord('task');

//Use the JForms, even in Joomla 1.5
//-$jv = new JVersion();
//-$GLOBALS['alt_libdir'] = ($jv->RELEASE < 1.6) ? JPATH_COMPONENT_ADMINISTRATOR : null;


$config = JComponentHelper::getParams( 'com_##component##' );

$controller = JRequest::getWord('view', '##defaultview##');

$ControllerConfig = array();

// Require specific controller if requested
if ($controller) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	$ControllerConfig = array('viewname'=>strtolower($controller),'mainmodel'=>strtolower($controller),'itemname'=>ucfirst(strtolower($controller)));
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}


// Create the controller
$classname	= '##Component##Controller'.$controller;
$controller	= new $classname($ControllerConfig );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();