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
 * @since  1.0
 */
class JFormFieldCheckboxTest extends \PHPUnit_Framework_TestCase
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
				'<field type="checkbox" id="myId" name="myName" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'checkbox',
						'id' => 'myId',
						'name' => 'myName',
					)
				),
			),
			array(
				'<field type="checkbox" id="myId" name="myName" value="aValue" class="foo bar" disabled="true" onclick="barFoo();" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'checkbox',
						'id' => 'myId',
						'value' => 'aValue',
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
	 * @return void
	 *
	 * @dataProvider dataGetInput
	 */
	public function testGetInput($xml, $expectedTagAttr)
	{
		$field = new CheckboxField;

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
}
