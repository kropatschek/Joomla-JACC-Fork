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

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
##ifdefFieldorderingStart##
$canOrder	= $user->authorise('core.edit.state', '##com_component##.category');
$saveOrder	= $listOrder == 'a.ordering';
##ifdefFieldorderingEnd##
##codeend##
<form action="##codestart## echo JRoute::_('index.php?option=##com_component##&view=##name##s'); ##codeend##" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search">##codestart## echo JText::_('JSEARCH_FILTER_LABEL'); ##codeend##</label>
			<input type="text" name="filter_search" id="filter_search" value="##codestart## echo $this->escape($this->state->get('filter.search')); ##codeend##" title="##codestart## echo JText::_('COM_WEBLINKS_SEARCH_IN_TITLE'); ##codeend##" />
			<button type="submit">##codestart## echo JText::_('JSEARCH_FILTER_SUBMIT'); ##codeend##</button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">##codestart## echo JText::_('JSEARCH_FILTER_CLEAR'); ##codeend##</button>
		</div>
		<div class="filter-select fltrt">
##ifdefFieldpublishedStart##
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_PUBLISHED');##codeend##</option>
				##codestart## echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);##codeend##
			</select>
##ifdefFieldpublishedEnd##
##ifdefFieldstateStart##
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_PUBLISHED');##codeend##</option>
				##codestart## echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);##codeend##
			</select>
##ifdefFieldstateEnd##
##ifdefFieldcategory_idStart##
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_CATEGORY');##codeend##</option>
				##codestart## echo JHtml::_('select.options', JHtml::_('category.options', '##com_component##'), 'value', 'text', $this->state->get('filter.category_id'));##codeend##
			</select>
##ifdefFieldcategory_idEnd##
##ifdefFieldcatidStart##
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_CATEGORY');##codeend##</option>
				##codestart## echo JHtml::_('select.options', JHtml::_('category.options', '##com_component##'), 'value', 'text', $this->state->get('filter.category_id'));##codeend##
			</select>
##ifdefFieldcatidEnd##
##ifdefFieldlevelStart##
			<select name="filter_level" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_MAX_LEVELS');##codeend##</option>
				##codestart## echo JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'));##codeend##
			</select>
##ifdefFieldlevelEnd##
##ifdefFieldaccessStart##
			<select name="filter_access" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_ACCESS');##codeend##</option>
				##codestart## echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));##codeend##
			</select>
##ifdefFieldaccessEnd##
##ifdefFieldcreated_byStart##
			<select name="filter_author_id" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_AUTHOR');##codeend##</option>
				##codestart## echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'));##codeend##
			</select>
##ifdefFieldcreated_byEnd##
##ifdefFieldcreated_user_idStart##
			<select name="filter_author_id" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_AUTHOR');##codeend##</option>
				##codestart## echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'));##codeend##
			</select>
##ifdefFieldcreated_user_idEnd##
##ifdefFieldlanguageStart##
			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_LANGUAGE');##codeend##</option>
				##codestart## echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));##codeend##
			</select>
##ifdefFieldlanguageEnd##
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="##codestart## echo JText::_('JGLOBAL_CHECK_ALL'); ##codeend##" onclick="Joomla.checkAll(this)" />
				</th>
				##fields##
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="##numberOfFields##">
					##codestart## echo $this->pagination->getListFooter(); ##codeend##
				</td>
			</tr>
		</tfoot>
		<tbody>
		##codestart## foreach ($this->items as $i => $item) :
			##ifdefFieldorderingStart##
			$item->max_ordering = 0; //??
			$ordering	= ($listOrder == 'a.ordering');
			##ifdefFieldorderingEnd##
			##ifdefFieldchecked_out_timeStart##
			##ifdefFieldchecked_outStart##
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			##ifdefFieldchecked_out_timeEnd##
			##ifdefFieldchecked_outEnd##
			##ifdefFieldcatidStart##
			$canCreate	= $user->authorise('core.create',		'##com_component##.category.'.$item->catid);
			$canEdit	= $user->authorise('core.edit',			'##com_component##.category.'.$item->id);
			$canEditOwn	= $user->authorise('core.edit.own',		'##com_component##.category.'.$item->id)##ifdefFieldcreated_byStart## && $item->created_by == $userId##ifdefFieldcreated_byEnd##;
			$canChange	= $user->authorise('core.edit.state',	'##com_component##.category.'.$item->id)##ifdefFieldchecked_out_timeStart####ifdefFieldchecked_outStart## && $canCheckin##ifdefFieldchecked_out_timeEnd####ifdefFieldchecked_outEnd##;
			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##
			$canCreate	= $user->authorise('core.create',		'##com_component##.category.'.$item->catid);
			$canEdit	= $user->authorise('core.edit',			'##com_component##.category.'.$item->id);
			$canEditOwn	= $user->authorise('core.edit.own',		'##com_component##.category.'.$item->id)##ifdefFieldcreated_byStart## && $item->created_by == $userId##ifdefFieldcreated_byEnd##;
			$canChange	= $user->authorise('core.edit.state',	'##com_component##.category.'.$item->id)##ifdefFieldchecked_out_timeStart####ifdefFieldchecked_outStart## && $canCheckin##ifdefFieldchecked_out_timeEnd####ifdefFieldchecked_outEnd##;
			##ifdefFieldcategory_idEnd##
			##codeend##
			<tr class="row##codestart## echo $i % 2; ##codeend##">
				<td class="center">
					##codestart## echo JHtml::_('grid.id', $i, $item->id); ##codeend##
				</td>
				##fieldslist##
			</tr>
			##codestart## endforeach; ##codeend##
		</tbody>
	</table>

	##codestart## //TODO Load the batch processing form. ##codeend##
	##codestart## //echo $this->loadTemplate('batch'); ##codeend##

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="##codestart## echo $listOrder; ##codeend##" />
		<input type="hidden" name="filter_order_Dir" value="##codestart## echo $listDirn; ##codeend##" />
		##codestart## echo JHtml::_('form.token'); ##codeend##
	</div>
</form>