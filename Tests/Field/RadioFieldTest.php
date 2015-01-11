<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\RadioField;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field\RadioField.
 *
 * @coversDefaultClass  Joomla\Form\Field\RadioField
 */
class RadioFieldTest extends \PHPUnit_Framework_TestCase
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
	 * Test data for getInput test
	 *
	 * @return  array
	 */
	public function dataGetInput()
	{
		return array(
			array(
				'<field name="myName" id="myId" type="radio" class="foo bar">'
					. '<option value="0">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'fieldset',
					'attributes' => array(
						'id' => 'myId',
						'class' => 'radio foo bar'
					),
					'children' => array(
						'count' => 4
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="radio" class="foo bar">'
					. '<option value="0" class="one two">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'label',
					'attributes' => array(
						'for' => 'myId0',
						'class' => 'one two',
					),
					'parent' => array('tag' => 'fieldset')
				)
			),
			array(
				'<field name="myName" id="myId" type="radio">'
					. '<option value="0" disabled="true" class="one two">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'radio',
						'id' => 'myId0',
						'name' => 'myName',
						'class' => 'one two',
						'value' => '0',
						'disabled' => 'disabled'
					),
					'parent' => array('tag' => 'fieldset')
				)
			)
		);
	}

	/**
	 * Test the getInput method.
	 *
	 * @param   string  $xml              @todo
	 * @param   string  $expectedTagAttr  @todo
	 *
	 * @covers        ::getInput
	 * @dataProvider  dataGetInput
	 */
	public function testGetInput($xml, $expectedTagAttr)
	{
		$field = new RadioField;
		$field->setText($this->text);

		$xml = new \SimpleXMLElement($xml);
		$this->assertTrue(
			$field->setup($xml, 'aValue'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertTag(
			$expectedTagAttr,
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return attributes correctly.'
		);
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
	 * @covers ::getOptions
	 * @dataProvider dataGetOptions
	 */
	public function testGetOptions($optionTag, $expected)
	{
		$field = new RadioField;
		$field->setText($this->text);

		$fieldStartTag = '<field name="myName" type="radio">';
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
				. $i . ' correctly.'
			);
		}
	}
}
