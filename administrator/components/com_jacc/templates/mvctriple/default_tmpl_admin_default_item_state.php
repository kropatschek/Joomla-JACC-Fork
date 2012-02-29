<?php defined('_JEXEC') or die; ?>
				<td class="center">
					##codestart## echo JHtml::_('jgrid.published', $item->state, $i, '##name##s.', $canChange, 'cb'##ifdefFieldpublish_upStart##, $item->publish_up##ifdefFieldpublish_upEnd####ifdefFieldpublish_downStart##, $item->publish_down##ifdefFieldpublish_downEnd##); ##codeend##
				</td>