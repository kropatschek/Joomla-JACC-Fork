<?php defined('_JEXEC') or die;
$format = '%Y-%m-%d';
$datesize = 10;
?>
<<?php echo '?'?>xml version="1.0" encoding="utf-8"##codeend##
<form>
	<fields>
<?php foreach ($this->formfield as $field)
{
	if ($field->get('additional')) continue;
	if ($field->get('key') == 'params') continue;
	$required = $field->get('required') ? $field->get('required') : 'false';
	$size= $field->get('size') ? $field->get('size') : '40';
	//$label= $field->get('label') ? $field->get('label') : ucfirst($field->get('key'));
	$label = '##COM_COMPONENT##_##NAME##_FIELD_'.strtoupper($field->get('key')).'_LABEL';
	$description = '##COM_COMPONENT##_##NAME##_FIELD_'.strtoupper($field->get('key')).'_DESC';

	switch($field->get('key')) {
		case 'catid':
		case 'category_id':
			if($this->uses_categories):
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="category"
			extension="##com_component##.##nameplural####extra##"
			label="JCATEGORY"
			description="JFIELD_CATEGORY_DESC"
			class="inputbox"
			required="true">
		</field>
<?php
			endif;
			break;
		case 'parent_id':
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="##name##edit"
			##ifdefFieldextensionStart##
			extension="##com_component##.##nameplural####extra##"
			##ifdefFieldextensionEnd##
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			required="true">
		</field>
<?php
			break;
		case $this->primary:
?>
		<field
			name="<?php echo $this->primary ?>"
			type="hidden"
			default="0"
			required="true"
			readonly="true"/>
<?php
			break;
		case 'asset_id':
?>
		<field
			name="asset_id"
			type="hidden"
			filter="unset"/>
<?php
			break;
		case 'featured':
?>
		<field name="featured"
			type="list"
			label="JFEATURED"
			description="<?php echo $description ?>"
			default="0">
			<option value="0">JNO</option>
			<option value="1">JYES</option>
		</field>
<?php
			break;
		case 'image':
?>
		<field name="image"
			type="media"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>">
<?php
			break;
		case 'published':
?>
		<field
			id="published"
			name="published"
			type="list"
			label="JSTATUS"
			description="JFIELD_PUBLISHED_DESC"
			class="inputbox"
			size="1"
			default="1">
			<option
				value="1">
				JPUBLISHED</option>
			<option
				value="0">
				JUNPUBLISHED</option>
			<option
				value="2">
				JARCHIVED</option>
			<option
				value="-2">
				JTRASHED</option>
		</field>
<?php
			break;
		case 'state':
?>
		<field
			id="state"
			name="state"
			type="list"
			label="JSTATUS"
			description="JFIELD_PUBLISHED_DESC"
			class="inputbox"
			size="1"
			default="1">
			<option
				value="1">
				JPUBLISHED</option>
			<option
				value="0">
				JUNPUBLISHED</option>
			<option
				value="2">
				JARCHIVED</option>
			<option
				value="-2">
				JTRASHED</option>
		</field>
<?php
			break;
		case 'publish_up':
?>
		<field
			id="publish_up"
			name="publish_up"
			type="calendar"
			label="JGLOBAL_FIELD_PUBLISH_UP_LABEL"
			description="JGLOBAL_FIELD_PUBLISH_UP_DESC"
			class="inputbox"
			format="%Y-%m-%d %H:%M:%S"
			size="22"
			filter="user_utc" />
<?php
			break;
		case 'publish_down':
?>
		<field
			id="publish_down"
			name="publish_down"
			type="calendar"
			label="JGLOBAL_FIELD_PUBLISH_DOWN_LABEL"
			description="JGLOBAL_FIELD_PUBLISH_DOWN_DESC"
			class="inputbox"
			format="%Y-%m-%d %H:%M:%S"
			size="22"
			filter="user_utc" />
<?php
			break;
		case 'language':
?>
		<field
			name="language"
			type="contentlanguage"
			label="JFIELD_LANGUAGE_LABEL"
			description="JFIELD_LANGUAGE_DESC"
			class="inputbox">
			<option value="*">JALL</option>
		</field>
<?php
			break;
		case 'metakey':
?>
		<field
			id="metakey"
			name="metakey"
			type="textarea"
			label="JFIELD_META_KEYWORDS_LABEL"
			description="JFIELD_META_KEYWORDS_DESC"
			class="inputbox"
			rows="5"
			cols="50" />
<?php
			break;
		case 'metadesc':
?>
		<field
			id="metadesc"
			name="metadesc"
			type="textarea"
			label="JFIELD_META_DESCRIPTION_LABEL"
			description="JFIELD_META_DESCRIPTION_DESC"
			class="inputbox"
			rows="5"
			cols="50" />
<?php
			break;
		case 'access':
?>
		<field
			id="access"
			name="access"
			type="accesslevel"
			label="JFIELD_ACCESS_LABEL"
			description="JFIELD_ACCESS_DESC"
			class="inputbox"
			size="1" />
<?php
			break;
		default:
		switch (strtolower($field->get('formfield'))) {
			case 'list':
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="list"
			class="inputbox"
			default="1"
			required="<?php echo $required ?>"
			size="1"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>">
			<option
				value="0">
				Option None</option>
			<option
				value="1">
				First Option</option>
			<option
				value="2">
				Second Option</option>
			<option
				value="3">
				And so on</option>
		</field>
<?php
				break;
			case 'editor':
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="editor"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			buttons="readmore,pagebreak"/>
<?php
				break;
			case 'text':
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="text"
			required="<?php echo $required ?>"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			size="40"/>
<?php
				break;
			case 'integer':
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="text"
			required="<?php echo $required ?>"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			size="10"/>
<?php
				break;
			case 'float':
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="text"
			required="<?php echo $required ?>"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			size="10"/>
<?php
				break;
			case 'calendar':
				if ($field->get('fieldtype') == 'datetime')
				{
					$format = '%Y-%m-%d %H-%M-%S';
					$datesize = 16;
				}
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="calendar"
			required="<?php echo $required ?>"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			size="<?php echo $datesize ?>"
			format="<?php echo $format ?>"/>
<?php
				break;
			case 'boolean':
?>
			<field
				name="<?php echo $field->get('key') ?>"
				type="list"
				default=""
				label="<?php echo $label ?>"
				description="<?php echo $description ?>">
				<option
					value="0">No</option>
				<option
					value="1">Yes</option>
			</field>
<?php
				break;
			case 'null':
?>
		<field
			name="<?php echo $field->get('key') ?>"
			type="hidden"
			filter="unset"/>
<?php
				break;
			default:
?>
		<field
			name="<?php echo $field->get('key') ?>"
			required="<?php echo $required ?>"
			type="<?php echo $field->get('formfield') ?>"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			size="<?php echo $size ?>"/>
<?php
		}
	}

}
?>
		<field name="rules" type="rules" label="JFIELD_RULES_LABEL"
		translate_label="false" class="inputbox" filter="rules"
		component="##com_component##" section="##name##" validate="rules"
		/>
	</fields>
<?php if (isset($this->formfield['params'])): ?>

	<fields name="params">
		<fieldset
			name="basic">
			<field
				name="example_param"
				type="list"
				default=""
				label="Params_Example_Label"
				description="Params_Example_Desc">
				<option
					value="0">No</option>
				<option
					value="1">Yes</option>
			</field>
		</fieldset>
	</fields>
<?php endif; ?>
</form>