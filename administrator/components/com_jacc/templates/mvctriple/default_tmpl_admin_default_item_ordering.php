<?php defined('_JEXEC') or die; ?>
				<td class="order">
					##ifdefFieldcatidStart##
						##codestart## if ($canChange) : ##codeend##
							##codestart## if ($saveOrder) :##codeend##
								##codestart## if ($listDirn == 'asc') : ##codeend##
									<span>##codestart## echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid), '##nameplural####extra##.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ##codeend##</span>
									<span>##codestart## echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), '##nameplural####extra##.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ##codeend##</span>
								##codestart## elseif ($listDirn == 'desc') : ##codeend##
									<span>##codestart## echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid), '##nameplural####extra##.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ##codeend##</span>
									<span>##codestart## echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), '##nameplural####extra##.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ##codeend##</span>
								##codestart## endif; ##codeend##
							##codestart## endif; ##codeend##
							##codestart## $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ##codeend##
							<input type="text" name="order[]" size="5" value="##codestart## echo $item->ordering;##codeend##" ##codestart## echo $disabled ##codeend## class="text-area-order" />
						##codestart## else : ##codeend##
							##codestart## echo $item->ordering; ##codeend##
						##codestart## endif; ##codeend##
					##ifdefFieldcatidEnd##
					##ifdefFieldcategory_idStart##
						##codestart## if ($canChange) : ##codeend##
							##codestart## if ($saveOrder) :##codeend##
								##codestart## if ($listDirn == 'asc') : ##codeend##
									<span>##codestart## echo $this->pagination->orderUpIcon($i, ($item->category_id == @$this->items[$i-1]->category_id), '##nameplural####extra##.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ##codeend##</span>
									<span>##codestart## echo $this->pagination->orderDownIcon($i, $n, ($item->category_id == @$this->items[$i+1]->category_id), '##nameplural####extra##.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ##codeend##</span>
								##codestart## elseif ($listDirn == 'desc') : ##codeend##
									<span>##codestart## echo $this->pagination->orderUpIcon($i, ($item->category_id == @$this->items[$i-1]->category_id), '##nameplural####extra##.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ##codeend##</span>
									<span>##codestart## echo $this->pagination->orderDownIcon($i, $n, ($item->category_id == @$this->items[$i+1]->category_id), '##nameplural####extra##.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ##codeend##</span>
								##codestart## endif; ##codeend##
							##codestart## endif; ##codeend##
							##codestart## $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ##codeend##
							<input type="text" name="order[]" size="5" value="##codestart## echo $item->ordering;##codeend##" ##codestart## echo $disabled ##codeend## class="text-area-order" />
						##codestart## else : ##codeend##
							##codestart## echo $item->ordering; ##codeend##
						##codestart## endif; ##codeend##
					##ifdefFieldcategory_idEnd##
					##ifnotdefFieldcatidStart##
					##ifnotdefFieldcategory_idStart##
						##codestart## if ($canChange) : ##codeend##
							##codestart## if ($saveOrder) :##codeend##
								##codestart## if ($listDirn == 'asc') : ##codeend##
									<span>##codestart## echo $this->pagination->orderUpIcon($i, true, '##nameplural####extra##.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ##codeend##</span>
									<span>##codestart## echo $this->pagination->orderDownIcon($i, $n, true, '##nameplural####extra##.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ##codeend##</span>
								##codestart## elseif ($listDirn == 'desc') : ##codeend##
									<span>##codestart## echo $this->pagination->orderUpIcon($i, true, '##nameplural####extra##.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ##codeend##</span>
									<span>##codestart## echo $this->pagination->orderDownIcon($i, $n, true, '##nameplural####extra##.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ##codeend##</span>
								##codestart## endif; ##codeend##
							##codestart## endif; ##codeend##
							##codestart## $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ##codeend##
							<input type="text" name="order[]" size="5" value="##codestart## echo $item->ordering;##codeend##" ##codestart## echo $disabled ##codeend## class="text-area-order" />
						##codestart## else : ##codeend##
							##codestart## echo $item->ordering; ##codeend##
						##codestart## endif; ##codeend##
					##ifnotdefFieldcategory_idEnd##
					##ifnotdefFieldcatidEnd##
				</td>
