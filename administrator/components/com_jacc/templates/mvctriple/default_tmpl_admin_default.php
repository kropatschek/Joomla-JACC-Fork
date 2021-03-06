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
##ifdefFieldextensionStart##
//$extension	= $this->escape($this->state->get('filter.extension'));
##ifdefFieldextensionEnd##
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
##ifdefFieldorderingStart##
##ifdefFieldcatidStart##
$canOrder	= $user->authorise('core.edit.state', '##com_component##.##nameplural####extra##.category');
##ifdefFieldcatidEnd##
##ifdefFieldcategory_idStart##
$canOrder	= $user->authorise('core.edit.state', '##com_component##.##nameplural####extra##.category');
##ifdefFieldcategory_idEnd##
##ifnotdefFieldcatidStart##
##ifnotdefFieldcategory_idStart##
$canOrder	= $user->authorise('core.edit.state', '##com_component##.##name##');
##ifnotdefFieldcategory_idEnd##
##ifnotdefFieldcatidEnd##
$ordering	= ($listOrder == 'a.ordering');
$saveOrder	= ($listOrder == 'a.ordering');
##ifdefFieldorderingEnd##
##ifdefFieldlftStart##
$ordering 	= ($listOrder == 'a.lft');
$saveOrder 	= ($listOrder == 'a.lft' && $listDirn == 'asc');
##ifdefFieldlftEnd##
##codeend##
<form action="##codestart## echo JRoute::_('index.php?option=##com_component##&view=##nameplural####extra##'); ##codeend##" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search">##codestart## echo JText::_('JSEARCH_FILTER_LABEL'); ##codeend##</label>
			<input type="text" name="filter_search" id="filter_search" value="##codestart## echo $this->escape($this->state->get('filter.search')); ##codeend##" title="##codestart## echo JText::_('JFIELD_PLG_SEARCH_ALL_LABEL'); ##codeend##" />
			<button type="submit">##codestart## echo JText::_('JSEARCH_FILTER_SUBMIT'); ##codeend##</button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">##codestart## echo JText::_('JSEARCH_FILTER_CLEAR'); ##codeend##</button>
		</div>
		<div class="filter-select fltrt">
			##ifdefFieldpublishedStart##
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value="">##codestart## echo JText::_('JOPTION_SELECT_PUBLISHED');##codeend##</option>
				##codestart## echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);##codeend##
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
				</th>##fields##
			</tr>
		</thead>
		<tfoot>
			<tr>
			<!-- TODO: Not all browsers support colspan="0". A better solution would be if colspan is the number off colums -->
				<td colspan="0">
					##codestart## echo $this->pagination->getListFooter(); ##codeend##
				</td>
			</tr>
		</tfoot>
		<tbody>
		##codestart##
		##ifdefFieldparent_idStart##
		$originalOrders = array();
		##ifdefFieldparent_idEnd##
		##ifdefFieldorderingStart##
		$n = count($this->items);
		##ifdefFieldorderingEnd##
		foreach ($this->items as $i => $item) :
			##ifdefFieldparent_idStart##
			$orderkey	= array_search($item->id, $this->ordering[$item->parent_id]);
			##ifdefFieldparent_idEnd##
			##ifdefFieldorderingStart##
			$item->max_ordering = 0; //?? I don't get it, what is the sense of this
			##ifdefFieldorderingEnd##
			##ifdefFieldchecked_out_timeStart##
			##ifdefFieldchecked_outStart##
			$canCheckin	= $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			##ifdefFieldchecked_out_timeEnd##
			##ifdefFieldchecked_outEnd##
			##ifdefFieldcatidStart##
			$canCreate	= $user->authorise('core.create',     '##com_component##.##nameplural####extra##.category.'.$item->catid);
			##ifdefFieldcatidEnd##
			##ifdefFieldcategory_idStart##
			$canCreate	= $user->authorise('core.create',     '##com_component##.##nameplural####extra##.category.'.$item->category_id);
			##ifdefFieldcategory_idEnd##
			$canEdit	= $user->authorise('core.edit',       '##com_component####ifdefFieldparent_idStart##.##nameplural####extra####ifdefFieldparent_idEnd##.##name##.'.$item->id);
			$canEditOwn	= $user->authorise('core.edit.own',   '##com_component####ifdefFieldparent_idStart##.##nameplural####extra####ifdefFieldparent_idEnd##.##name##.'.$item->id)##ifdefFieldcreated_byStart## && $item->created_by == $userId##ifdefFieldcreated_byEnd####ifdefFieldcreated_user_idStart## && $item->created_user_id == $userId##ifdefFieldcreated_user_idEnd##;
			$canChange	= $user->authorise('core.edit.state', '##com_component####ifdefFieldparent_idStart##.##nameplural####extra####ifdefFieldparent_idEnd##.##name##.'.$item->id)##ifdefFieldchecked_out_timeStart####ifdefFieldchecked_outStart## && $canCheckin##ifdefFieldchecked_out_timeEnd####ifdefFieldchecked_outEnd##;
		##codeend##
			<tr class="row##codestart## echo $i % 2; ##codeend##">
				<td class="center">
					##codestart## echo JHtml::_('grid.id', $i, $item->id); ##codeend##
				</td>##fieldslist##
			</tr>
			##codestart## endforeach; ##codeend##
		</tbody>
	</table>

	##codestart## //Load the batch processing form. ##codeend##
	##codestart## echo $this->loadTemplate('batch'); ##codeend##

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="##codestart## echo $listOrder; ##codeend##" />
		<input type="hidden" name="filter_order_Dir" value="##codestart## echo $listDirn; ##codeend##" />
		##codestart## echo JHtml::_('form.token'); ##codeend##
		##ifdefFieldparent_idStart##
		<input type="hidden" name="original_order_values" value="##codestart## echo implode($originalOrders, ','); ##codeend##" />
		##ifdefFieldparent_idEnd##
	</div>
</form>