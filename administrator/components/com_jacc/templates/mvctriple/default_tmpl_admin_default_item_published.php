<?php defined('_JEXEC') or die; ?>

				<td class="center">
					##codestart## echo JHtml::_('jgrid.published', $item->published, $i, '##name##s.', $canChange, 'cb', $item->publish_up, $item->publish_down); ##codeend##
				</td>
