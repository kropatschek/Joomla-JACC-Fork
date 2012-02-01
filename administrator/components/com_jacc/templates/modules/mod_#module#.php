<?php
/**
 * @version SVN: $Id: mod_#module#.php 96 2011-08-11 06:59:32Z michel $
 * @package    ##Module##
 * @subpackage Base
 * @author     ##author##
 * @license    ##license##
 */

defined('_JEXEC') or die('Restricted access'); // no direct access

require_once (dirname(__FILE__).DS.'helper.php');
$item = mod##Module##Helper::getItem($params);
require(JModuleHelper::getLayoutPath('mod_##module##'));
require_once ('helper.php');

?>