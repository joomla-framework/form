<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Field\TelField;
use SimpleXMLElement;

/**
 * Test class for JFormFieldTel.
 *
 * @since  1.0
 */
class JFormFieldTelTest extends \PHPUnit_Framework_TestCase
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
				'<field type="tel" id="myId" name="myName" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'text',
						'id' => 'myId',
						'name' => 'myName',
					)
				),
			),
			array(
				'<field type="tel" id="myId" name="myName" size="0" maxlength="0" class="foo bar" readonly="true" disabled="true" onchange="barFoo();" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'text',
						'id' => 'myId',
						'size' => '0',
						'maxlength' => '0',
						'class' => 'foo bar',
						'readonly' => 'readonly',
						'disabled' => 'disabled',
						'onchange' => 'barFoo();',
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
		$field = new TelField;

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
