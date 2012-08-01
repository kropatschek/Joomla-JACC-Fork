<?php defined('_JEXEC') or die; ?>
<?php $foreignFields = $this->foreignFields; ?>
<?php $foreignFields = is_array($foreignFields) ? $foreignFields : array(); ?>
<?php if (array_key_exists ($this->field->get('key'), $foreignFields)) : ?>
<?php $foreign = $foreignFields[$this->field->get('key')]; ?>
				<td>
					##codestart## echo $this->escape($item-><?php echo $foreign->get('name').'_'.$foreign->get('hident'); ?>) ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'boolean'): ?>
				<td class="center">
					##codestart## //if ($canChange) : ##codeend##
						##codestart## //echo JHtml::_('grid.boolean', $i, $item->##field##, '##nameplural####extra##.##field##on', '##nameplural####extra##.##field##off'); ##codeend##
					##codestart## //else : ##codeend##
						##codestart## echo JText::_($item->##field## ? 'JYES' : 'JNO'  ); ##codeend##
					##codestart## //endif; ##codeend##
				</td>
<?php elseif ($this->field->get('formfield') == 'list'): ?>
				<td class="center nowrap">
					##codestart## $lang = JFactory::getLanguage(); ##codeend##
					##codestart## if ($lang->hasKey($key = '##COM_COMPONENT##_##NAME##_##FIELD##_VALUE_'.strtoupper($item->##field##))) : ##codeend##
						##codestart## echo JText::_($key); ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo $this->escape($item->##field##); ##codeend##
					##codestart## endif; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'datetime'): ?>
				<td class="center nowrap">
					##codestart## if ($item->##field## != JFactory::getDbo()->getNullDate()) : ##codeend##
						##codestart## echo JHtml::_('date',  $item->##field##, JText::_('DATE_FORMAT_LC4').' H:i', true); ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_EMPTY_##FIELD##'); ##codeend##
					##codestart## endif; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'date'): ?>
				<td class="center nowrap">
					##codestart## if ($item->##field## . ' 00:00:00' != JFactory::getDbo()->getNullDate()) : ##codeend##
						##codestart## echo JHtml::_('date',  $item->##field##, JText::_('DATE_FORMAT_LC4'), true) ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_EMPTY_##FIELD##'); ##codeend##
					##codestart## endif; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'time'): ?>
				<td class="center nowrap">
					##codestart## if ($item->##field## != '00:00:00') : ##codeend##
						##codestart## echo $item->##field##; ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_EMPTY_##FIELD##'); ##codeend##
					##codestart## endif; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'int' || $this->field->get('fieldtype') == 'tinyint'): ?>
				<td class="center">
					##codestart## echo (int) $item->##field##; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'float' || $this->field->get('fieldtype') == 'double'): ?>
				<td class="center">
					##codestart## //echo (float) $item->##field##; ##codeend##
					##codestart## echo sprintf('%.2f', $item->##field##); ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'text' || $this->field->get('fieldtype') == 'varchar'): ?>
				<td>
					##codestart## if ($item->##field##) : ##codeend##
						##codestart## echo $this->escape($item->##field##); ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_EMPTY_##FIELD##'); ##codeend##
					##codestart## endif; ##codeend##
				</td>
<?php else: ?>
				<td>
					##codestart## echo $this->escape($item->##field##); ##codeend##
				</td>
<?php endif; ?>
