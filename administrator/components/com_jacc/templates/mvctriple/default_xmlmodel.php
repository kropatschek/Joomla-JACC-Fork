<?php defined('_JEXEC') or die;?>
<<?php echo '?'?>xml version="1.0" encoding="utf-8"##codeend##
<form>
	<fields>
<?php foreach ($this->formfield as $field)
{
	if ($field->get('additional')) continue;
	if ($field->get('key') == 'params') continue;
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
		##ifdefFieldextensionStart##
		<!-- <field
			name="<?php echo $field->get('key') ?>"
			type="##name##edit"
			extension="##com_component##.##nameplural####extra##"
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
			class="inputbox"
			required="true">
		</field> -->
		##ifdefFieldextensionEnd##
		<field
			name="<?php echo $field->get('key') ?>"
			type="##name##edit"
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
				case null:
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
			type="<?php echo $field->get('formfield') ?>"
<?php
					if ($field->get('formfield') != 'hidden')
					{
?>
			label="<?php echo $label ?>"
			description="<?php echo $description ?>"
<?php
					}
					$element = clone $field;
					unset($element->key);
					unset($element->formfield);
					unset($element->label);
					unset($element->description);
					unset($element->delete);
					unset($element->alt);
					unset($element->group);
					unset($element->foreign);
					unset($element->foreignkey);
					unset($element->reltable);
					unset($element->_errors);
					unset($element->ordering);
					unset($element->fieldtype);
					unset($element->hident);
					unset($element->comment);
					unset($element->list);
					unset($element->options);
					unset($element->values);

					foreach ($element as $attribute => $value)
					{
// 						if ($attribute == 'key') continue;
// 						if ($attribute == 'formfield') continue;
// 						if ($attribute == 'label') continue;
// 						if ($attribute == 'description') continue;
// 						if ($attribute == 'delete') continue;
// 						if ($attribute == 'alt') continue;
// 						if ($attribute == 'group') continue;
// 						if ($attribute == 'foreign') continue;
// 						if ($attribute == 'foreignkey') continue;
// 						if ($attribute == 'reltable') continue;
						if ($attribute == 'required' || $attribute == 'readonly' || $attribute == 'disabled')
						{
							$value = $value ? 'true' : 'false';
						}
?>
			<?php echo $attribute; ?>="<?php echo $value; ?>"
<?php
					}
?>
		>
<?php
					if ($field->get('options')){
						echo $field->get('options');
					}
?>
		</field>
<?php
		}
	}

}
?>
		##ifdefFieldasset_idEnd##
		<field name="rules" type="rules" label="JFIELD_RULES_LABEL"
		translate_label="false" class="inputbox" filter="rules"
		component="##com_component##" section="##name####ifdefFieldparent_idStart##.##nameplural####extra####ifdefFieldparrent_idEnd##" validate="rules"
		/>
		##ifdefFieldasset_idEnd##
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