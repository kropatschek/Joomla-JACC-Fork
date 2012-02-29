<?php defined('_JEXEC') or die; ?>
				<td>
					##ifdefFieldchecked_out_timeStart##
					##ifdefFieldchecked_outStart##
					##codestart## if ($item->checked_out) : ##codeend##
						##codestart## echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, '##name##s.', $canCheckin); ##codeend##
					##codestart## endif; ##codeend##
					##ifdefFieldchecked_out_timeEnd##
					##ifdefFieldchecked_outEnd##
					##codestart## if ($canEdit) : ##codeend##
						<a href="##codestart## echo JRoute::_('index.php?option=##com_component##&task=##name##.edit&id='.(int) $item->id); ##codeend##">
							##codestart## echo $this->escape($item->##title##); ##codeend##</a>
					##codestart## else : ##codeend##
							##codestart## echo $this->escape($item->##title##); ##codeend##
					##codestart## endif; ##codeend##
					##ifdefFieldaliasStart##
					<p class="smallsub">
						##codestart## echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ##codeend##</p>
					##ifdefFieldaliasEnd##
				</td>