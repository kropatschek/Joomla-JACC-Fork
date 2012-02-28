<?php
/**
 * @version		$Id: admin.jacc.php 96 2011-08-11 06:59:32Z michel $
 * @package    Jacc
 * @author	   	mliebler
 * @copyright  	Copyright (C) 2010, Michael Liebler. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

//--No direct access
defined('_JEXEC') or die('Resrtricted Access');
// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );

jimport('joomla.application.component.model');
require_once( JPATH_COMPONENT.DS.'models'.DS.'model.php' );
// Component Helper
jimport('joomla.application.component.helper');

//add Helperpath to JHTML
JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers');

//include Helper
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'jacc.php' );

//Use the JForms, even in Joomla 1.5 
$jv = new JVersion();
$GLOBALS['alt_libdir'] = ($jv->RELEASE < 1.6) ? JPATH_COMPONENT_ADMINISTRATOR : null;

//set the default view
$controller = JRequest::getWord('view', 'jacc');

//add submenu for 1.6
if ($jv->RELEASE > 1.5) {
	JaccHelper::addSubmenu($controller);	
}



$ControllerConfig = array();

// Require specific controller if requested
if ($controller) {   
   $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
   
   if (file_exists($path)) {
       require_once $path;
   } else {
       $ControllerConfig = array('viewname'=>strtolower($controller), 'mainmodel'=>strtolower($controller), 'itemname'=>ucfirst(strtolower($controller))); 
	   $controller = '';	   
   }
}

// Create the controller
$classname    = 'JaccController'.$controller;
$controller   = new $classname($ControllerConfig );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();