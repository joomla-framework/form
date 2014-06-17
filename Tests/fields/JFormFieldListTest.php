<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field\ListField;
use SimpleXmlElement;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldListTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test data for getInput test
	 *
	 * @return  array
	 */
	public function dataGetInput()
	{
		return array(
			array(
				'<field name="myName" id="myId" type="list" class="foo bar" disabled="true">'
					. '<option value="0">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'class' => 'foo bar',
						'name' => 'myName',
						'disabled' => 'disabled'
					),
					'children' => array(
						'only' => array('tag' => 'option'),
						'count' => 2
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="list" multiple="true" size="0" onchange="barFoo();">'
					. '<option value="0">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => 'myName[]',
						'multiple' => 'multiple',
						'size' => '0',
						'onchange' => 'barFoo();'
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="list" readonly="true">'
					. '<option value="0">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => '',
						'disabled' => 'disabled'
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="list" readonly="true">'
					. '<option value="0">No</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'myName',
						'value' => 'aValue'
					)
				)
			),
			array(
				'<field name="myName" id="myId" type="list">'
					. '<option value="aValue">To be selected</option>'
					. '<option value="1">Yes</option>'
				. '</field>',
				array(
					'tag' => 'option',
					'attributes' => array(
						'value' => 'aValue',
						'selected' => 'selected'
					)
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
	 * @return void
	 *
	 * @dataProvider dataGetInput
	 * @since __VERSION_NO__
	 */
	public function testGetInput($xml, $expectedTagAttr)
	{
		$field = new ListField;

		$xml = new SimpleXMLElement($xml);
		$this->assertThat(
			$field->setup($xml, 'aValue'),
			$this->isTrue(),
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
	 *
	 * @since __VERSION_NO__
	 */
	public function dataGetOptions()
	{
		return array(
			array('<option value="0" onclick="foobar();">No</option>'
				. '<item value="1">Yes</item>',
					array(
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
	 * @return  void
	 *
	 * @dataProvider dataGetOptions
	 * @since   1.0
	 */
	public function testGetOptions($optionTag, $expected)
	{
		$field = new ListField;

		$fieldStartTag = '<field name="myName" type="list">';
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
}
