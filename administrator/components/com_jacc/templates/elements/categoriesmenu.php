
		$extension = JRequest::getString('extension');
		JSubMenuHelper::addEntry(
			JText::_('##COM_COMPONENT##_SUBMENU_##FIRSTNAME##_CATEGORIES'),
			'index.php?option=com_categories&extension=com_##component##.##firstnameplural####firstextra##',
			$vName == 'categories' || $extension == 'com_##component##.##firstnameplural####firstextra##'
		);