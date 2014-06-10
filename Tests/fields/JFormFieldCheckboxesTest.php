<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field\CheckboxesField;
use SimpleXmlElement;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldCheckboxesTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test data for getInput test
	 *
	 * @return  array
	 */
	public function dataGetInput()
	{
		return array(// Todo 
			array(
				'<field name="myName" id="myId" type="checkboxes" class="foo bar">'
					. '<option value="0">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				null,
				array(
					'tag' => 'fieldset',
					'attributes' => array(
						'id' => 'myId',
						'class' => 'checkboxes foo bar'
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="checkboxes">'
					. '<option value="0">No</option>'
					. '<option value="aValue">Yes</option>'
				. '</field>',
				'aValue',
				array(
					'tag' => 'li',
					'child' => array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'checkbox',
							'id' => 'myId1',
							'name' => 'myName[]',
							'checked' => 'checked'
						)
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="radio" class="foo bar">'
					. '<option value="0" class="one two">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				null,
				array(
					'tag' => 'li',
					'child' => array(
						'tag' => 'label',
						'attributes' => array(
							'for' => 'myId0',
							'class' => 'one two'
						)
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="checkboxes" checked="0">'
					. '<option value="0" disabled="true" class="one two">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				null,
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'checkbox',
						'id' => 'myId0',
						'name' => 'myName[]',
						'class' => 'one two',
						'value' => '0',
						'disabled' => 'disabled',
						'checked' => 'checked'
					),
					'parent' => array('tag' => 'li')
				)
			)
		);
	}

	/**
	 * Test the getInput method.
	 *
	 * @return void
	 *
	 * @dataProvider dataGetInput
	 */
	public function testGetInput($xml, $value, $expectedTagAttr)
	{
		$field = new CheckboxesField;

		$xml = new SimpleXMLElement($xml);
		$this->assertThat(
			$field->setup($xml, $value),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertTag(
			$expectedTagAttr,
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return attributes correctly.'
		);
		
	}

	public function dataGetOptions()
	{
		return array(
			array('<option value="0" onclick="foobar();">No</option>'
				. '<item value="1">Yes</item>',
					array(
						//presentInArray#optionNumber => optionArray
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
	 * @dataProvider dataGetOptions
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetOptions($optionTag, $expected)
	{
		$field = new CheckboxesField;

		$fieldStartTag = '<field name="myName" type="checkboxes">';
		$fieldEndTag = '</field>';

		$xml = new SimpleXmlElement($fieldStartTag . $optionTag . $fieldEndTag);
		$this->assertThat(
			$field->setup($xml, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$options = TestHelper::invoke($field, 'getOptions');

		foreach ($expected as $inOrNot => $expectedOption) {
			$expected = $inOrNot[0] == '1' ? true : false;
			$i = substr($inOrNot, 2);

			$this->assertEquals(
				in_array((object)$expectedOption, $options),
				$expected,
				'Line:' . __LINE__ . ' The getOption method should compute option #'
				. $i . ' correctly.'
			);
		}
	}
}
