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

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of ##Name## records
 *
 * @author      ##author##
 * @package     Joomla.Administrator
 * @subpackage  com_##component##
 * @since       2.5
 */
// TODO: plural function
class ##Component##Model##Nameplural####extra## extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				##fields##
				##ifdefFieldcategory_idStart## 'category_title',##ifdefFieldcategory_idEnd####ifdefFieldcatidStart## 'category_title',##ifdefFieldcatidEnd####ifdefFieldaccessStart## 'access_level',##ifdefFieldaccessEnd##
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$context	= $this->context;

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout', 'default'))
		{
			$context .= '.'.$layout;
		}

		##ifdefFieldextensionStart##
		//$extension = $app->getUserStateFromRequest('com_categories.categories.filter.extension', 'extension', 'com_content', 'cmd');

		//$this->setState('filter.extension', $extension);
		//$parts = explode('.', $extension);

		// extract the component name
		//$this->setState('filter.component', $parts[0]);

		// extract the optional section name
		//$this->setState('filter.section', (count($parts) > 1) ? $parts[1] : null);

		##ifdefFieldextensionEnd##
		// Load the filter state.
		$search = $this->getUserStateFromRequest($context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		##ifdefFieldlevelStart##
		$level = $this->getUserStateFromRequest($context.'.filter.level', 'filter_level', 0, 'int');
		$this->setState('filter.level', $level);

		##ifdefFieldlevelEnd##
		##ifdefFieldaccessStart##
		$accessId = $this->getUserStateFromRequest($context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $accessId);

		##ifdefFieldaccessEnd##
		##ifdefFieldcreated_byStart##
		$authorId = $app->getUserStateFromRequest($context.'.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		##ifdefFieldcreated_byEnd##
		##ifdefFieldcreated_user_idStart##
		$authorId = $app->getUserStateFromRequest($context.'.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		##ifdefFieldcreated_user_idEnd##
		##ifdefFieldpublishedStart##
		$published = $this->getUserStateFromRequest($context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		##ifdefFieldpublishedEnd##
		##ifdefFieldstateStart##
		$state = $this->getUserStateFromRequest($context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		##ifdefFieldstateEnd##
		##ifdefFieldcategory_idStart##
		$categoryId = $this->getUserStateFromRequest($context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		##ifdefFieldcategory_idEnd##
		##ifdefFieldcatidStart##
		$categoryId = $this->getUserStateFromRequest($context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		##ifdefFieldcatidEnd##
		##ifdefFieldlanguageStart##
		$language = $this->getUserStateFromRequest($context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		##ifdefFieldlanguageEnd##
		// Load the parameters.
		$params = JComponentHelper::getParams('##com_component##');
		$this->setState('params', $params);

		// List state information.
		##ifnotdefFieldparent_idStart##
		parent::populateState('a.##title##', 'asc');
		##ifnotdefFieldparent_idEnd##
		##ifdefFieldparent_idStart##
		parent::populateState('a.lft', 'asc');
		##ifdefFieldparent_idEnd##
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		##ifdefFieldextensionEnd##
		//$id	.= ':'.$this->getState('filter.extension');
		##ifdefFieldextensionEnd##
		##ifdefFieldaccessStart##
		$id	.= ':'.$this->getState('filter.access');
		##ifdefFieldaccessEnd##
		##ifdefFieldpublishedStart##
		$id	.= ':'.$this->getState('filter.published');
		##ifdefFieldpublishedEnd##
		##ifdefFieldstateStart##
		$id	.= ':'.$this->getState('filter.state');
		##ifdefFieldstateEnd##
		##ifdefFieldcategory_idStart##
		$id	.= ':'.$this->getState('filter.category_id');
		##ifdefFieldcategory_idEnd##
		##ifdefFieldcatidStart##
		$id	.= ':'.$this->getState('filter.category_id');
		##ifdefFieldcatidEnd##
		##ifdefFieldcreated_byStart###
		$id	.= ':'.$this->getState('filter.author_id');
		##ifdefFieldcreated_byEnd##
		##ifdefFieldcreated_user_idStart###
		$id	.= ':'.$this->getState('filter.author_id');
		##ifdefFieldcreated_user_idEnd##
		##ifdefFieldlanguageStart##
		$id	.= ':'.$this->getState('filter.language');
		##ifdefFieldlanguageEnd##

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				// TODO nicer
				//'a.id, a.title, a.alias, a.checked_out, a.checked_out_time, a.catid' .
				//', a.state, a.access, a.created, a.created_by, a.ordering, a.featured, a.language, a.hits' .
				//', a.publish_up, a.publish_down'
				##ifdefFieldmodified_timeStart##
				##ifdefFieldcreated_timeStart##
				'CASE WHEN a.modified_time = 0 THEN a.created_time ELSE a.modified_time END as modified_time,' .
				##ifdefFieldcreated_timeEnd##
				##ifdefFieldcreatedStart##
				'CASE WHEN a.modified_time = 0 THEN a.created ELSE a.modified_time END as modified_time,' .
				##ifdefFieldcreatedEnd##
				##ifdefFieldmodified_timeEnd##
				##ifdefFieldmodifiedStart##
				##ifdefFieldcreatedStart##
				'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END as modified,' .
				##ifdefFieldcreatedEnd##
				##ifdefFieldcreated_timeStart##
				'CASE WHEN a.modified = 0 THEN a.created_time ELSE a.modified END as modified,' .
				##ifdefFieldcreated_timeEnd##
				##ifdefFieldmodifiedEnd##
				'a.*'
			)
		);
		$query->from($db->quoteName('##table##').' AS a');

##fieldslist##
		##ifdefFieldlanguageStart##
		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', $db->quoteName('#__languages').' AS l ON l.lang_code = a.language');

		##ifdefFieldlanguageEnd##
		##ifdefFieldchecked_outStart##
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		##ifdefFieldchecked_outEnd##
		##ifdefFieldaccessStart##
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		##ifdefFieldaccessEnd##
		##ifdefFieldcatidStart##
		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.category_id');

		##ifdefFieldcategory_idEnd##
		##ifdefFieldcreated_byStart##
		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		##ifdefFieldcreated_byEnd##
		##ifdefFieldcreated_user_idStart##
		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_user_id');

		##ifdefFieldcreated_user_idEnd##
		##ifdefFieldmodified_byStart##
		// Join over the users for the author.
		$query->select('uam.name AS modifier_name');
		$query->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

		##ifdefFieldmodified_byEnd##
		##ifdefFieldmodified_user_idStart##
		// Join over the users for the author.
		$query->select('uam.name AS modifier_name');
		$query->join('LEFT', '#__users AS uam ON uam.id = a.modified_user_id');

		##ifdefFieldmodified_user_idEnd##
		##ifdefFieldextensionStart##
		// Filter by extension
		//if ($extension = $this->getState('filter.extension')) {
		//	$query->where('a.extension = '.$db->quote($extension));
		//}
		// TODO: just a workaround to get rid of the ROOT in the list.
		$query->where('a.id <> 1');

		##ifdefFieldextensionEnd##
		##ifdefFieldlevelStart##
		// Filter on the level.
		if ($level = $this->getState('filter.level')) {
			$query->where('a.level <= '.(int) $level);
		}

		##ifdefFieldlevelEnd##
		##ifdefFieldaccessStart##
		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN ('.$groups.')');
		}

		##ifdefFieldaccessEnd##
		##ifdefFieldstateStart##
		// Filter by published state
		$state = $this->getState('filter.state');
		if (is_numeric($state))
		{
			$query->where('a.state = ' . (int) $state);
		}
		elseif ($state === '') {
			$query->where('(a.state IN (0, 1))');
		}

		##ifdefFieldstateEnd##
		##ifdefFieldpublishedStart##
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		##ifdefFieldpublishedEnd##
		##ifdefFieldcatidStart##
		// Filter by published
		// Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= '.(int) $lft);
			$query->where('c.rgt <= '.(int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN ('.$categoryId.')');
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('c.level <= '.((int) $level + (int) $baselevel - 1));
		}
		##ifdefFieldcatidEnd##
		##ifdefFieldcategory_idStart##
		// Filter by published
		// Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= '.(int) $lft);
			$query->where('c.rgt <= '.(int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.category_id IN ('.$categoryId.')');
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('c.level <= '.((int) $level + (int) $baselevel - 1));
		}
		##ifdefFieldcategory_idEnd##
		##ifdefFieldcreated_byStart##
		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by '.$type.(int) $authorId);
		}

		##ifdefFieldcreated_byEnd##
		##ifdefFieldcreated_user_idStart##
		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_user_id '.$type.(int) $authorId);
		}

		##ifdefFieldcreated_user_idEnd##

		##ifdefField<?php echo $this->hident ?>Start##
		// Filter by search in ##title##.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = '.(int) substr($search, 3));
			}
			##ifdefFieldcreated_byStart##
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->Quote('%'.$db->escape(substr($search, 7), true).'%');
				$query->where('(ua.name LIKE '.$search.' OR ua.username LIKE '.$search.')');
			}
			##ifdefFieldcreated_byEnd##
			##ifdefFieldcreated_user_idStart##
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->Quote('%'.$db->escape(substr($search, 7), true).'%');
				$query->where('(ua.name LIKE '.$search.' OR ua.username LIKE '.$search.')');
			}
			##ifdefFieldcreated_user_idEnd##
			else
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.##title## LIKE '.$search.##ifdefFieldaliasStart##' OR a.alias LIKE '.$search.##ifdefFieldaliasEnd##')');
			}
		}

		##ifdefField<?php echo $this->hident ?>End##
		##ifdefFieldlanguageStart##
		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = '.$db->quote($language));
		}

		##ifdefFieldlanguageEnd##

		// Add the list ordering clause.
		##ifdefFieldlftStart##
		$orderCol	= $this->state->get('list.ordering', 'a.lft');
		##ifdefFieldlftEnd##
		##ifnotdefFieldlftStart##
		$orderCol	= $this->state->get('list.ordering', 'a.##title##');
		##ifnotdefFieldlftStart##
		$orderDirn	= $db->escape($this->state->get('list.direction', 'asc'));
		// TODO
		$order = null;
		switch($orderCol)
		{
			##ifdefFieldorderingStart##
			case 'a.ordering':
				##ifdefFieldcategory_idStart##
				$order[] = $db->escape('c.title').' '.$orderDirn;
				##ifdefFieldcategory_idEnd##
				##ifdefFieldcatidStart##
				$order[] = $db->escape('c.title').' '.$orderDirn;
				##ifdefFieldcatidEnd##
				$order[] = $db->escape('c.title').' '.$orderDirn;
				break;
			##ifdefFieldorderingEnd##
			##ifdefFieldcategory_idStart##
			case 'category_title':
				$order[] = $db->escape('c.title').' '.$orderDirn;
				##ifdefFieldorderingStart##
				$order[] = $db->escape('c.title').' '.$orderDirn;
				##ifdefFieldorderingEnd##
				break;
			##ifdefFieldcategory_idEnd##
			##ifdefFieldcatidStart##
			case 'category_title':
				$order[] = $db->escape('c.title').' '.$orderDirn;
				##ifdefFieldorderingStart##
				$order[] = $db->escape('c.title').' '.$orderDirn;
				##ifdefFieldorderingEnd##
				break;
			##ifdefFieldcatidEnd##
			##ifdefFieldlanguageStart##
			case 'language':
				$order[] = $db->escape('l.title').' '.$orderDirn;
				break;
			##ifdefFieldlanguageEnd##
			##ifdefFieldaccessStart##
			case 'access_level':
				$order[] = $db->escape('ag.title').' '.$orderDirn;
				break;
			##ifdefFieldaccessEnd##
			default:
				$order[] = $db->escape($orderCol).' '.$orderDirn;
				break;
		}

		$query->order(implode(', ',$order));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}

	##ifdefFieldcreated_byStart##
	/**
	 * Build a list of authors
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text');
		$query->from('#__users AS u');
		$query->join('INNER', '##table## AS c ON c.created_by = u.id');
		$query->group('u.id, u.name');
		$query->order('u.name');

		// Setup the query
		$db->setQuery($query->__toString());

		// Return the result
		return $db->loadObjectList();
	}

	##ifdefFieldcreated_byEnd##
	##ifdefFieldcreated_user_idStart##
	/**
	 * Build a list of authors
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text');
		$query->from('#__users AS u');
		$query->join('INNER', '##table## AS c ON c.created_user_id = u.id');
		$query->group('u.id, u.name');
		$query->order('u.name');

		// Setup the query
		$db->setQuery($query->__toString());

		// Return the result
		return $db->loadObjectList();
	}

	##ifdefFieldcreated_user_idEnd##
	##ifdefFieldaccessStart##
	/**
	 * Method to get a list of articles.
	 * Overridden to add a check for access levels.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 * @since	1.6.1
	 */
	public function getItems()
	{
		$items	= parent::getItems();
		$app	= JFactory::getApplication();
		if ($app->isSite())
		{
			$user	= JFactory::getUser();
			$groups	= $user->getAuthorisedViewLevels();

			for ($x = 0, $count = count($items); $x < $count; $x++)
			{
				//Check the access level. Remove articles the user shouldn't see
				if (!in_array($items[$x]->access, $groups))
				{
					unset($items[$x]);
				}
			}
		}
		return $items;
	}
	##ifdefFieldaccessEnd##
}
##codeend##
