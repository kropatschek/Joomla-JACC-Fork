<?php defined('_JEXEC') or die; ?>
				<th width="10%">
					##codestart## echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'a.lft', $listDirn, $listOrder); ##codeend##
					##codestart## if ($saveOrder) :##codeend##
						##codestart## echo JHtml::_('grid.order', $this->items, 'filesave.png', '##nameplural####extra##.saveorder'); ##codeend##
					##codestart## endif; ##codeend##
				</th>
