<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Field;

use Joomla\Form\Html\Select as HtmlSelect;

/**
 * List Form Field class for the Joomla! Framework.
 *
 * Supports a generic list of options.
 *
 * @since  1.0
 */
class ListField extends \Joomla\Form\Field
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'List';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		$html = array();
		$attr = '';

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
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true')
		{
			$html[] = $select->genericlist($options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
		}
		else
		// Create a regular list.
		{
			$html[] = $select->genericlist($options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

		return implode($html);
	}

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

		/** @var \SimpleXMLElement $option */
		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			$text = $this->translateOptions
				? $this->getText()->alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname))
				: trim((string) $option);

			// Create a new option object based on the <option /> element.
			$tmp = $select->option((string) $option['value'], $text, 'value', 'text', ((string) $option['disabled'] == 'true'));

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
