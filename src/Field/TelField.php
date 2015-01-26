<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Field;

/**
 * Telephone Form Field class for the Joomla! Framework.
 *
 * Supports an HTML5 text field for telephone numbers.
 *
 * @link   http://www.w3.org/TR/html-markup/input.tel.html
 * @see    \Joomla\Form\Rule\TelRule for telephone number validation
 * @since  1.0
 */
class TelField extends TextField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Tel';
}
