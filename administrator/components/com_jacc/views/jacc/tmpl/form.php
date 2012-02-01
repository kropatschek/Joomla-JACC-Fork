<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Set toolbar items for the page
$edit		= JRequest::getVar('edit', true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::title(   JText::_('Component').': <small><small>[ ' . $text.' ]</small></small>' );
JToolBarHelper::apply();
JToolBarHelper::save();
if (!$edit) {
	JToolBarHelper::cancel();
} else {
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel( 'cancel', 'Close' );
}

?>

<script language="javascript" type="text/javascript">

var numcvpairs = <?php echo $this->numcvpairs ?>;

window.addEvent('domready', function() {

	 document.formvalidator.setHandler('component',
            function (value) {
                    regex=/com_.*/;
                    if(regex.test(value) == false) {
						return alert('<?php echo JText::_('Component starts with com_'); ?>');
                    }
                    return true;
    });
});	

window.addEvent('domready', function() {
    document.formvalidator.setHandler('tables',
            function (value) {
				if(value == '') return alert('<?php echo JText::_('Please select table(s)') ?>');
        		return true;
    });
});	

function vremove(file) {
	$('vremove').value = file;
	submitbutton('vremove');
}
<?php 
$jv = new JVersion();
if ($jv->RELEASE < 1.6):
?>
function submitbutton(task)
{

    var form = document.adminForm;
    if (task == 'cancel' || document.formvalidator.isValid(form)) {
		submitform(task);
	}
}
<?php else: ?>

Joomla.submitbutton = function(task)
{
	if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
}
<?php endif; ?>
</script>

	 	<form method="post" action="index.php" id="adminForm" name="adminForm">
	 	<div class="col width-60  fltlft">
		  <fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>			
										
										
					<?php echo $this->form->getLabel('name'); ?>
					
					<?php echo $this->form->getInput('name');  ?> <span style="line-height:20px">(com_xxx)</span> 
										
					<?php echo $this->form->getLabel('version'); ?>
					
					<?php echo $this->form->getInput('version');  ?>
										
					<?php echo $this->form->getLabel('use'); ?>
					
					<?php echo $this->form->getInput('use');  ?>
					
					<?php echo $this->form->getLabel('tables'); ?>
					
					<?php echo $this->form->getInput('tables');  ?>
										
					<?php echo $this->form->getLabel('created'); ?>
					
					<?php echo $this->form->getInput('created');  ?>
										
					<?php echo $this->form->getLabel('published'); ?>
					
					<?php echo $this->form->getInput('published');  ?>
						            
          </fieldset>       
        </div>
        <div class="col width-40 fltrt">
        <div style="margin-top:10px;"></div>
			<fieldset class="panelform">
				<legend><?php echo JText::_( 'Description' ); ?></legend>
					 <div class="clr"></div>
					<?php echo $this->form->getInput('description');  ?>
			</fieldset>
			<fieldset class="panelform">
				<legend><?php echo JText::_( 'Parameters' ); ?></legend>
				<table>				

				<?php foreach ($this->form->getFieldset('basic') as $field) : ?>
					<?php if ($field->hidden):  ?>
						<?php echo $field->input;  ?>
					<?php else:  ?>
					<tr>
						<td class="paramlist_key" width="40%">
							<?php echo $field->label;  ?>
						</td>
						<td class="paramlist_value">
							<?php echo $field->input;  ?>
						</td>
					</tr>
				<?php endif;  ?>
				<?php endforeach;  ?>
			</table>			
			</fieldset>		
			<fieldset class="panelform">			
				<legend><?php echo JText::_( 'Versions' ); ?></legend>
				<div style="padding-left:50px;">
				<?php 
				
				if ($count= count($this->item->files)):
				
				?>
				 <ul>
				 		<?php foreach($this->item->files as $file):				 					 	
				 				$isRecent = stristr($file, '-'.$this->item->version.'.');
				 				?>
				 				<li style="border-bottom:1px dotted #c0c0c0;height:24px;">
				 					<a href="<?php echo JURI::base() ?>components/com_jacc/archives/<?php echo $file?>"><?php echo $file ?></a>
				 					<?php if (!$isRecent ): ?>
				 					<a  href="Javascript:vremove('<?php echo $file?>')" class="listicon"><img class="hasTip" src="<?php echo JURI::base() ?>components/com_jacc/assets/delete.png" title="Delete::Delete This Version" \ ></a>
									<?php endif; ?>
				 				</li>
				 		<?php
				 				$count--; 
				 				endforeach; ?>
				 </ul>				 
				 
				 <?php endif;?>
				 </div>
			</fieldset>					
        </div>                   
	 	<div class="col width-60  fltlft">
		  <fieldset class="adminform">
		  <legend>View/Controller</legend>
			<div class="fltrt col width-20"><?php echo JText::_('AddViewControllerHelp')?></div>
			<div id="vcpairs-container" class="fltlft col width-80">						  
		  		<?php foreach ($this->form->getFieldset('views') as $field) : ?>
					<?php echo $field->input;  ?>
				<?php endforeach;  ?>
			</div>
		</fieldset>
		</div>          
		<input type="hidden" name="option" value="com_jacc" />
		<input type="hidden" id="vremove" name="vremove" value="" />
	    <input type="hidden" name="cid[]" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="jacc" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<div class="clr"></div>
	<div style="text-align:center;font-weight:bold;padding:10px;">Jacc Version <?php print JaccHelper::getVersion() ?></div> 