<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Field\TextField;
use SimpleXMLElement;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldTextTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the getInput method.
	 *
	 * @return void
	 */
	public function testGetInput()
	{
		$field = new TextField;

		$xml = new SimpleXMLElement('<field type="text" id="myId" name="myName" />');
		$this->assertThat(
			$field->setup($xml, 'aValue'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);
		
		$this->assertRegExp(
			'/<input[\s]+type="text"[\s]*name="myName"[\s]*id="myId"[\s]*value="aValue"[\s]*[\/]>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should return something without error.'
		);

		$xml = new SimpleXMLElement('<field type="text" id="myId" name="myName" size="0" maxlength="0" class="foo bar" readonly="true" disabled="true" onchange="barFoo();" />');
		$this->assertThat(
			$field->setup($xml, 'aValue'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertRegExp(
			'/<input[\s]+.*size="0".*\/>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return size attribute correctly.'
		);

		$this->assertRegExp(
			'/<input[\s]+.*maxlength="0".*\/>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return maxlength attribute correctly.'
		);

		$this->assertRegExp(
			'/<input[\s]+.*class="foo bar".*\/>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return class attribute correctly.'
		);

		$this->assertRegExp(
			'/<input[\s]+.*readonly([\s]+.*|="readonly".*)\/>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return readonly attribute correctly.'
		);

		$this->assertRegExp(
			'/<input[\s]+.*disabled([\s]+.*|="disabled".*)\/>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return disabled attribute correctly.'
		);

		$this->assertRegExp(
			'/<input[\s]+.*onchange="barFoo\(\);".*\/>/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should compute and return onchange attribute correctly.'
		);
	}
}
