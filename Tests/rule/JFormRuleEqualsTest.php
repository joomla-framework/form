<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Equals as RuleEquals;
use SimpleXmlElement;
use Joomla\Registry\Registry;

/**
 * Test class for Joolma Framework Form rule Equals.
 *
 * @since  1.0
 */
class JFormRuleEqualsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Form\Rule\Equals::test method.
	 *
	 * @return void
	 */
	public function testEquals()
	{
		$rule = new RuleEquals;
		$field = new SimpleXmlElement('<field name="foo" field="bar" />');
		
		// Test fail conditions.
		$registry = new Registry(array('barfoo' => 'aValue'));
		$this->assertThat(
			$rule->test($field, 'myValue', '', $registry),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Equal rule should have failed and returned false.'
		);

		// Test pass conditions.
		$registry = new Registry(array('barfoo' => 'aValue', 'bar' => 'myValue'));
		$this->assertThat(
			$rule->test($field, 'myValue', '', $registry),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Equal rule should have passed and returned true.'
		);
	}

	/**
	 * Test the Joomla\Form\Rule\Equals::test method.
	 *
	 * @return void
	 *
	 * @expectedException UnexpectedValueException
	 */
	public function testEqualsNoField()
	{
		$rule = new RuleEquals;
		$field = new SimpleXmlElement('<field name="foo" />');
		
		// Test fail conditions.
		$registry = new Registry(array('barfoo' => 'aValue'));
		$rule->test($field, 'myValue', '', $registry);
	}

	/**
	 * Test the Joomla\Form\Rule\Equals::test method.
	 *
	 * @return void
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testEqualsNullRegistry()
	{
		$rule = new RuleEquals;
		$field = new SimpleXmlElement('<field name="foo" field="bar" />');
		
		// Test fail conditions.
		$rule->test($field, 'myValue');
	}
}
