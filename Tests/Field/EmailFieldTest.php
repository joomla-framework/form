<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\EmailField;

/**
 * Test class for Joomla\Form\Field\EmailField.
 *
 * @coversDefaultClass  Joomla\Form\Field\EmailField
 */
class EmailFieldTest extends \PHPUnit_Framework_TestCase
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
				'<field type="email" id="myId" name="myName" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'email',
						'id' => 'myId',
						'name' => 'myName'
					)
				),
			),
			array(
				'<field type="email" id="myId" name="myName" size="0" maxlength="0" class="foo bar" readonly="true" disabled="true" onchange="barFoo();" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'email',
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
	 * @param   string  $xml              @todo
	 * @param   string  $expectedTagAttr  @todo
	 *
	 * @covers        ::getInput
	 * @dataProvider  dataGetInput
	 */
	public function testGetInput($xml, $expectedTagAttr)
	{
		$field = new EmailField;

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
}
