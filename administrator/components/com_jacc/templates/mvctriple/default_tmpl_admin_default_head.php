<?php defined('_JEXEC') or die; ?>
<?php $foreignFields = $this->foreignFields; ?>
<?php $foreignFields = is_array($foreignFields) ? $foreignFields : array(); ?>
<?php if (array_key_exists ($this->field->get('key'), $foreignFields)): ?>
<?php $foreign = $foreignFields[$this->field->get('key')]; ?>
				<th>
					##codestart## echo JHtml::_('grid.sort', '##COM_COMPONENT##_##NAME##_HEADING_##FIELD##', '<?php echo $foreign->get('name').'_'.$foreign->get('hident'); ?>', $listDirn, $listOrder); ##codeend##
				</th>
<?php elseif ($this->field->get('formfield', 'text') == 'editor'): ?>
				<th>
					##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_HEADING_##FIELD##'); ##codeend##
				</th>
<?php else: ?>
				<th>
					##codestart## echo JHtml::_('grid.sort', '##COM_COMPONENT##_##NAME##_HEADING_##FIELD##', 'a.##field##', $listDirn, $listOrder); ##codeend##
				</th>
<?php endif; ?>
