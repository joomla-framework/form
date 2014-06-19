<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Field\FileField;
use SimpleXMLElement;

/**
 * Test class for JForm.
 *
 * @coversDefaultClass Joomla\Form\Field\FileField
 * @since  1.0
 */
class JFormFieldFileTest extends \PHPUnit_Framework_TestCase
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
				'<field type="file" id="myId" name="myName" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'file',
						'id' => 'myId',
						'name' => 'myName',
					)
				),
			),
			array(
				'<field type="file" id="myId" name="myName" accept="image/*" size="0" class="foo bar" disabled="true" onchange="barFoo();" />',
				array(
					'tag' => 'input',
					'attributes' => array(
						'type' => 'file',
						'id' => 'myId',
						'size' => '0',
						'accept' => 'image/*',
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
	 * @covers ::getInput
	 * @dataProvider dataGetInput
	 * @since __VERSION_NO__
	 */
	public function testGetInput($xml, $expectedTagAttr)
	{
		$field = new FileField;

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
