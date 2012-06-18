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
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

##codeend##
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == '##name##.cancel' || document.formvalidator.isValid(document.id('##name##-form')))
		{
			##codestart## //TODO: echo $this->form->getField('description')->save();##codeend##
			Joomla.submitform(task, document.getElementById('##name##-form'));
		}
		else
		{
			alert('##codestart## echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="##codestart## echo JRoute::_('index.php?option=##com_component##&layout=edit&id='.(int) $this->item->id);##codeend##" method="post" name="adminForm" id="##name##-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend>##codestart## echo empty($this->item->id) ? JText::_('COM_##COMPONENT##_NEW_##NAME##') : JText::sprintf('COM_##COMPONENT##_EDIT_##NAME##', $this->item->id);##codeend##</legend>
			<ul class="adminformlist">
				<?php if (isset($this->formfield['details']))
				{
					$fields = $this->formfield['details'];
					foreach ($fields as $field)
					{
						$this->field = $field;
						if ($this->field->get('formfield', 'text') <> 'editor')
						{
							echo $this->loadTemplate('tmpl_admin_edit_fields');
						}
					}
				} ?>
			</ul>
			<?php
			if (isset($this->formfield['details']))
			{
				$fields = $this->formfield['details'];
				foreach ($fields as $field)
				{
					$this->field = $field;
					if ($this->field->get('formfield', 'text') =='editor')
					{
						echo $this->loadTemplate('tmpl_admin_edit_fields');
					}
				}
			}
			?>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
		##codestart## echo  JHtml::_('sliders.start', '##name##-slider'.$this->item->id, array('useCookie'=>1)); ##codeend##
		##codestart## echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_PUBLISHING'), 'publishing-details');##codeend##
		<fieldset class="panelform">
			<ul class="adminformlist">
				<?php
				if (isset($this->formfield['desc']))
				{
					$fields = $this->formfield['desc'];
					foreach ($fields as $field)
					{
						$this->field = $field;
						if ($this->field->get('formfield', 'text') <>'editor')
						{
							echo $this->loadTemplate('formfields');
						}
					}
				}
				?>
				##codestart## if ($this->canDo->get('core.admin')): ##codeend##
				<li><span class="faux-label">##codestart## echo JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL'); ##codeend##</span>
					<div class="button2-left"><div class="blank">
						<button type="button" onclick="document.location.href='#access-rules';">
							##codestart## echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR'); ##codeend##
						</button>
					</div></div>
				</li>
				##codestart## endif; ##codeend##
			</ul>
			<?php
			if (isset($this->formfield['desc']))
			{
				$fields = $this->formfield['desc'];
				foreach ($fields as $field)
				{
					$this->field = $field;
					if ($this->field->get('formfield', 'text') =='editor')
					{
						echo $this->loadTemplate('formfields');
					}
				}
			}
			?>
		</fieldset>
		##codestart## echo JHtml::_('sliders.panel', JText::_('COM_##COMPONENT##_##NAME##_DETAILS'), 'basic-options');##codeend##
		<fieldset class="panelform">
			<ul class="adminformlist">
				<?php
				if (isset($this->formfield['subdesc']))
				{
					$fields = $this->formfield['subdesc'];
					foreach ($fields as $field)
					{
						$this->field = $field;
						if ($this->field->get('formfield', 'text') <>'editor')
						{
							echo $this->loadTemplate('formfields');
						}
					}
				}
				?>
			</ul>
			<?php
			if (isset($this->formfield['subdesc']))
			{
				$fields = $this->formfield['subdesc'];
				foreach ($fields as $field)
				{
					$this->field = $field;
					if ($this->field->get('formfield', 'text') =='editor')
					{
						echo $this->loadTemplate('formfields');
					}
				}
			}
			?>
		</fieldset>
		##ifdefFieldparamsStart##

		##codestart## //echo $this->loadTemplate('params');##codeend##
		##ifdefFieldparamsEnd##

		##ifdefFieldmetadataStart##

		##codestart## //echo $this->loadTemplate('metadata');##codeend##
		##ifdefFieldmetadataEnd##

		##codestart## echo JHtml::_('sliders.end');##codeend##
	</div>

	##ifdefFieldasset_idStart##
	<div class="clr"></div>
	##codestart## if ($this->canDo->get('core.admin')): ##codeend##
		<div class="width-100 fltlft">
			##codestart## echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ##codeend##

				##codestart## echo JHtml::_('sliders.panel', JText::_('##COM_COMPONENT##_FIELDSET_RULES'), 'access-rules'); ##codeend##
				<fieldset class="panelform">
					##codestart## echo $this->form->getLabel('rules'); ##codeend##
					##codestart## echo $this->form->getInput('rules'); ##codeend##
				</fieldset>

			##codestart## echo JHtml::_('sliders.end'); ##codeend##
		</div>
	##codestart## endif; ##codeend##
	<div>
		<input type="hidden" name="task" value="" />
		##codestart## echo JHtml::_('form.token');##codeend##
	</div>
	##ifdefFieldasset_idEnd##
</form>
<div class="clr"></div>