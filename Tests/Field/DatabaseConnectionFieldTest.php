<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Database\DatabaseDriver;
use Joomla\Form\Field\DatabaseConnectionField;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field\DatabaseConnectionField.
 *
 * @coversDefaultClass  Joomla\Form\Field\DatabaseConnectionField
 */
class DatabaseConnectionFieldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Text object for injection
	 *
	 * @var  Text
	 */
	private $text;

	/**
	 * Set up for testing
	 */
	public function setUp()
	{
		parent::setUp();

		// Prepare a Text object to be injected into test objects
		$this->text = new Text(Language::getInstance(dirname(__DIR__)));
	}

	/**
	 * Test data for getOptions test
	 *
	 * @return  array
	 */
	public function dataGetOptions()
	{
		$conn = DatabaseDriver::getConnectors();
		$available = array_map('ucfirst', $conn);

		$expected = array();

		foreach ($available as $value)
		{
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
	 * @param   string  $supported        @todo
	 * @param   string  $available        @todo
	 * @param   string  $expectedOptions  @todo
	 *
	 * @covers        ::getOptions
	 * @dataProvider  dataGetOptions
	 */
	public function testGetOptions($supported, $available, $expectedOptions)
	{
		$xml = '<field name="databaseconnection" type="databaseconnection" '
			. ' supported="' . implode(",", $supported) . '" />';
		$element = new \SimpleXmlElement($xml);

		$field = new DatabaseConnectionField;
		$field->setText($this->text);

		$this->assertTrue(
			$field->setup($element, 'aValue'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertEquals(
			$expectedOptions,
			TestHelper::invoke($field, 'getOptions'),
			'Line:' . __LINE__ . ' The getOptions method should return correct options.'
		);
	}
}
