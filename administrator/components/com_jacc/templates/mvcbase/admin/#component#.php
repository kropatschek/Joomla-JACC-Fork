<?php
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

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', '##com_component##')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register helper class
//TODO nur für test nacher wieder aukommentieren
//JLoader::register('##Component##Helper', dirname(__FILE__) . '/helpers/##component##.php');

// Include dependencies
jimport('joomla.application.component.controller');

$controller = JController::getInstance('##Component##');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();