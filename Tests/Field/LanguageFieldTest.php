<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\LanguageField;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestDatabase;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field\LanguageField.
 *
 * @coversDefaultClass  Joomla\Form\Field\LanguageField
 * @since  1.0
 */
class LanguageFieldTest extends TestDatabase
{
	/**
	 * Text object for injection
	 *
	 * @var  Text
	 */
	private $text;

	/**
	 * Sets up dependencies for the test.
	 */
	protected function setUp()
	{
		parent::setUp();

		include_once dirname(__DIR__) . '/inspectors.php';

		// Prepare a Text object to be injected into test objects
		$this->text = new Text(Language::getInstance(dirname(__DIR__)));
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  \PHPUnit_Extensions_Database_DataSet_XmlDataSet
	 */
	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/testfiles/JFormField.xml');
	}

	/**
	 * Test data for getOptions test
	 *
	 * @return  array
	 */
	public function dataGetOptions()
	{
		return array(
			array('<option value="0" onclick="foobar();">No</option>'
				. '<item value="1">Yes</item>',
					array(
						// Format presentInArray#optionNumber => optionArray
						'1#0' => array(
							'value' => '0',
							'text' => 'No',
							'disable' => false,
							'class' => '',
							'onclick' => 'foobar();'
						),
						'0#1' => array(
							'value' => '1',
							'text' => 'Yes',
							'disable' => false,
							'class' => '',
							'onclick' => ''
						),
						'1#2' => array(
							'value' => 'en-GB',
							'text' => 'English (United Kingdom)',
						),
					),
				),
			array('<option value="oof" disabled="true">Foo</option>'
				. '<option value="rab" class="lorem">Bar</option>',
					array(
						'1#0' => array(
							'value' => 'oof',
							'text' => 'Foo',
							'disable' => true,
							'class' => '',
							'onclick' => ''
						),
						'1#1' => array(
							'value' => 'rab',
							'text' => 'Bar',
							'disable' => false,
							'class' => 'lorem',
							'onclick' => ''
						),
					),
				),
			);
	}

	/**
	 * Test the getOptions method.
	 *
	 * @param   string  $optionTag  @todo
	 * @param   string  $expected   @todo
	 *
	 * @covers        ::getOptions
	 * @dataProvider  dataGetOptions
	 */
	public function testGetOptions($optionTag, $expected)
	{
		$field = new LanguageField;
		$field->setText($this->text);

		$fieldStartTag = '<field name="myName" type="language" base_path="'
		. __DIR__ . '/data" >';
		$fieldEndTag = '</field>';

		$xml = new \SimpleXmlElement($fieldStartTag . $optionTag . $fieldEndTag);
		$this->assertTrue(
			$field->setup($xml, 'value'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$options = TestHelper::invoke($field, 'getOptions');

		foreach ($expected as $inOrNot => $expectedOption)
		{
			$expected = $inOrNot[0] == '1' ? true : false;
			$i = substr($inOrNot, 2);

			$this->assertEquals(
				in_array((object) $expectedOption, $options),
				$expected,
				'Line:' . __LINE__ . ' The getOption method should compute option #'
				. $i . ' correctly'
			);
		}
	}

	/**
	 * Test...
	 *
	 * @covers ::createLanguageList
	 */
	public function testCreateLanguageList()
	{
		$field = new LanguageField;
		$field->setText($this->text);

		$list = TestHelper::invoke(
			$field,
			'createLanguageList',
			'en-GB',
			__DIR__ . '/data'
		);

		$listCompareEqual = array(
			(object) array(
				'text' => 'English (United Kingdom)',
				'value' => 'en-GB',
				'selected' => 'selected="selected"'
			)
		);

		$this->assertEquals(
			$listCompareEqual,
			$list
		);
	}
}
