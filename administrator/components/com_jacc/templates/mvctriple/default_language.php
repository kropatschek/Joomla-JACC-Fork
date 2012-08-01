<?php defined('_JEXEC') or die;
foreach ($this->formfield as $field)
{
	if ($field->get('additional')) continue;
	if ($field->get('key') == 'params') continue;

	$key = strtoupper($field->get('key'));
	$label = $field->get('label', array());
	$label = (array_key_exists($this->language, $label) ? $label[$this->language] : $label['en-GB']);
	//$label = (property_exists($label, 'en-GB') ? $label->en-GB : $label->en-GB);
	$description = $field->get('description', array());
	$description = (array_key_exists($this->language, $description) ? $description[$this->language] : $description['en-GB']);
	$values = $field->get('values', array());
	$values = (array_key_exists($this->language, $values) ? $values[$this->language] : (array_key_exists('en-GB', $values) ? $values['en-GB'] : ''));

	//$description = (property_exists($description, 'en-GB') ? $description->en-GB : $description->en-GB);

	switch($field->get('key')) {
		case 'catid':
		case 'category_id':
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
		//case $this->hident:
			break;
		case 'featured':
?>
##COM_COMPONENT##_##NAME##_FIELD_<?php echo $key ?>_DESC="<?php echo $label ?>"
<?php
			break;
		case $this->primary:
			if ($this->primary == $this->hident):
?>
##COM_COMPONENT##_##NAME##_HEADING_<?php echo $key ?>="<?php echo $label ?>"
<?php
			endif;
			break;
		default:
?>
##COM_COMPONENT##_##NAME##_FIELD_<?php echo $key ?>_LABEL="<?php echo $label ?>"
##COM_COMPONENT##_##NAME##_FIELD_<?php echo $key ?>_DESC="<?php echo $description ?>"
<?php echo $values; ?>
<?php if ($field->get('list') == true): ?>
##COM_COMPONENT##_##NAME##_HEADING_<?php echo $key ?>="<?php echo $label ?>"
##COM_COMPONENT##_##NAME##_EMPTY_<?php echo $key ?>=""
<?php endif; ?>
<?php
			break;
	}
}
?>