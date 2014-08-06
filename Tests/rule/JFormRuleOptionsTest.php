<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Options as RuleOptions;
use SimpleXmlElement;
/**
 * Test class for Joolma Framework Form rule Options.
 *
 * @coversDefaultClass Joomla\Form\Rule\Options
 * @since  1.0
 */
class JFormRuleOptionsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Form\Rule\Options::test method.
	 *
	 * @return  void
	 *
	 * @covers ::test
	 * @since   1.0
	 */
	public function testOptions()
	{
		$rule = new RuleOptions;
		$xml = new SimpleXmlElement(
			'<field name="field1"><option value="value1">Value1</option><option value="value2">Value2</option></field>'
		);

		// Test fail conditions.

		$this->assertFalse(
			$rule->test($xml, 'bogus'),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		// Test pass conditions.

		$this->assertTrue(
			$rule->test($xml, 'value1'),
			'Line:' . __LINE__ . ' value1 should pass and return true.'
		);

		$this->assertTrue(
			$rule->test($xml, 'value2'),
			'Line:' . __LINE__ . ' value2 should pass and return true.'
		);
	}
}
