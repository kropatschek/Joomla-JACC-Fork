<?php
/**
* @version		$Id: view.html.php 96 2011-08-11 06:59:32Z michel $
* @package		Jacc
* @subpackage 	Tables
* @copyright	Copyright (C) 2011, Michael Liebler. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class JaccViewModules  extends JView {

	public function display($tpl = null)
	{
		$app = JFactory::getApplication('');

		if ($this->getLayout() == 'form') {

			$this->_displayForm($tpl);
			return;
		}
		$context			= 'com_jacc'.'.'.strtolower($this->getName()).'.list.';
		$filter_state = $app->getUserStateFromRequest($context . 'filter_state', 'filter_state', '', 'word');
		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', $this->get('DefaultFilter'), 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$search = JString::strtolower($search);

		// Get data from the model
		$items =  $this->get('Data');
		$total =  $this->get('Total');
		$pagination =  $this->get('Pagination');
		//create the lists
		$lists = array();
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		// search filter
		$lists['search'] = $search;
		$items = $this->get('Data');

		//pagination
		$pagination =  $this->get( 'Pagination' );
		$user = JFactory::getUser();

		$this->assignRef('user', $user);
		$this->assign('lists', $lists);
		$this->assign('items', $items);
		$this->assign('total', $total);
		$this->assign('pagination', $pagination);
		parent::display();
	}

	/**
	 *  Displays the form
	 * @param string $tpl
	 */
	public function _displayForm($tpl)
	{
		global  $alt_libdir;

		JLoader::import('joomla.form.formvalidator', $alt_libdir);
		JHTML::stylesheet( 'fields.css', 'administrator/components/com_jacc/assets/' );

		$db			=JFactory::getDBO();
		$uri 		=JFactory::getURI();
		$user 		=JFactory::getUser();
		$form		= $this->get('Form');

		$lists = array();

		$editor =  JFactory::getEditor();

		//get the item
		$item	=$this->get('item');

		//Get Versions
		$model=$this->getModel();
		$archive = strtolower($item->name);
		$item->files = $model->getFiles($archive);

		$form->bind($item);

		$isNew		= ($item->id < 1);

		// Edit or Create?
		if ($isNew) {
			// initialise new record
			$item->published = 1;
		}

		$lists['published'] 		= JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $item->published );

		$this->assign('form', $form);

		$this->assignRef('lists', $lists);
		$this->assignRef('editor', $editor);
		$this->assignRef('item', $item);
		$this->assignRef('isNew', $isNew);

		parent::display($tpl);
	}
}
?>