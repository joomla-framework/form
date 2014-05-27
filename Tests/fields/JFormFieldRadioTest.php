<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field\RadioField;
use SimpleXmlElement;
/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldRadioTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the getInput method.
	 *
	 * @return void
	 */
	public function testGetInput()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load('<form><field name="radio" type="radio" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new RadioField($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			strlen($field->input),
			$this->greaterThan(0),
			'Line:' . __LINE__ . ' The getInput method should return something without error.'
		);

		// TODO: Should check all the attributes have come in properly.
	}

	public function dataGetOptions()
	{
		return array(
			array('<option value="0" onclick="foobar();">No</option><option value="1">Yes</option>',
					array(
						array(
							'value' => '0',
							'text' => 'No',
							'disable' => false,
							'class' => '',
							'onclick' => 'foobar();'
						),
						array(
							'value' => '1',
							'text' => 'Yes',
							'disable' => false,
							'class' => '',
							'onclick' => ''
						),
					),
				),
			array('<option value="oof" disabled="true">Foo</option><option value="rab" class="lorem">Bar</option>',
					array(
						array(
							'value' => 'oof',
							'text' => 'Foo',
							'disable' => true,
							'class' => '',
							'onclick' => ''
						),
						array(
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
		$field = new RadioField;

		$fieldStartTag = '<field name="radio" type="radio">';
		$fieldEndTag = '</field>';

		$xml = new SimpleXmlElement($fieldStartTag . $optionTag . $fieldEndTag);
		$this->assertThat(
			$field->setup($xml, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$options = TestHelper::invoke($field, 'getOptions');
		
		$i = 0;
		foreach ($expected as $expectedOption) {
			foreach ($expectedOption as $attr => $value) {
				$this->assertEquals(
					$options[$i]->$attr,
					$value,
					'Line:' . __LINE__ . ' The getOption method should compute ' . $attr . ' correctly'
				);
			}

			++$i;
		}
	}
}
