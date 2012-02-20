
		$extension = JRequest::getString('extension');
		JSubMenuHelper::addEntry(
			JText::_('COM_##COMPONENT##_##FIRSTNAME##'),
			'index.php?option=com_categories&extension=com_##component##.##firstname##',
			$vName == 'categories' || $extension == 'com_##component##.##firstname##'
		);