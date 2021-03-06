<?php
/**
* @version		$Id:controller.php  1 ##date##Z ##sauthor## $
* @package		##Component##
* @subpackage 	Controllers
* @copyright	Copyright (C) ##year##, ##author##. All rights reserved.
* @license ###license##
*/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Variant Controller
 *
 * @package
 * @subpackage Controllers
 */
class ##Component##Controller extends JController
{

	protected $_viewname = 'item';
	protected $_mainmodel = 'item';
	protected $_itemname = 'Item';
	protected $_context = "com_##component##";
	/**
	 * Constructor
	 */

	public function __construct($config = array ()) {

		parent::__construct($config);

		if(isset($config['viewname'])) $this->_viewname = $config['viewname'];
		if(isset($config['mainmodel'])) $this->_mainmodel = $config['mainmodel'];
		if(isset($config['itemname'])) $this->_itemname = $config['itemname'];

		JRequest::setVar('view', $this->_viewname);

	}

	public function display($cachable = false, $urlparams = false) {

		$document =JFactory::getDocument();

		$viewType	= $document->getType();
		$view = $this->getView($this->_viewname,$viewType);
		$model = $this->getModel($this->_mainmodel);

		$view->setModel($model,true);
		$view->display();
	}


}// class



?>