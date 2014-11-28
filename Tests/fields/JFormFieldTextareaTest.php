<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Field\TextareaField;
use SimpleXMLElement;

/**
 * Test class for JFormFieldTel.
 *
 * @coversDefaultClass Joomla\Form\Field\TextareaField
 * @since  1.0
 */
class JFormFieldTextareaTest extends \PHPUnit_Framework_TestCase
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
	 * @return void
	 *
	 * @covers        ::getInput
	 * @dataProvider  dataGetInput
	 * @since         __VERSION_NO__
	 */
	public function testGetInput($xml, $expectedTagAttr)
	{
		$field = new TextareaField;

		$xml = new SimpleXMLElement($xml);
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
