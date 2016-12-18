<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Field;

use Joomla\Form\FormHelper;

FormHelper::loadFieldClass('text');

/**
 * E-mail Form Field class for the Joomla! Framework.
 *
 * Supports an HTML5 text field for e-mail addresses.
 *
 * @link   http://www.w3.org/TR/html-markup/input.email.html#input.email
 * @see    \Joomla\Form\Rule\EmailRule
 * @since  1.0
 */
class EmailField extends TextField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Email';
}
