<?php defined('_JEXEC') or die; ?>
				<td class="center">
					##codestart## if ($item->language=='*'):##codeend##
						##codestart## echo JText::alt('JALL', 'language'); ##codeend##
					##codestart## else:##codeend##
						##codestart## echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ##codeend##
					##codestart## endif;##codeend##
				</td>
