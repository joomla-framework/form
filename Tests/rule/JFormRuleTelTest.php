<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Tel as RuleTel;
use SimpleXmlElement;
/**
 * Test class for Joolma Framework Form rule Tel.
 *
 * @since  1.0
 */
class JFormRuleTelTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test data for testing of Joomla\Form\Rule\Tel::test method.
	 *
	 * @return array
	 *
	 * @since __VERSION_NO__
	 */
	public function dataTel()
	{
		return array(
			array('<field name="tel1a" plan="NANP" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),
					array('anything_5555555555', false),
					array('5555555555_anything', false),

					// Test pass conditions.
					array('', true),
					array('(555) 234-5678', true),
					array('1-555-234-5678', true),
					array('+1-555-234-5678', true),
					array('555-234-5678', true),
					array('1-555-234-5678', true),
					array('1 555 234 5678', true),
				),
			),
			array('<field name="tel1b" plan="northamerica" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),

					// Test pass conditions.
					array('', true),
					array('(555) 234-5678', true),
				),
			),
			array('<field name="tel1c" plan="us" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),

					// Test pass conditions.
					array('', true),
					array('(555) 234-5678', true),
				),
			),
			array('<field name="tel2a" plan="ITU-T" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),
					array('anything_5555555555', false),
					array('5555555555_anything', false),
					array('1 2 3 4 5 6 ', false),
					array('5552345678', false),
					array('anything_555.5555555', false),
					array('555.5555555_anything', false),

					// Test pass conditions.
					array('', true),
					array('+555 234 5678', true),
					array('+123 555 234 5678', true),
					array('+2 52 34 55', true),
					array('+5552345678', true),
				),
			),
			array('<field name="tel2b" plan="International" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),

					// Test pass conditions.
					array('', true),
					array('+555 234 5678', true),
				),
			),
			array('<field name="tel2c" plan="int" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),

					// Test pass conditions.
					array('', true),
					array('+555 234 5678', true),
				),
			),
			array('<field name="tel2d" plan="missdn" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),

					// Test pass conditions.
					array('', true),
					array('+555 234 5678', true),
				),
			),
			array('<field name="tel2e" plan="" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('123451234512', false),

					// Test pass conditions.
					array('', true),
					array('+555 234 5678', true),
				),
			),
			array('<field name="tel3a" plan="EPP" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('12345123451234512345', false),
					array('123.1234', false),
					array('23.1234', false),
					array('3.1234', false),

					// Test pass conditions.
					array('', true),
					array('+123.1234', true),
					array('+23.1234', true),
					array('+3.1234', true),
					array('+3.1234x555', true),
				),
			),
			array('<field name="tel3b" plan="IETF" />',
				array(
					// Test fail conditions.
					array('bogus', false),
					array('0', false),
					array('12345123451234512345', false),

					// Test pass conditions.
					array('', true),
					array('+123.1234', true),
				),
			),
			array('<field name="tel4" />',
				array(
					// Test fail conditions no plan.
					array('bogus', false),
					array('0', false),
					array('anything_555.5555555', false),
					array('555.5555555x555_anything', false),
					array('555.', false),
					array('1 2 3 4 5 6 ', false),

					// Test pass conditions no plan.
					array('', true),
					array('555 234 5678', true),
					array('+123 555 234 5678', true),
					array('+2 52 34 55', true),
					array('5552345678', true),
					array('.5555555', true),
					array('+5552345678', true),
					array('1 2 3 4 5 6 7', true),
					array('123451234512', true),
				),
			),
		);
	}

	/**
	 * Test the Joomla\Form\Rule\Tel::test method.
	 *
	 * @param   string  $xml         @todo
	 * @param   array   $assertions  @todo
	 *
	 * @dataProvider dataTel
	 *
	 * @return void
	 */
	public function testTel($xml, $assertions)
	{
		$rule = new RuleTel;
		$field = new SimpleXmlElement($xml);

		foreach ($assertions as $assertion)
		{
			$this->assertEquals(
				$rule->test($field, $assertion[0]),
				$assertion[1],
				'Line:' . __LINE__ . ' The rule should ' . ($assertion[1] ? 'pass' : 'fail')
				. ' with "' . $assertion[0] . '" and return ' . ($assertion[1] ? 'true' : 'false') . '.'
			);
		}
	}
}
