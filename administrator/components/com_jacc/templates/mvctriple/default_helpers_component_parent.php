<?php defined('_JEXEC') or die; ?>
	##ifdefFieldasset_idStart##
	/**
	 * Method to return a list of all categories that a user has permission for a given action
	 *
	 * @param   string  $action     The name of the section within the component from which to retrieve the actions.
	 *
	 * @return  array  List of categories that this group can do this action to (empty array if none). Categories must be published.
	 *
	 * @since   11.1
	 */
	public function getAuthorised##Nameplural####extra##($action)
	{
		// Brute force method: get all published ##name## rows for the component and check each one
		// TODO: Modify the way permissions are stored in the db to allow for faster implementation and better scaling
		$user		= JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('b.id AS id, a.name AS asset_name')->from('##table## AS b')
			->innerJoin('#__assets AS a ON b.asset_id = a.id')##ifdefFieldpublishedStart##->where('b.published = 1');##ifdefFieldpublishedEnd####ifdefFieldstateStart##->where('b.state = 1');##ifdefFieldstateEnd##
		$db->setQuery($query);
		$all##Nameplural####extra## = $db->loadObjectList('id');
		$allowed##Nameplural####extra## = array();
		foreach ($all##Nameplural####extra## as $##name##)
		{
			if ($user->authorise($action, $##name##->asset_name))
			{
				$allowed##Nameplural####extra##[] = (int) $##name##->id;
			}
		}
		return $allowed##Nameplural####extra##;
	}

	##ifdefFieldasset_idEnd##
