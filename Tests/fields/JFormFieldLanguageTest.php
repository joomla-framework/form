<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestDatabase;
use Joomla\Form\Field\LanguageField;
use Joomla\Test\TestHelper;
use SimpleXmlElement;

/**
 * Test class for JFormFieldLanguage.
 *
 * @coversDefaultClass Joomla\Form\Field\LanguageField
 * @since  1.0
 */
class JFormFieldLanguageTest extends TestDatabase
{
	/**
	 * Sets up dependencies for the test.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		include_once dirname(__DIR__) . '/inspectors.php';
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  \PHPUnit_Extensions_Database_DataSet_XmlDataSet  dataset
	 *
	 * @since   1.0
	 */
	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/testfiles/JFormField.xml');
	}

	/**
	 * Test data for getOptions test
	 *
	 * @return  array
	 *
	 * @since __VERSION_NO__
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
	 * @return  void
	 *
	 * @covers ::getOptions
	 * @dataProvider dataGetOptions
	 * @since   1.0
	 */
	public function testGetOptions($optionTag, $expected)
	{
		$field = new LanguageField;

		$fieldStartTag = '<field name="myName" type="language" base_path="'
		. __DIR__ . '/data" >';
		$fieldEndTag = '</field>';

		$xml = new SimpleXmlElement($fieldStartTag . $optionTag . $fieldEndTag);
		$this->assertThat(
			$field->setup($xml, 'value'),
			$this->isTrue(),
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
	 * @return void
	 * 
	 * @covers ::createLanguageList
	 * @since   __VERSION_NO__
	 */
	public function testCreateLanguageList()
	{
		$field = new LanguageField;
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
