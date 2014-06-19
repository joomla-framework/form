<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Field\CheckboxField;
use SimpleXMLElement;

/**
 * Test class for JForm.
 *
 * @coversDefaultClass Joomla\Form\Field\CheckboxField
 * @since  1.0
 */
class JFormFieldCheckboxTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test data for getInput test
	 *
	 * @return  array
	 *
	 * @since __VERSION_NO__
	 */
	public function dataGetInput()
	{
		return array(
			array(
				'<field type="checkbox" id="myId" name="myName" />',
				'',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'checkbox',
						'id' => 'myId',
						'name' => 'myName'
					)
				),
			),
			array(
				'<field type="checkbox" id="myId" name="myName" checked="true" />',
				'',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'checkbox',
						'id' => 'myId',
						'name' => 'myName',
						'checked' => 'checked'
					)
				),
			),
			array(
				'<field type="checkbox" id="myId" name="myName" />',
				'0',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'checkbox',
						'id' => 'myId',
						'name' => 'myName',
						'checked' => 'checked'
					)
				),
			),
			array(
				'<field type="checkbox" id="myId" name="myName" value="aVal" class="foo bar" disabled="true" onclick="barFoo();" />',
				'aValue',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'checkbox',
						'id' => 'myId',
						'value' => 'aVal',
						'class' => 'foo bar',
						'disabled' => 'disabled',
						'onclick' => 'barFoo();',
						'checked' => 'checked'
					)
				),
			),
		);
	}

	/**
	 * Test the getInput method.
	 *
	 * @param   string  $xml              @todo
	 * @param   string  $value            @todo
	 * @param   string  $expectedTagAttr  @todo
	 *
	 * @return void
	 *
	 * @covers ::getInput
	 * @dataProvider dataGetInput
	 * @since __VERSION_NO__
	 */
	public function testGetInput($xml, $value, $expectedTagAttr)
	{
		$field = new CheckboxField;

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
}
