<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Field;

use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Form\FormHelper;
use Joomla\Form\Html\Select as HtmlSelect;

FormHelper::loadFieldClass('list');

/**
 * File List Form Field class for the Joomla! Framework.
 *
 * Supports an HTML select list of files.
 *
 * @since  1.0
 */
class FileListField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'FileList';

	/**
	 * Method to get the list of files for the field options.
	 *
	 * Specify the target directory with a directory attribute
	 * Attributes allow an exclude mask and stripping of extensions from file name.
	 * Default attribute may optionally be set to null (no file) or -1 (use a default).
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
		$stripExt = (string) $this->element['stripext'];
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

		// Get a list of files in the search path with the given filter.
		$files = Folder::files($path, $filter);

		// Build the options list from the list of files.
		if (is_array($files))
		{
			foreach ($files as $file)
			{
				// Check to see if the file is in the exclude mask.
				if ($exclude)
				{
					if (preg_match(chr(1) . $exclude . chr(1), $file))
					{
						continue;
					}
				}

				// If the extension is to be stripped, do it.
				if ($stripExt)
				{
					$file = File::stripExt($file);
				}

				$options[] = $select->option($file, $file);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
