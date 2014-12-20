<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Test\TestHelper;
use Joomla\Form\Field\TimezoneField;

/**
 * Test class for Joomla\Form\Field\TimezoneField.
 *
 * @coversDefaultClass  Joomla\Form\Field\TimezoneField
 */
class TimezoneFieldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependancies for the test.
	 */
	protected function setUp()
	{
		parent::setUp();

		include_once dirname(__DIR__) . '/inspectors.php';
	}

	/**
	 * Test the getInput method.
	 *
	 * @covers  ::getGroups
	 */
	public function testGetGroups()
	{
		$field = new TimezoneField;
		$element = new \SimpleXmlElement('<field name="myName" id="myId" />');
		$this->assertTrue(
			$field->setup($element, 'value'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertGreaterThan(0, TestHelper::invoke($field, 'getGroups'));
	}
}
