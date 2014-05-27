<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Field\SpacerField;
use SimpleXmlElement;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldSpacerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependancies for the test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		include_once dirname(__DIR__) . '/inspectors.php';
	}

	/**
	 * Test the getInput method.
	 *
	 * @return void
	 */
	public function testGetInput()
	{
		$field = new SpacerField;

		$xml = new SimpleXmlElement('<field name="spacer" type="spacer" />');
		$this->assertThat(
			$field->setup($xml, 'aValue'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertRegExp(
			'/[\s]+/',
			$field->input,
			'Line:' . __LINE__ . ' The getInput method should return only and atleast one space character.'
		);
	}

	/**
	 * Test the getLabel method.
	 *
	 * @covers SpacerField::getTitle
	 * @return void
	 */
	public function testGetLabel()
	{
		$field = new SpacerField;

		$xml = new SimpleXmlElement('<field name="spacer" type="spacer" description="spacer" />');
		$this->assertThat(
			$field->setup($xml, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$equals = '<span class="spacer"><span class="before"></span><span class="">' .
			'<label id="spacer-lbl" class="hasTip" title="spacer::spacer">spacer</label></span>' .
			'<span class="after"></span></span>';

		$this->assertEquals(
			$field->label,
			$equals,
			'Line:' . __LINE__ . ' The getLabel method should return something without error.'
		);

		$xml = new SimpleXmlElement('<field name="spacer" type="spacer" class="text" />');
		$this->assertThat(
			$field->setup($xml, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$equals = '<span class="spacer"><span class="before"></span><span class="text">' .
			'<label id="spacer-lbl" class="">spacer</label></span><span class="after"></span></span>';

		$this->assertEquals(
			$field->label,
			$equals,
			'Line:' . __LINE__ . ' The getLabel method should return something without error.'
		);

		$xml = new SimpleXmlElement('<field name="spacer" type="spacer" class="text" label="MyLabel" />');
		$this->assertThat(
			$field->setup($xml, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$equals = '<span class="spacer"><span class="before"></span><span class="text">' .
			'<label id="spacer-lbl" class="">MyLabel</label></span><span class="after"></span></span>';

		$this->assertEquals(
			$field->label,
			$equals,
			'Line:' . __LINE__ . ' The getLabel method should return something without error.'
		);

		$xml = new SimpleXmlElement('<field name="spacer" type="spacer" hr="true" />');
		$this->assertThat(
			$field->setup($xml, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$expected = '<span class="spacer"><span class="before"></span><span class=""><hr class="" /></span>' .
			'<span class="after"></span></span>';

		$this->assertEquals(
			$field->label,
			$expected,
			'Line:' . __LINE__ . ' The getLabel method should return something without error.'
		);
	}
}
