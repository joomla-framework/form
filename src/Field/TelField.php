<?php
/**
 * Part of the Joomla Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Field;

/**
 * Form Field class for the Joomla Framework.
 * Supports a text field telephone numbers.
 *
 * @link   http://www.w3.org/TR/html-markup/input.tel.html
 * @see    \Joomla\Form\Rule\Tel for telephone number validation
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
