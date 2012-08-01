<?php defined('_JEXEC') or die; ?>
				<td class="order">
					##codestart## if ($canChange) : ##codeend##
						##codestart## if ($saveOrder) : ##codeend##
							<span>##codestart## echo $this->pagination->orderUpIcon($i, isset($this->ordering[$item->parent_id][$orderkey - 1]), '##nameplural####extra##.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ##codeend##</span>
							<span>##codestart## echo $this->pagination->orderDownIcon($i, $this->pagination->total, isset($this->ordering[$item->parent_id][$orderkey + 1]), '##nameplural####extra##.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ##codeend##</span>
						##codestart## endif; ##codeend##
						##codestart## $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ##codeend##
						<input type="text" name="order[]" size="5" value="##codestart## echo $orderkey + 1;##codeend##" ##codestart## echo $disabled ##codeend## class="text-area-order" />
						##codestart## $originalOrders[] = $orderkey + 1; ##codeend##
					##codestart## else : ##codeend##
						##codestart## echo $orderkey + 1;##codeend##
					##codestart## endif; ##codeend##
				</td>
