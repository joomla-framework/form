<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field\TimezoneField;

/**
 * Test class for JForm.
 *
 * @coversDefaultClass Joomla\Form\Field\TimezoneField
 * @since  1.0
 */
class JFormFieldTimezoneTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependancies for the test.
	 *
	 * @return void
	 *
	 * @since __VERSION_NO__
	 */
	protected function setUp()
	{
		parent::setUp();

		include_once dirname(__DIR__) . '/inspectors.php';
	}

	/**
	 * Test the getInput method.
	 *
	 * @return void
	 *
	 * @covers ::getGroups
	 * @since __VERSION_NO__
	 */
	public function testGetGroups()
	{
		$field = new TimezoneField;
		$element = new \SimpleXmlElement('<field name="myName" id="myId" />');
		$this->assertThat(
			$field->setup($element, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertGreaterThan(
			0,
			TestHelper::invoke($field, 'getGroups')
		);
	}
}
