<?php
/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field_Radio;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldRadioTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependancies for the test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		// The real class cannot be autoloaded
		\Joomla\Form\FormHelper::loadFieldClass('radio');

		parent::setUp();
	}

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

		/** @var Field_Radio $field */
		$field = \Joomla\Form\FormHelper::loadFieldType('radio');
		$field->setForm($form);

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

	/**
	 * Test the getOptions method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetOptions()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load('<form><field name="radio" type="radio"><option value="0">No</option><item value="1">Yes</item></field></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		/** @var Field_Radio $field */
		$field = \Joomla\Form\FormHelper::loadFieldType('radio');
		$field->setForm($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			strlen($field->input),
			$this->logicalNot(
				$this->StringContains('Yes')
			),
			'Line:' . __LINE__ . ' The field should not contain a Yes option.'
		);
	}
}
