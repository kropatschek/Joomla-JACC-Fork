<?php
/**
 * @version		$Id: sql.php 96 2011-08-11 06:59:32Z michel $
 * @author	   	mliebler
 * @package    Jacc
 * @subpackage Controllers
 * @copyright  	Copyright (C) 2010, mliebler. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Jacc Standard Controller
 *
 * @package Jacc   
 * @subpackage Controllers
 */
class JaccControllerSql extends JController
{

	/**
	 * Constructor
	 */
		 
	public function __construct($config = array ()) 
	{
		
		parent :: __construct($config);
	
	}
	
	function display() 
	{
		
		$config =& JFactory::getConfig();
		$dbprefix= $config->getValue('config.dbprefix');
		$sqlFile =  JPATH_COMPONENT_ADMINISTRATOR.DS.'sql'.DS.'example.sql';
		$sql = file_get_contents($sqlFile);

		print "<pre>";
		print str_replace('#__', $dbprefix, $sql);
		print "</pre>";
	}	
}
?>