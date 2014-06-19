<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Rule;
use Joomla\Form\FormHelper;

/**
 * Test class for JForm.
 *
 * @coversDefaultClass Joomla\Form\FormHelper
 * @since  1.0
 */
class JFormHelperTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the Form::addFieldPath method.
	 *
	 * This method is used to add additional lookup paths for field helpers.
	 *
	 * @return void
	 *
	 * @covers ::addFieldPath
	 * @covers ::addPath
	 * @since __VERSION_NO__
	 */
	public function testAddFieldPath()
	{
		// Check the default behaviour.
		$paths = FormHelper::addFieldPath();

		// The default path is the class file folder/forms
		// use of realpath to ensure test works for on all platforms
		$valid = dirname(__DIR__) . '/src/field';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The libraries fields path should be included by default.'
		);

		// Test adding a custom folder.
		FormHelper::addFieldPath(__DIR__);
		$paths = FormHelper::addFieldPath();

		$this->assertThat(
			in_array(__DIR__, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' An added path should be in the returned array.'
		);
	}

	/**
	 * Tests the Form::addFormPath method.
	 *
	 * This method is used to add additional lookup paths for form XML files.
	 *
	 * @return void
	 *
	 * @covers ::addFormPath
	 * @covers ::addPath
	 * @since __VERSION_NO__
	 */
	public function testAddFormPath()
	{
		// Check the default behaviour.
		$paths = FormHelper::addFormPath();

		// The default path is the class file folder/forms
		// use of realpath to ensure test works for on all platforms
		$valid = dirname(__DIR__) . '/src/form';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The libraries forms path should be included by default.'
		);

		// Test adding a custom folder.
		FormHelper::addFormPath(__DIR__);
		$paths = FormHelper::addFormPath();

		$this->assertThat(
			in_array(__DIR__, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' An added path should be in the returned array.'
		);
	}

	/**
	 * Tests the Form::addRulePath method.
	 *
	 * This method is used to add additional lookup paths for form XML files.
	 *
	 * @return void
	 *
	 * @covers ::addRulePath
	 * @covers ::addPath
	 * @since __VERSION_NO__
	 */
	public function testAddRulePath()
	{
		// Check the default behaviour.
		$paths = FormHelper::addRulePath();

		// The default path is the class file folder/rules
		// use of realpath to ensure test works for on all platforms
		$valid = dirname(__DIR__) . '/src/rule';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The libraries rule path should be included by default.'
		);

		// Test adding a custom folder.
		FormHelper::addRulePath(__DIR__);
		$paths = FormHelper::addRulePath();

		$this->assertThat(
			in_array(__DIR__, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' An added path should be in the returned array.'
		);
	}

	/**
	 * Test the Form::loadFieldClass method.
	 *
	 * @return void
	 *
	 * @covers ::loadFieldClass
	 * @covers ::loadRuleClass
	 * @covers ::loadClass
	 * @since __VERSION_NO__
	 */
	public function testLoadClass()
	{
		$this->assertThat(
			FormHelper::loadFieldClass('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' loadFieldClass should return false if class not found.'
		);

		$this->assertEquals(
			"Joomla\\Form\\Field\\TextField",
			FormHelper::loadFieldClass('text'),
			'Line:' . __LINE__ . ' loadFieldClass should return the correct class.'
		);

		$this->assertEquals(
			"Joomla\\Form\\Field\\TextField",
			FormHelper::loadFieldClass('joomla.text'),
			'Line:' . __LINE__ . ' loadFieldClass should return the correct class.'
		);

		// Add custom path.
		FormHelper::addFieldPath(__DIR__ . '/_testfields');

		$this->assertEquals(
			"Joomla\\Form\\Field\\TestField",
			FormHelper::loadFieldClass('test'),
			'Line:' . __LINE__ . ' loadFieldClass should return the correct custom class.'
		);

		$this->assertEquals(
			"Joomla\\Form\\Field\\FooField",
			FormHelper::loadFieldClass('foo'),
			'Line:' . __LINE__ . ' loadFieldClass should return the correct custom class.'
		);

		$this->assertEquals(
			"Foo\\Form\\Field\\BarField",
			FormHelper::loadFieldClass('foo.bar'),
			'Line:' . __LINE__ . ' loadFieldClass should return the correct custom class.'
		);

		$this->assertEquals(
			"Joomla\\Form\\Field\\Modal\\FooField",
			FormHelper::loadFieldClass('modal\\foo'),
			'Line:' . __LINE__ . ' loadFieldClass should return the correct custom class.'
		);

		$this->assertEquals(
			"Foo\\Form\\Field\\Modal\\BarField",
			FormHelper::loadFieldClass('foo.modal\\bar'),
			'Line:' . __LINE__ . ' loadFieldClass should return the correct custom class.'
		);

		$this->assertEquals(
			"Joomla\\Form\\Rule\\Email",
			FormHelper::loadRuleClass('email'),
			'Line:' . __LINE__ . ' loadRuleClass should return the correct class.'
		);

		$this->assertEquals(
			"Joomla\\Form\\Rule\\Url",
			FormHelper::loadRuleClass('joomla.url'),
			'Line:' . __LINE__ . ' loadRuleClass should return the correct class.'
		);
	}

	/**
	 * Test the Form::loadFieldType method.
	 *
	 * @return void
	 *
	 * @covers ::loadFieldType
	 * @covers ::loadType
	 * @since __VERSION_NO__
	 */
	public function testLoadFieldType()
	{
		$this->assertThat(
			FormHelper::loadFieldType('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' loadFieldType should return false if class not found.'
		);

		$field = FormHelper::loadFieldType('list');
		$this->assertThat(
			($field instanceof \Joomla\Form\Field\ListField),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct class.'
		);

		$this->assertEquals(
			$field,
			FormHelper::loadFieldType('list', false),
			'Line:' . __LINE__ . ' loadFieldType should return the correct class.'
		);

		// Add custom path.
		FormHelper::addFieldPath(__DIR__ . '/_testfields');

		$this->assertThat(
			(FormHelper::loadFieldType('test') instanceof \Joomla\Form\Field\TestField),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);

		$this->assertThat(
			(FormHelper::loadFieldType('foo') instanceof \Joomla\Form\Field\FooField),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);

		$this->assertThat(
			(FormHelper::loadFieldType('foo.bar') instanceof \Foo\Form\Field\BarField),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);

		$this->assertThat(
			(FormHelper::loadFieldType('modal\\foo') instanceof \Joomla\Form\Field\Modal\FooField),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);

		$this->assertThat(
			(FormHelper::loadFieldType('foo.modal\\bar') instanceof \Foo\Form\Field\Modal\BarField),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);
	}

	/**
	 * Test for Form::loadRuleType method.
	 *
	 * @return void
	 *
	 * @covers ::loadRuleType
	 * @covers ::loadType
	 * @since __VERSION_NO__
	 */
	public function testLoadRuleType()
	{
		// Test error handling.

		$this->assertThat(
			FormHelper::loadRuleType('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Loading an unknown rule should return false.'
		);

		// Test loading a custom rule.

		FormHelper::addRulePath(__DIR__ . '/_testrules');

		$this->assertThat(
			(FormHelper::loadRuleType('custom') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading a known rule should return a rule object.'
		);

		// Test all the stock rules load.

		$this->assertThat(
			(FormHelper::loadRuleType('boolean') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the boolean rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('email') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the email rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('equals') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the equals rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('options') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the options rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('color') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the color rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('tel') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the tel rule should return a rule object.'
		);
	}
}
