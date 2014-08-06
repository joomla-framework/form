<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Boolean as RuleBoolean;
use SimpleXmlElement;
/**
 * Test class for Joolma Framework Form rule Boolean.
 *
 * @coversDefaultClass Joomla\Form\Rule\Boolean
 * @since  1.0
 */
class JFormRuleBooleanTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test data for testing of Joomla\Form\Rule\Boolean::test method.
	 *
	 * @return array
	 *
	 * @since __VERSION_NO__
	 */
	public function dataBoolean()
	{
		return array(
			// Test fail conditions.
			array('bogus', false),
			array('0_anything', false),
			array('anything_1_anything', false),
			array('anything_true_anything', false),
			array('anything_false', false),

			// Test pass conditions.
			array(0, true),
			array(1, true),
			array('0', true),
			array('1', true),
			array('true', true),
			array('false', true),
			array('TRUE', true),
			array('FALSE', true),
		);
	}

	/**
	 * Test the Joomla\Form\Rule\Boolean::test method.
	 *
	 * @param   string   $value           @todo
	 * @param   boolean  $expectedOutput  @todo
	 *
	 * @dataProvider dataBoolean
	 *
	 * @covers ::test
	 * @return void
	 */
	public function testBoolean($value, $expectedOutput)
	{
		$rule = new RuleBoolean;
		$xml = new SimpleXmlElement('<field name="foo" />');

		$this->assertEquals(
			$rule->test($xml->field, $value),
			$expectedOutput,
			'Line:' . __LINE__ . ' The rule should pass and return '
				. ($expectedOutput ? 'true' : 'false') . '.'
		);
	}

	/**
	 * Test the Joomla\Form\Rule\Boolean::test method.
	 *
	 * @covers             Joomla\Form\Rule::test
	 * @expectedException  UnexpectedValueException
	 * @return void
	 */
	public function testRuleEmptyRegexException()
	{
		$rule = new RuleBoolean;
		$xml = new SimpleXmlElement('<field name="foo" />');

		TestHelper::setValue($rule, 'regex', '');

		$rule->test($xml->field, 'true');
	}
}
