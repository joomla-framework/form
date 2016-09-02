<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field_Hidden;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldHiddenTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependancies for the test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		// The real class cannot be autoloaded
		include_once __DIR__ . '/../../Field/Hidden.php';

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

		// Test a traditional hidden field type.

		$this->assertThat(
			$form->load('<form><field name="hidden" type="hidden" label="foo" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Hidden($form);

		$this->assertThat(
			$form->getLabel('hidden'),
			$this->equalTo(''),
			'Line:' . __LINE__ . ' The label of a hidden element should be nothing.'
		);

		// Test a field with attribute hidden = true.

		$this->assertThat(
			$form->load('<form><field name="hidden" type="text" label="foo" hidden="true" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Hidden($form);

		$this->assertThat(
			$form->getLabel('hidden'),
			$this->equalTo(''),
			'Line:' . __LINE__ . ' The label of a hidden element should be nothing.'
		);

		// Test a field with attribute hidden = false.

		$this->assertThat(
			$form->load('<form><field name="hidden" type="text" label="foo" hidden="false" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Hidden($form);

		$this->assertThat(
			$form->getLabel('hidden'),
			$this->equalTo('<label id="hidden-lbl" for="hidden" class="">foo</label>'),
			'Line:' . __LINE__ . ' The label of a non-hidden element should be some HTML.'
		);
	}
}
