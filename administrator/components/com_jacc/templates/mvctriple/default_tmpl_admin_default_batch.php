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
##ifdefFieldparent_idStart##
$options = array(
	JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
	JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE'))
);

##ifdefFieldparent_idEnd##
##ifdefFieldpublishedStart##
$published	= $this->state->get('filter.published');
##ifdefFieldpublishedEnd##
##ifdefFieldstateStart##
$state	= $this->state->get('filter.state');
##ifdefFieldstateEnd##
##ifdefFieldextensionStart##
$extension	= $this->escape($this->state->get('filter.extension'));
##ifdefFieldextensionEnd##

##codeend##
<fieldset class="batch">
	<legend>##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_BATCH_OPTIONS');##codeend##</legend>
	<p>##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_BATCH_TIP'); ##codeend##</p>
	##ifdefFieldaccessStart####codestart## echo JHtml::_('batch.access');##codeend####ifdefFieldaccessEnd##
	##ifdefFieldlanguageStart####codestart## echo JHtml::_('batch.language'); ##codeend####ifdefFieldlanguageEnd##
	##ifdefFielduser_idStart####codestart## echo JHtml::_('batch.user'); ##codeend####ifdefFielduser_idEnd##
	##ifdefFieldparent_idStart##
	##ifdefFieldpublishedStart##
	##codestart## if ($published >= 0) : ##codeend##
		<label id="batch-choose-action-lbl" for="batch-##name##-id">
			##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_BATCH_CATEGORY_LABEL'); ##codeend##
		</label>
		<fieldset id="batch-choose-action" class="combo">
		<select name="batch[##name##_id]" class="inputbox" id="batch-##name##-id">
			<option value="">##codestart## echo JText::_('JSELECT') ##codeend##</option>
			##ifdefFieldextensionStart##
			##codestart## //echo JHtml::_('select.options', JHtml::_('##name##.##nameplural####extra##', $extension, array('filter.published' => $published)));##codeend##
			##ifdefFieldextensionEnd##
			##codestart## echo JHtml::_('select.options', JHtml::_('##name##.##nameplural####extra##', array('filter.published' => $published)));##codeend##
		</select>
		##codestart## echo JHtml::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'); ##codeend##
		</fieldset>
	##codestart## endif; ##codeend##

	##ifdefFieldpublishedEnd##
	##ifdefFieldstateStart##
	##codestart## if ($state >= 0) : ##codeend##
		<label id="batch-choose-action-lbl" for="batch-##name##-id">
			##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_BATCH_CATEGORY_LABEL'); ##codeend##
		</label>
		<fieldset id="batch-choose-action" class="combo">
		<select name="batch[##name##_id]" class="inputbox" id="batch-##name##-id">
			<option value="">##codestart## echo JText::_('JSELECT') ##codeend##</option>
			##ifdefFieldextensionStart##
			##codestart## //echo JHtml::_('select.options', JHtml::_('##name##.##nameplural####extra##', $extension, array('filter.state' => $state)));##codeend##
			##ifdefFieldextensionEnd##
			##codestart## echo JHtml::_('select.options', JHtml::_('##name##.##nameplural####extra##', array('filter.state' => $state)));##codeend##
		</select>
		##codestart## echo JHtml::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'); ##codeend##
		</fieldset>
	##codestart## endif; ##codeend##

	##ifdefFieldstateEnd##
	##ifdefFieldparent_idEnd##
	##ifnotdefFieldparent_idStart##
	##ifdefFieldpublishedStart##
	##codestart## if ($published >= 0) : ##codeend##
		##codestart## echo JHtml::_('batch.item', '##com_component##.##nameplural####extra##');##codeend##
	##codestart## endif; ##codeend##
	##ifdefFieldpublishedEnd##
	##ifdefFieldstateStart##
	##codestart## if ($state >= 0) : ##codeend##
		##codestart## echo JHtml::_('batch.item', '##com_component##.##nameplural####extra##');##codeend##
	##codestart## endif; ##codeend##
	##ifdefFieldstateEnd##
	##ifnotdefFieldparent_idEnd##
	<button type="submit" onclick="Joomla.submitbutton('##name##.batch');">
		##codestart## echo JText::_('JGLOBAL_BATCH_PROCESS'); ##codeend##
	</button>
	<button type="button" onclick="##ifnotdefFieldparent_idEnd##document.id('batch-category-id').value='';##ifnotdefFieldparent_idEnd####ifdefFieldparent_idStart##document.id('batch-##name##-id').value='';##ifdefFieldparent_idEnd####ifdefFieldaccessStart##document.id('batch-access').value='';##ifdefFieldaccessEnd####ifdefFieldlanguageStart##document.id('batch-language-id').value='';##ifdefFieldlanguageEnd##">
		##codestart## echo JText::_('JSEARCH_FILTER_CLEAR'); ##codeend##
	</button>
</fieldset>
