<?php defined('_JEXEC') or die; ?>
				<?php if ($this->field->get('formfield', 'text') <> 'editor'): ?>
				<li>##codestart## echo $this->form->getLabel('<?php echo $this->field->get('key'); ?>'); ##codeend##
				##codestart## echo $this->form->getInput('<?php echo $this->field->get('key'); ?>'); ##codeend##</li>
				<?php endif; ?>
			<?php if ($this->field->get('formfield', 'text') == 'editor'): ?>
			<div>
				##codestart## echo $this->form->getLabel('<?php echo $this->field->get('key'); ?>');##codeend##
				<div class="clr"></div>
				##codestart## echo $this->form->getInput('<?php echo $this->field->get('key'); ?>');##codeend##
			</div>
			<?php endif; ?>