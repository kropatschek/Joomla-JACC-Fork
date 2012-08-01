<?php defined('_JEXEC') or die; ?>
##codestart##
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

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ##Component##View##Nameplural####extra## extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 *
	 * @return	void
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->authors		= $this->get('Authors');
		$this->canDo		= ##Component##Helper::getActions(##ifdefFieldparent_idStart##'##nameplural####extra##.##name##', $this->state->get('filter.##name##_id')##ifdefFieldparent_idEnd####ifnotdefFieldparent_idStart####ifdefFieldcatidStart##'##nameplural####extra##.category', $this->state->get('filter.category_id')##ifdefFieldcatidEnd####ifdefFieldcategory_idStart##'##nameplural####extra##.category', $this->state->get('filter.category_id')##ifdefFieldcategory_idEnd####ifnotdefFieldcatidStart####ifnotdefFieldcategory_idStart##'##name##'##ifnotdefFieldcategory_idEnd####ifnotdefFieldcatidEnd####ifnotdefFieldparent_idEnd##);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		##ifdefFieldparent_idStart##
		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->parent_id][] = $item->id;
		}

		// Levels filter.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1', JText::_('J1'));
		$options[]	= JHtml::_('select.option', '2', JText::_('J2'));
		$options[]	= JHtml::_('select.option', '3', JText::_('J3'));
		$options[]	= JHtml::_('select.option', '4', JText::_('J4'));
		$options[]	= JHtml::_('select.option', '5', JText::_('J5'));
		$options[]	= JHtml::_('select.option', '6', JText::_('J6'));
		$options[]	= JHtml::_('select.option', '7', JText::_('J7'));
		$options[]	= JHtml::_('select.option', '8', JText::_('J8'));
		$options[]	= JHtml::_('select.option', '9', JText::_('J9'));
		$options[]	= JHtml::_('select.option', '10', JText::_('J10'));

		$this->assign('f_levels', $options);
		##ifdefFieldparent_idEnd##



		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		$this->addToolbar();
		parent::display($tpl);

		// TODO modal view
		// We don't need toolbar in the modal window.
		//if ($this->getLayout() !== 'modal') {
		//	$this->addToolbar();
		//}
		//
		//parent::display($tpl);
	}

	/**
	 * Display the toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected function addToolbar()
	{
		// Initialise variables.
		##ifdefFieldextensionStart##
		//$component	= $this->state->get('filter.component');
		//$section	= $this->state->get('filter.section');
		##ifdefFieldextensionEnd##
		$canDo	= $this->canDo;
		$user	= JFactory::getUser();

		##ifdefFieldextensionStart##
		// Avoid nonsense situation.
		//if ($component == 'com_categories') {
		//	return;
		//}

		##ifdefFieldextensionEnd##
		JToolBarHelper::title(JText::_('COM_##COMPONENT##_##NAMEPLURAL####EXTRA##_MANAGER'), 'generic.png');

		if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('##name##.add');
		}

		if ($canDo->get('core.edit')##ifdefFieldparent_idStart## || $canDo->get('core.edit.own')##ifdefFieldparent_idEnd##)
		{
			JToolBarHelper::editList('##name##.edit');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.edit.state'))
		{
			##ifdefFieldpublishedStart##
			JToolBarHelper::publish('##nameplural####extra##.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('##nameplural####extra##.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			##ifdefFieldpublishedEnd##
			##ifdefFieldfeaturedStart##
			JToolBarHelper::custom('##nameplural####extra##.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
			##ifdefFieldfeaturedEnd##
			JToolBarHelper::divider();
			JToolBarHelper::archiveList('##nameplural####extra##.archive');
			##ifnotdefFieldparent_idStart##
			JToolBarHelper::checkin('##nameplural####extra##.checkin');
			##ifnotdefFieldparent_idEnd##
		}

		##ifdefFieldparent_idStart##
		if (JFactory::getUser()->authorise('core.admin')) {
			JToolBarHelper::checkin('##nameplural####extra##.checkin');
		}

		##ifdefFieldparent_idEnd##
		##ifdefFieldstateStart##
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', '##nameplural####extra##.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('##nameplural####extra##.trash');
			JToolBarHelper::divider();
		}

		##ifdefFieldstateEnd##
		##ifdefFieldpublishedStart##
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', '##nameplural####extra##.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('##nameplural####extra##.trash');
			JToolBarHelper::divider();
		}
		##ifdefFieldpublishedEnd##

		if ($canDo->get('core.admin'))
		{
			##ifdefFieldparent_idStart##
			JToolBarHelper::custom('##nameplural####extra##.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', false);
			##ifdefFieldparent_idEnd##
			JToolBarHelper::preferences('##com_component##');
			JToolBarHelper::divider();
		}
		JToolBarHelper::help('JHELP_##COMPONENT##_##NAME##_MANAGER');
	}
}
##codeend##
