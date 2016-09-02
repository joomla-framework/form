<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field_Integer;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldIntegerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependancies for the test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		// The real class cannot be autoloaded
		include_once __DIR__ . '/../../Field/Integer.php';

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
			$form->load('<form><field name="integer" type="integer" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Integer($form);

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
			$form->load('<form><field name="integer" type="integer" first="1" last="-5" step="1"/></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Integer($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			$field->input,
			$this->logicalNot(
				$this->StringContains('<option')
			),
			'Line:' . __LINE__ . ' The field should not contain any options.'
		);

		$this->assertThat(
			$form->load('<form><field name="integer" type="integer" first="-7" last="-5" step="1"/></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Integer($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			$field->input,
			$this->StringContains('<option value="-7">-7</option>'),
			'Line:' . __LINE__ . ' The field should contain -7 through -5 as options.'
		);

		$this->assertThat(
			$form->load('<form><field name="integer" type="integer" first="-7" last="-5" step="-1"/></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Integer($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			$field->input,
			$this->logicalNot(
				$this->StringContains('<option')
			),
			'Line:' . __LINE__ . ' The field should not contain any options.'
		);

		$this->assertThat(
			$form->load('<form><field name="integer" type="integer" first="-5" last="-7" step="-1"/></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Integer($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			$field->input,
			$this->StringContains('<option value="-7">-7</option>'),
			'Line:' . __LINE__ . ' The field should contain -5 through -7 as options.'
		);
	}
}
