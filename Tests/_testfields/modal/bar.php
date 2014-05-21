<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Foo\Form\Field\Modal;

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  1.0
 */
class BarField extends \Joomla\Form\Field
{
	/**
	 * Method to get the field input.
	 *
	 * @return  string        The field input.
	 */
	protected function getInput()
	{
		return 'Modal';
	}
}
