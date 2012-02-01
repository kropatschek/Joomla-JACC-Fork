<?php defined('_JEXEC') or die('Restricted access'); ?>
##codestart##
// no direct access
defined('_JEXEC') or die('Restricted access');

  JToolBarHelper::title( JText::_( '##Name##' ), 'generic.png' );
  JToolBarHelper::publishList();
  JToolBarHelper::unpublishList();
  JToolBarHelper::deleteList();
  JToolBarHelper::editListX();
  JToolBarHelper::addNewX();
  JToolBarHelper::preferences('com_##component##', '550');  
##codeend##

<form action="index.php?option=##com_component##&amp;view=##name##" method="post" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				##codestart## echo JText::_( 'Filter' ); ##codeend##:
				<input type="text" name="search" id="search" value="##codestart## echo $this->lists['search'];##codeend##" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();">##codestart## echo JText::_( 'Go' ); ##codeend##</button>
				<button onclick="document.getElementById('search').value='';this.form.submit();">##codestart## echo JText::_( 'Reset' ); ##codeend##</button>
			</td>
			<td nowrap="nowrap">
##ifdefFieldpublishedStart##			
				##codestart##
 				  	echo $this->lists['state'];
  				##codeend##
##ifdefFieldpublishedEnd##  				
			</td>
		</tr>		
	</table>
<div id="editcell">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					##codestart## echo JText::_( 'NUM' ); ##codeend##
				</th>
				<th width="20">				
					<input type="checkbox" name="toggle" value="" onclick="checkAll(##codestart## echo count( $this->items ); ##codeend##);" />
				</th>			
##fields##
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="12">
				##codestart## echo $this->pagination->getListFooter(); ##codeend##
			</td>
		</tr>
	</tfoot>
	<tbody>
##codestart##
  $k = 0;
  if (count( $this->items ) > 0 ):
  
  for ($i=0, $n=count( $this->items ); $i < $n; $i++):
  
  	$row = &$this->items[$i];
 	$onclick = "";
  	
    if (JRequest::getVar('function', null)) {
    	$onclick= "onclick=\"window.parent.jSelect##Name##_id('".$row->id."', '".$this->escape($row-><?php echo $this->hident ?>)."', '','##primary##')\" ";
    }  	
    
 	$link = JRoute::_( 'index.php?option=##com_component##&view=##name##&task=edit&cid[]='. $row->##primary## );
 	$row->id = $row->##primary##;
##ifdefFieldchecked_out_timeStart##
##ifdefFieldchecked_outStart## 	
 	$checked = JHTML::_('grid.checkedout', $row, $i );
##ifdefFieldchecked_out_timeEnd##
##ifdefFieldchecked_outEnd##
##ifnotdefFieldchecked_out_timeStart##
##ifnotdefFieldchecked_outStart## 	
 	$checked = JHTML::_('grid.id', $i, $row->##primary##);
##ifnotdefFieldchecked_out_timeEnd##
##ifnotdefFieldchecked_outEnd## 	
  	$published = JHTML::_('grid.published', $row, $i ); 	
 	
  ##codeend##
	<tr class="##codestart## echo "row$k"; ##codeend##">
		
		<td align="center">##codestart## echo $this->pagination->getRowOffset($i); ##codeend##.</td>
        
        <td>##codestart## echo $checked  ##codeend##</td>	
##fieldslist##		
	</tr>
##codestart##
  $k = 1 - $k;
  endfor;
  else:
  ##codeend##
	<tr>
		<td colspan="12">
			##codestart## echo JText::_( 'There are no items present' ); ##codeend##
		</td>
	</tr>
	##codestart##
  endif;
  ##codeend##
</tbody>
</table>
</div>
<input type="hidden" name="option" value="##com_component##" />
<input type="hidden" name="task" value="##name##" />
<input type="hidden" name="view" value="##name##" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="##codestart## echo $this->lists['order']; ##codeend##" />
<input type="hidden" name="filter_order_Dir" value="" />
##codestart## echo JHTML::_( 'form.token' ); ##codeend##
</form>  	