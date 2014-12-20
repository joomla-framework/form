<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Rule;
use Joomla\Form\FormHelper;

/**
 * Test class for Joomla\Form\FormHelper.
 *
 * @coversDefaultClass  Joomla\Form\FormHelper
 */
class FormHelperTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass()
	{
		/** @var \Composer\Autoload\ClassLoader $loader */
		$loader = include dirname(__DIR__) . '/vendor/autoload.php';

		// Add our test fields to the autoload paths for testing
		$loader->addPsr4('Foo\\Form\\Field\\', __DIR__ . '/_testfields');
	}

	/**
	 * Tests the Form::addFormPath method.
	 *
	 * This method is used to add additional lookup paths for form XML files.
	 *
	 * @covers  ::addFormPath
	 * @covers  ::addPath
	 */
	public function testAddFormPath()
	{
		// Check the default behaviour.
		$paths = FormHelper::addFormPath();

		// The default path is the class file folder/forms
		// use of realpath to ensure test works for on all platforms
		$valid = dirname(__DIR__) . '/src/form';

		$this->assertContains(
			$valid,
			$paths,
			$this->isTrue(),
			'Line:' . __LINE__ . ' The libraries forms path should be included by default.'
		);

		// Test adding a custom folder.
		FormHelper::addFormPath(__DIR__);
		$paths = FormHelper::addFormPath();

		$this->assertContains(
			__DIR__,
			$paths,
			'Line:' . __LINE__ . ' An added path should be in the returned array.'
		);
	}

	/**
	 * Test the Form::loadFieldClass method.
	 *
	 * @covers  ::loadFieldClass
	 * @covers  ::loadRuleClass
	 * @covers  ::loadClass
	 */
	public function testLoadClass()
	{
		$this->assertFalse(
			FormHelper::loadFieldClass('bogus'),
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

		$this->assertEquals(
			"Foo\\Form\\Field\\BarField",
			FormHelper::loadFieldClass('foo.bar'),
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
}
