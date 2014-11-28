<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Email as RuleEmail;

/**
 * Test class for Joolma Framework Form rule Email.
 *
 * @coversDefaultClass Joomla\Form\Rule\Email
 * @since  1.0
 */
class JFormRuleEmailTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Form\Rule\Email::test method.
	 *
	 * @return void
	 *
	 * @covers  ::test
	 * @since   __VERSION_NO__
	 */
	public function testEmail()
	{
		$rule = new RuleEmail;
		$xml = simplexml_load_string('<form><field name="email1" /><field name="email2" unique="true" /></form>');

		// Test fail conditions.

		$this->assertFalse(
			$rule->test($xml->field[0], 'bogus'),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		$this->assertFalse(
			$rule->test($xml->field[0], '0'),
			'Line:' . __LINE__ . ' The non required field should pass with empty value.'
		);

		$this->assertFalse(
			$rule->test($xml->field[0], 'false'),
			'Line:' . __LINE__ . ' The non required field should pass with empty value.'
		);

		// Test pass conditions.

		$this->assertTrue(
			$rule->test($xml->field[0], ''),
			'Line:' . __LINE__ . ' The non required field should pass with empty value.'
		);

		$this->assertTrue(
			$rule->test($xml->field[0], 'me@example.com'),
			'Line:' . __LINE__ . ' The basic rule should pass and return true.'
		);
	}

	/**
	 * Data Provider  for email rule test with no multiple attribute and no tld attribute
	 *
	 * @return array
	 *
	 * @since 11.1
	 */
	public function emailData1()
	{
		return array(
			array('test@example.com', true),
			array('badaddress.com', false),
			array('firstnamelastname@domain.tld', true),
			array('firstname+lastname@domain.tld', true),
			array('firstname+middlename+lastname@domain.tld', true),
			array('firstnamelastname@subdomain.domain.tld', true),
			array('firstname+lastname@subdomain.domain.tld', true),
			array('firstname+middlename+lastname@subdomain.domain.tld', true),
			array('firstname@localhost', true)
		);
	}

	/**
	 * Test the email rule
	 *
	 * @param   string   $emailAddress    Email to be tested
	 * @param   boolean  $expectedResult  Result of test
	 *
	 * @return void
	 *
	 * @covers ::test
	 * @dataProvider emailData1
	 * @since 11.1
	 */
	public function testEmailData($emailAddress, $expectedResult)
	{
		$rule = new RuleEmail;
		$xml = simplexml_load_string('<form><field name="email1" /></form>');
		$this->assertEquals(
			$rule->test($xml->field[0], $emailAddress),
			$expectedResult,
			$emailAddress . ' should have returned ' . ($expectedResult ? 'true' : 'false') . ' but did not'
		);
	}

	/**
	 * Data Provider  for email rule test with multiple attribute and no tld attribute
	 *
	 * @return array
	 *
	 * @since 12.3
	 */
	public function emailData2()
	{
		return array(
			array('test@example.com', true),
			array('test@example.com,badaddress.com', false),
			array('test@example.com,badaddress.com,test2@example.com,', false),
			array('test@example.com,test2@example.com,test3@localhost', true),
		);
	}

	/**
	 * Test the email rule with the multiple attribute
	 *
	 * @param   string   $emailAddress    Email to be tested
	 * @param   boolean  $expectedResult  Result of test
	 *
	 * @return void
	 *
	 * @covers ::test
	 * @dataProvider emailData2
	 * @since 12.3
	 */
	public function testEmailData2($emailAddress, $expectedResult)
	{
		$rule = new RuleEmail;
		$xml = simplexml_load_string('<form><field name="email1" multiple="multiple" /></form>');
		$this->assertEquals(
			$rule->test($xml->field[0], $emailAddress),
			$expectedResult,
			$emailAddress . ' should have returned ' . ($expectedResult ? 'true' : 'false') . ' but did not'
		);
	}

	/**
	 * Data Provider  for email rule test with tld attribute
	 *
	 * @return array
	 *
	 * @since 12.3
	 */
	public function emailData3()
	{
		return array(
			array('test@example.com', true),
			array('test3@localhost', false),
			array('test3@example.c', false),
			array('test3@example.ca', true),
			array('test3@example.travel', true),
		);
	}

	/**
	 * Test the email rule with the tld attribute
	 *
	 * @param   string   $emailAddress    Email to be tested
	 * @param   boolean  $expectedResult  Result of test
	 *
	 * @return void
	 *
	 * @covers ::test
	 * @dataProvider emailData3
	 * @since 12.3
	 */
	public function testEmailData3($emailAddress, $expectedResult)
	{
		$rule = new RuleEmail;
		$xml = simplexml_load_string('<form><field name="email1" tld="tld" /></form>');
		$this->assertEquals(
			$rule->test($xml->field[0], $emailAddress),
			$expectedResult,
			'Line:' . __LINE__ . ' ' . $emailAddress . ' should have returned '
				. ($expectedResult ? 'true' : 'false') . ' but did not.'
		);
	}
}
