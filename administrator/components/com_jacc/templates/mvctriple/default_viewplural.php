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

class ##Component##View##Name##s extends JView
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

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

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
		$canDo = ##Component##Helper::getActions();
		//$canDo = ##Component##Helper::getActions($this->state->get('filter.category_id'));

		$user = JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_##COMPONENT##_MANAGER_##NAME##S'), 'generic.png');

		if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('##name##.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolBarHelper::editList('##name##.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::divider();
			##ifdefFieldpublishedStart##
			JToolBarHelper::publish('##name##s.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('##name##s.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			##ifdefFieldpublishedEnd##
			##ifdefFieldfeaturedStart##
			JToolBarHelper::custom('##name##s.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
			##ifdefFieldfeaturedEnd##
			JToolBarHelper::divider();
			JToolBarHelper::archiveList('##name##s.archive');

			JToolBarHelper::checkin('##name##s.checkin');
		}
		##ifdefFieldstateStart##
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', '##name##s.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('##name##s.trash');
			JToolBarHelper::divider();
		}
		##ifdefFieldstateEnd##

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('##com_component##');
			JToolBarHelper::divider();
		}
		JToolBarHelper::help('JHELP_##COMPONENT##_##NAME##_MANAGER');
	}
}
##codeend##
