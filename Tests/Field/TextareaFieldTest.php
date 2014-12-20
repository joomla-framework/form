<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\TextareaField;

/**
 * Test class for Joomla\Form\Field\TextareaField.
 *
 * @coversDefaultClass  Joomla\Form\Field\TextareaField
 */
class TextareaFieldTest extends \PHPUnit_Framework_TestCase
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
				'<field type="textarea" id="myId" name="myName" />',
				array(
					'tag' => 'textarea',
					'attributes' => array(
						'id' => 'myId',
						'name' => 'myName',
					)
				),
			),
			array(
				'<field type="textarea" id="myId" name="myName" rows="0" cols="0" class="foo bar" disabled="true" onchange="barFoo();" />',
				array(
					'tag' => 'textarea',
					'attributes' => array(
						'id' => 'myId',
						'rows' => '0',
						'cols' => '0',
						'class' => 'foo bar',
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
		$field = new TextareaField;

		$xml = new \SimpleXMLElement($xml);
		$this->assertTrue(
			$field->setup($xml, 'aValue'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$expectedTagAttr['content'] = 'aValue';

		$this->assertTag(
			$expectedTagAttr,
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return attributes correctly.'
		);
	}
}
