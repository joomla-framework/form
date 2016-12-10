<?php
/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field_ImageList;

/**
 * Test class for JFormFieldImageList.
 *
 * @since  1.0
 */
class JFormFieldImageListTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependencies for the test.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		// The real class cannot be autoloaded
		\Joomla\Form\FormHelper::loadFieldClass('imagelist');

		parent::setUp();
	}

	/**
	 * Test the getInput method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInput()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load('<form><field name="imagelist" type="imagelist" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		/** @var Field_ImageList $field */
		$field = \Joomla\Form\FormHelper::loadFieldType('imagelist');
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
}
