<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\GroupedListField;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field\GroupedListField.
 *
 * @coversDefaultClass  Joomla\Form\Field\GroupedListField
 */
class GroupedListFieldTest extends \PHPUnit_Framework_TestCase
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
		$this->text = new Text(Language::getInstance('en-GB', dirname(__DIR__)));
	}

	/**
	 * Test data for getGroups test
	 *
	 * @return  array
	 */
	public function dataGetInput()
	{
		return array(
			'basic' => array(
				'inputs' => array(
					'id' => 'myId',
					'name' => 'myName',
				),
				'expected' => array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => 'myName',
					),
					'child' => array(
						'tag' => 'optgroup',
						'attributes' => array(
							'label' => 'barfoo',
						)
					)
				),
			),
			'allAttrSet' => array(
				'inputs' => array(
					'id' => 'myId',
					'name' => 'myName',
					'class' => 'aClass',
					'disabled' => 'true',
					'size' => '50',
					'multiple' => 'true',
				),
				'expected' => array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => 'myName[]',
						'class' => 'aClass',
						'disabled' => 'disabled',
						'size' => '50',
						'multiple' => 'multiple',
					)
				),
			),
			'readonlySelectNoName' => array(
				'inputs' => array(
					'id' => 'myId',
					'name' => 'myName',
					'readonly' => 'true'
				),
				'expected' => array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => '',
					)
				),
			),
			'readonlyHiddenInput' => array(
				'inputs' => array(
					'id' => 'myId',
					'name' => 'myName',
					'readonly' => 'true'
				),
				'expected' => array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'myName',
						'value' => 'setupValue',
					)
				),
			),
		);
	}

	/**
	 * Test the getInput method.
	 *
	 * @param   array  $inputs    Inputs to set the state
	 * @param   array  $expected  Expected Output tags
	 *
	 * @covers        ::getInput
	 * @dataProvider  dataGetInput
	 */
	public function testGetInput($inputs, $expected)
	{
		$xml = '<field type="groupedlist"';

		foreach ($inputs as $attr => $value)
		{
			$xml .= " $attr=\"$value\"";
		}

		$xml .= '/>';

		$field = $this->getMock('Joomla\\Form\\Field\\GroupedListField', array('getGroups'));

		// Configure the stub.
		$field->expects($this->any())
			->method('getGroups')
			->willReturn(
				array(
					'barfoo' => array(
							(object) array('value' => 'oof', 'text' => 'Foo')
					)
				)
			);
		$field->setText($this->text);

		$xml = new \SimpleXmlElement($xml);

		$this->assertTrue(
			$field->setup($xml, 'setupValue'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertTag(
			$expected,
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return attributes correctly.'
		);
	}

	/**
	 * Test data for getGroups test
	 *
	 * @return  array
	 */
	public function dataGetGroups()
	{
		return array(
			array('<option value="oof" disabled="true">Foo</option>'
				. '<option value="rab" class="lorem">Bar</option>',
				array(
					0 => array(
						(object) array(
							'value' => 'oof',
							'text' => 'Foo',
							'disable' => true,
							'class' => '',
							'onclick' => ''
						),
						(object) array(
							'value' => 'rab',
							'text' => 'Bar',
							'disable' => false,
							'class' => 'lorem',
							'onclick' => ''
						),
					),
				),
			),
			array('<group label="barfoo"><option value="oof" disabled="true">Foo</option>'
				. '<option value="rab" class="lorem">Bar</option></group>',
				array(
					'barfoo' => array(
						(object) array(
							'value' => 'oof',
							'text' => 'Foo',
							'disable' => true,
							'class' => '',
							'onclick' => ''
						),
						(object) array(
							'value' => 'rab',
							'text' => 'Bar',
							'disable' => false,
							'class' => 'lorem',
							'onclick' => ''
						),
					),
				),
			),
			array('<option value="foo">Foo</option>'
				. '<group label="barfoo"><option value="oof" disabled="true">Foo</option>'
				. '<foo>bar</foo>'
				. '<option value="rab" class="lorem">Bar</option></group>'
				. '<option value="bar">Bar</option>',
				array(
					0 => array(
						(object) array(
							'value' => 'foo',
							'text' => 'Foo',
							'disable' => false,
							'class' => '',
							'onclick' => ''
						),
					),
					'barfoo' => array(
						(object) array(
							'value' => 'oof',
							'text' => 'Foo',
							'disable' => true,
							'class' => '',
							'onclick' => ''
						),
						(object) array(
							'value' => 'rab',
							'text' => 'Bar',
							'disable' => false,
							'class' => 'lorem',
							'onclick' => ''
						),
					),
					2 => array(
						(object) array(
							'value' => 'bar',
							'text' => 'Bar',
							'disable' => false,
							'class' => '',
							'onclick' => ''
						),
					),
				),
			),
		);
	}

	/**
	 * Test the getGroups method.
	 *
	 * @param   string  $optionTag  @todo
	 * @param   string  $expected   @todo
	 *
	 * @covers ::getGroups
	 * @dataProvider dataGetGroups
	 */
	public function testGetGroups($optionTag, $expected)
	{
		$field = new GroupedListField;
		$field->setText($this->text);

		$fieldStartTag = '<field name="myName" type="groupedlist">';
		$fieldEndTag = '</field>';

		$xml = new \SimpleXmlElement($fieldStartTag . $optionTag . $fieldEndTag);
		$this->assertTrue(
			$field->setup($xml, 'value'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$groups = TestHelper::invoke($field, 'getGroups');

		foreach ($expected as $i => $expectedGroup)
		{
			$this->assertTrue(
				in_array($expectedGroup, $groups),
				'Line:' . __LINE__ . ' The getGroups method should compute group #'
				. $i . ' correctly'
			);
		}
	}

	/**
	 * Test the getGroups method.
	 *
	 * @covers             ::getGroups
	 * @expectedException  UnexpectedValueException
	 */
	public function testGetGroupsUnknownChildException()
	{
		$field = new GroupedListField;
		$field->setText($this->text);

		$fieldStartTag = '<field name="myName" type="groupedlist">';
		$optionTag = '<item value="foo">Bar</item>';
		$fieldEndTag = '</field>';

		$xml = new \SimpleXmlElement($fieldStartTag . $optionTag . $fieldEndTag);
		$this->assertTrue(
			$field->setup($xml, 'value'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$groups = TestHelper::invoke($field, 'getGroups');
	}
}
