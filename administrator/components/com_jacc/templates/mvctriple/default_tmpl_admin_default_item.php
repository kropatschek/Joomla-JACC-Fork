<?php defined('_JEXEC') or die; ?>
<?php if ($this->field->get('fieldtype') == 'text' || $this->field->get('fieldtype') == 'varchar'): ?>
				<td>
					##codestart## if ($item->##field##) : ##codeend##
						##codestart## echo $this->escape($item->##field##); ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo JText::_('##COM_COMPONENT##_##NAME##_EMPTY_##FIELD##'); ##codeend##
					##codestart## endif; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'boolean'): ?>
				<td class="center">
					##codestart## if ($canChange) : ##codeend##
						##codestart## echo JHtml::_('grid.boolean', $i, $item->##field##, '##nameplural####extra##.##field##on', '##nameplural####extra##.##field##off'); ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo JText::_($item->##field## ? 'JYES' : 'JNO'  ); ##codeend##
					##codestart## endif; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'datetime'): ?>
				<td class="center nowrap">
					##codestart## echo JHtml::_('date', $item->##field##, 'Y-m-d H:i:s'); ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'date'): ?>
				<td class="center nowrap">
					##codestart## echo JHtml::_('date', $item->##field##, 'Y-m-d'); ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'int' || $this->field->get('fieldtype') == 'tinyint'): ?>
				<td class="center">
					##codestart## echo (int) $item->##field##; ##codeend##
				</td>
<?php elseif ($this->field->get('fieldtype') == 'float' || $this->field->get('fieldtype') == 'double'): ?>
				<td class="center">
					##codestart## echo (float) $item->##field##; ##codeend##
				</td>
<?php else: ?>
				<td>
					##codestart## echo $this->escape($item->##field##) ##codeend##
				</td>
<?php endif; ?>
