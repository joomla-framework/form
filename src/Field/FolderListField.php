<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Field;

use Joomla\Filesystem\Folder;
use Joomla\Form\FormHelper;
use Joomla\Form\Html\Select as HtmlSelect;

FormHelper::loadFieldClass('list');

/**
 * Folder List Form Field class for the Joomla! Framework.
 *
 * Supports an HTML select list of folders
 *
 * @since  1.0
 */
class FolderListField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'FolderList';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0
	 */
	protected function getOptions()
	{
		$options = array();

		$select = new HtmlSelect;

		// Try to inject the text object into the field
		try
		{
			$select->setText($this->getText());
		}
		catch (\RuntimeException $exception)
		{
			// A Text object was not set, ignore the error and try to continue processing
		}

		// Initialize some field attributes.
		$filter = (string) $this->element['filter'];
		$exclude = (string) $this->element['exclude'];
		$hideNone = (string) $this->element['hide_none'];
		$hideDefault = (string) $this->element['hide_default'];

		// Get the path in which to search for file options.
		$path = (string) $this->element['directory'];

		if (!is_dir($path))
		{
			$path = JPATH_ROOT . '/' . $path;
		}

		// Prepend some default options based on field attributes.
		if (!$hideNone)
		{
			$text = $this->translateOptions
				? $this->getText()->alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname))
				: '- None Selected -';

			$options[] = $select->option('-1', $text);
		}

		if (!$hideDefault)
		{
			$text = $this->translateOptions
				? $this->getText()->alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname))
				: '- Use Default -';

			$options[] = $select->option('', $text);
		}

		// Get a list of folders in the search path with the given filter.
		$folders = Folder::folders($path, $filter);

		// Build the options list from the list of folders.
		if (is_array($folders))
		{
			foreach ($folders as $folder)
			{
				// Check to see if the file is in the exclude mask.
				if ($exclude)
				{
					if (preg_match(chr(1) . $exclude . chr(1), $folder))
					{
						continue;
					}
				}

				$options[] = $select->option($folder, $folder);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
