<?php defined('_JEXEC') or die; ?>
				<?php if ($this->field->get('formfield', 'text') == 'editor'): ?>
				<th>
					##codestart## echo echo JText::_('##COM_COMPONENT##_##NAME##_##FIELD##_HEADING'); ##codeend##
				</th>
				<?php else: ?>
				<th>
					##codestart## echo JHtml::_('grid.sort', '##COM_COMPONENT##_##NAME##_##FIELD##_HEADING', 'a.##field##', $listDirn, $listOrder); ##codeend##
				</th>
				<?php endif; ?>