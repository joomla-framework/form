<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Field;

/**
 * Text Form Field class for the Joomla! Framework.
 *
 * Supports a one line text field.
 *
 * @link   http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since  1.0
 */
class TextField extends \Joomla\Form\Field
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Text';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$autofocus = ((string) $this->element['autofocus'] == 'true') ? ' autofocus' : '';
		$autocomplete = $this->element['autocomplete'] ? ' autocomplete="' . (string) $this->element['autocomplete'] . '"' : '';

		// Temporary workaround to make sure the placeholder can be set without coupling to joomla/language
		$placeholder = '';

		if ($this->element['placeholder'])
		{
			try
			{
				$placeholder = ' placeholder="' . $this->getText()->translate((string) $this->element['placeholder']) . '"';
			}
			catch (\RuntimeException $e)
			{
				$placeholder = ' placeholder="' . (string) $this->element['placeholder'] . '"';
			}
		}

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return '<input type="' . strtolower($this->type) . '" name="' . $this->name . '" id="' . $this->id . '"'
			. ' value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled
			. $readonly . $onchange . $maxLength . $placeholder . $autofocus . $autocomplete . '/>';
	}
}
