<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Rule;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\EqualsRule;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\Form\Rule\EqualsRule.
 *
 * @coversDefaultClass  Joomla\Form\Rule\EqualsRule
 */
class EqualsRuleTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Form\Rule\EqualsRule::test method.
	 *
	 * @covers  ::test
	 */
	public function testEquals()
	{
		$rule = new EqualsRule;
		$field = new \SimpleXmlElement('<field name="foo" field="bar" />');

		// Test fail conditions.
		$registry = new Registry(array('barfoo' => 'aValue'));
		$this->assertFalse(
			$rule->test($field, 'myValue', '', $registry),
			'Line:' . __LINE__ . ' Equal rule should have failed and returned false.'
		);

		// Test pass conditions.
		$registry = new Registry(array('barfoo' => 'aValue', 'bar' => 'myValue'));
		$this->assertTrue(
			$rule->test($field, 'myValue', '', $registry),
			'Line:' . __LINE__ . ' Equal rule should have passed and returned true.'
		);
	}

	/**
	 * Test the Joomla\Form\Rule\EqualsRule::test method.
	 *
	 * @covers             ::test
	 * @expectedException  UnexpectedValueException
	 */
	public function testEqualsNoField()
	{
		$rule = new EqualsRule;
		$field = new \SimpleXmlElement('<field name="foo" />');

		// Test fail conditions.
		$registry = new Registry(array('barfoo' => 'aValue'));
		$rule->test($field, 'myValue', '', $registry);
	}

	/**
	 * Test the Joomla\Form\Rule\EqualsRule::test method.
	 *
	 * @covers             ::test
	 * @expectedException  InvalidArgumentException
	 */
	public function testEqualsNullRegistry()
	{
		$rule = new EqualsRule;
		$field = new \SimpleXmlElement('<field name="foo" field="bar" />');

		// Test fail conditions.
		$rule->test($field, 'myValue');
	}
}
