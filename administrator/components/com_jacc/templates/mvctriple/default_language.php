<?php defined('_JEXEC') or die;
foreach ($this->formfield as $field)
{
	if ($field->get('additional')) continue;
	if ($field->get('key') == 'params') continue;
	$label = $field->get('label') ? $field->get('label') : ucfirst($field->get('key'));
	$key = strtoupper($field->get('key'));

	switch($field->get('key')) {
		case 'catid':
		case 'category_id':
		case $this->primary:
		case 'asset_id':
		case 'image':
		case 'published':
		case 'state':
		case 'publish_up':
		case 'publish_down':
		case 'language':
		case 'metakey':
		case 'metadesc':
		case 'access':
		case 'created_by':
		case 'created_user_id':
		case 'created_time':
		case 'modified_by':
		case 'modified_user_id':
		case 'modified_time':
		case 'checked_out':
		case 'checked_out_time':
			break;
		case 'featured':
?>
##COM_COMPONENT##_##NAME##_FIELD_<?php echo $key ?>_DESC="<?php echo $label ?>"
<?php
			break;
		default:
?>
##COM_COMPONENT##_##NAME##_FIELD_<?php echo $key ?>_LABEL="<?php echo $label ?>"
##COM_COMPONENT##_##NAME##_FIELD_<?php echo $key ?>_DESC="<?php echo $label ?>"
##COM_COMPONENT##_##NAME##_HEADING_<?php echo $key ?>="<?php echo $label ?>"
##COM_COMPONENT##_##NAME##_EMPTY_<?php echo $key ?>=""
<?php
			break;
	}
}
?>