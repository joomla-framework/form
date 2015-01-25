<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Rule;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\BooleanRule;

/**
 * Test class for Joomla\Form\Rule\BooleanRule.
 *
 * @coversDefaultClass  Joomla\Form\Rule\BooleanRule
 * @since  1.0
 */
class BooleanRuleTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test data for testing of Joomla\Form\Rule\Boolean::test method.
	 *
	 * @return  array
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
	 * Test the Joomla\Form\Rule\BooleanRule::test method.
	 *
	 * @param   string   $value           @todo
	 * @param   boolean  $expectedOutput  @todo
	 *
	 * @dataProvider dataBoolean
	 *
	 * @covers ::test
	 */
	public function testBoolean($value, $expectedOutput)
	{
		$rule = new BooleanRule;
		$xml = new \SimpleXmlElement('<field name="foo" />');

		$this->assertEquals(
			$rule->test($xml->field, $value),
			$expectedOutput,
			'Line:' . __LINE__ . ' The rule should pass and return '
				. ($expectedOutput ? 'true' : 'false') . '.'
		);
	}

	/**
	 * Test the Joomla\Form\Rule\BooleanRule::test method.
	 *
	 * @covers             Joomla\Form\Rule::test
	 * @expectedException  UnexpectedValueException
	 */
	public function testRuleEmptyRegexException()
	{
		$rule = new BooleanRule;
		$xml = new \SimpleXmlElement('<field name="foo" />');

		TestHelper::setValue($rule, 'regex', '');

		$rule->test($xml->field, 'true');
	}
}
