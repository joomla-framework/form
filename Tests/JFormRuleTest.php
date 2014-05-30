<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Rule;
use SimpleXmlElement;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class FormRuleTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test...
	 *
	 * @return void
	 *
	 * @expectedException UnexpectedValueException
	 */
	public function testTest()
	{
		$rule = new Rule;
		$element = new SimpleXmlElement('<field type="text" />');

		$rule->test($element, 'val');
	}
}
