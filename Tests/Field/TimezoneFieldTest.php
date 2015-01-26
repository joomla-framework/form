<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\TimezoneField;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field\TimezoneField.
 *
 * @coversDefaultClass  Joomla\Form\Field\TimezoneField
 */
class TimezoneFieldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Text object for injection
	 *
	 * @var  Text
	 */
	private $text;

	/**
	 * Sets up dependancies for the test.
	 */
	protected function setUp()
	{
		parent::setUp();

		include_once dirname(__DIR__) . '/inspectors.php';

		// Prepare a Text object to be injected into test objects
		$this->text = new Text(Language::getInstance(dirname(__DIR__)));
	}

	/**
	 * Test the getInput method.
	 *
	 * @covers  ::getGroups
	 */
	public function testGetGroups()
	{
		$field = new TimezoneField;
		$field->setText($this->text);
		$element = new \SimpleXmlElement('<field name="myName" id="myId" />');
		$this->assertTrue(
			$field->setup($element, 'value'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertGreaterThan(0, TestHelper::invoke($field, 'getGroups'));
	}
}
