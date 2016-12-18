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
 * URL Form Field class for the Joomla! Framework.
 *
 * Supports an HTML5 URL text field
 *
 * @link   http://www.w3.org/TR/html-markup/input.url.html#input.url
 * @see    \Joomla\Form\Rule\UrlRule for validation of full urls
 * @since  1.0
 */
class UrlField extends TextField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Url';
}
