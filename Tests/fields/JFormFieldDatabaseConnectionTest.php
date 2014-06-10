<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field\DatabaseConnectionField;
use Joomla\Database\DatabaseDriver;
use SimpleXMLElement;

/**
 * Test class for JFormFieldDatabaseConnection.
 *
 * @since  1.0
 */
class JFormFieldDatabaseConnectionTest extends \PHPUnit_Framework_TestCase
{
	public function dataGetOptions()
	{
		$conn = DatabaseDriver::getConnectors();
		$available = array_map('ucfirst', $conn);

		$expected = array();
		foreach ($available as $value) {
			$expected[lcfirst($value)] = $value;
		}

		return array(
			array(
				array("mysqli"),
				$available,
				array("mysqli" => "Mysqli")
			),
			array(
				array(),
				$available,
				$expected,
			),
			// Todo : create mock of static function JDatabaseDriver::getConnectors.
			/*array(
				array("mysqli"),
				array(),
				array('' => "JNONE")
			),*/
		);
	}

	/**
	 * Test the getOptions method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @dataProvider dataGetOptions
	 */
	public function testGetOptions($supported, $available, $expectedOptions)
	{
		$xml = '<field name="databaseconnection" type="databaseconnection" '
			. ' supported="' . implode(",", $supported) . '" />';
		$element = new SimpleXmlElement($xml);

		$field = new DatabaseConnectionField;

		$this->assertThat(
			$field->setup($element, 'aValue'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertEquals(
			$expectedOptions,
			TestHelper::invoke($field, 'getOptions'),
			'Line:' . __LINE__ . ' The getOptions method should return correct options.'
		);
	}
}
