<?php defined('_JEXEC') or die; ?>
Joomla.submitbutton = function(task)
{
	if (task == '##name##.cancel' || document.formvalidator.isValid(document.id('##name##-form')))
	{
		Joomla.submitform(task, document.getElementById('##name##-form'));
	}
	else
	{
		alert(Joomla.JText._('JGLOBAL_VALIDATION_FORM_FAILED'));
	}
}