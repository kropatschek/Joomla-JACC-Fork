
		$extension = JRequest::getString('extension');
		JSubMenuHelper::addEntry(
			JText::_('##COM_COMPONENT##_##FIRSTNAME##_CATEGORIES_SUBMENU'),
			'index.php?option=com_categories&extension=com_##component##.##firstnameplural####firstextra##',
			$vName == 'categories' || $extension == 'com_##component##.##firstnameplural####firstextra##'
		);