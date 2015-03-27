<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\ImageListField;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field\ImageListField.
 *
 * @coversDefaultClass  Joomla\Form\Field\ImageListField
 */
class ImageListFieldTest extends \PHPUnit_Framework_TestCase
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
		$this->text = new Text(new Language(dirname(__DIR__), 'en-GB'));
	}

	/**
	 * Test the getInput method.
	 *
	 * @covers  ::getOptions
	 */
	public function testGetOptions()
	{
		$xml = '<field name="imagelist" type="imagelist" />';

		$field = new ImageListField;
		$field->setText($this->text);

		$xml = new \SimpleXmlElement($xml);

		$this->assertTrue(
			$field->setup($xml, 'setupValue'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		TestHelper::invoke($field, 'getOptions');

		// Only check for new filters added, rest is tested in parent's test.
		$filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$|\.jpeg$|\.psd$|\.eps$';
		$element = TestHelper::getValue($field, 'element');

		$this->assertEquals($filter, $element['filter']);
	}
}
