<?php defined('_JEXEC') or die; ?>
##codestart##
/**
##ifdefVarpackageStart##
 * @package    ##package## Administrator
 * @subpackage ##com_component##
##ifdefVarpackageEnd##
##ifnotdefVarpackageStart##
 * @package    ##com_component## Administrator
##ifnotdefVarpackageEnd##
 * @version    ##version##
 * @copyright  Copyright (C) ##year##, ##author##. All rights reserved.
 * @license    ##license##
*/

defined('_JEXEC') or die;

//-global $alt_libdir;
JLoader::import('joomla.application.component.modelitem');//-, $alt_libdir);
jimport('joomla.application.component.helper');

JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_##component##/tables');
/**
 * ##Component##Model##Name##
 * @author $Author$
 */


class ##Component##Model##Name##  extends JModelItem {



	protected $context = 'com_##component##.##name##';
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	public function populateState()
	{
		$app = JFactory::getApplication();

		//$params	= $app->getParams();

		// Load the object state.
		$id	= JRequest::getInt('##primary##');
		$this->setState('##name##.##primary##', $id);

		// Load the parameters.
		//TODO: componenthelper
		//$this->setState('params', $params);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('##name##.##primary##');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($id = null)
	{
		if ($this->_item === null) {

			$this->_item = false;

			if (empty($id)) {
				$id = $this->getState('##name##.##primary##');
			}

			// Get a level row instance.
			$table = JTable::getInstance('##Name##', 'Table');


			// Attempt to load the row.
			if ($table->load($id)) {

				// Check published state.
				if ($published = $this->getState('filter.published')) {

					if ($table->state != $published) {
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$this->_item = JArrayHelper::toObject($table->getProperties(1), 'JObject');

			} else if ($error = $table->getError()) {

				$this->setError($error);
			}
		}
##ifdefFieldparamsStart##
		$params = json_decode($this->_item ->params);
		$this->_item->params = new JObject();
		$this->_item->params ->setProperties(JArrayHelper::fromObject($params));
##ifdefFieldparamsEnd##

		return $this->_item;
	}

}
##codeend##