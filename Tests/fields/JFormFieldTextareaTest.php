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
 * @since  1.0
 */
class JFormFieldTextareaTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the getInput method.
	 *
	 * @return void
	 */
	public function testGetInput()
	{
		$field = new TextareaField;

		$xml = new SimpleXMLElement('<field type="textarea" id="myId" name="myName" />');
		$this->assertThat(
			$field->setup($xml, 'aValue'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);
		
		$this->assertRegExp(
			'/<textarea[\s]+name="myName"[\s]*id="myId"[\s]*>aValue<\/textarea>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should return something without error.'
		);

		$xml = new SimpleXMLElement('<field type="textarea" id="myId" name="myName" rows="0" cols="0" class="foo bar" disabled="true" onchange="barFoo();" />');
		$this->assertThat(
			$field->setup($xml, 'aValue'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertRegExp(
			'/<textarea[\s]+.*class="foo bar".*/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return class attribute correctly.'
		);

		$this->assertRegExp(
			'/<textarea[\s]+.*disabled([\s]+.*|="disabled".*)/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return disabled attribute correctly.'
		);

		$this->assertRegExp(
			'/<textarea[\s]+.*cols="0".*/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return columns attribute correctly.'
		);

		$this->assertRegExp(
			'/<textarea[\s]+.*rows="0".*/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return rows attribute correctly.'
		);

		$this->assertRegExp(
			'/<textarea[\s]+.*onchange="barFoo\(\);".*/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return onchange attribute correctly.'
		);
	}
}
