<?php
defined('JPATH_BASE') or die;
global $alt_libdir;
JLoader::import('joomla.form.fields.list', $alt_libdir);
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_##component##'.DS.'helpers'.DS.'##component##.php');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormField##Component##Category extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = '##Component##Category';
	
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getInput()
	{
	
		$size		= ($v = $this->element['size']) ? ' size="'.$v.'"' : '';
		$class		= ($v = $this->element['class']) ? 'class="'.$v.'"' : 'class="inputbox"';
		$extension		= ($v = $this->element['extension']) ? $v : '';
		$readonly	= $this->element['readonly'] == 'true' ? ' readonly="readonly"' : '';
		$onchange	= ($v = $this->element['onchange']) ? ' onchange="'.$v.'"' : '';
		$maxLength	= ($v = $this->element['maxlength']) ? ' maxlength="'.$v.'"' : '';
		$multiple	= ($v = $this->element['multiple']) ? ' multiple="'.$v.'"' : '';
		$input = JHtml::_('##component##.categories', $extension, $this->value, $this->name, '- Select Category -', array('attributes'=>$class.$size.$readonly.$onchange.$maxLength.$multiple, 'filter.published' => 1)); 
	
		return $input ;
	}
}
?>