<?php defined('_JEXEC') or die; ?>
				##ifnotdefFieldlftStart##
				<td>
					##codestart## echo (int) $this->escape($item->##field##) ##codeend##
				</td>
				##ifnotdefFieldlftEnd##
				##ifdefFieldlftStart##
				<td>
					<span title="##codestart## echo sprintf('%d-%d', $item->lft, $item->rgt);##codeend##">
						##codestart## echo (int) $this->escape($item->##field##) ##codeend##</span>
				</td>
				##ifdefFieldlftEnd##
