<?php
/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Foo\Form\Rule;

/**
 * Form Rule class for the Joomla Framework.
 *
 * @since  1.0
 */
class CustomRule extends \Joomla\Form\Rule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $regex = '^custom';

	/**
	 * The regular expression modifiers to use when testing a form field value.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $modifiers = 'i';
}
