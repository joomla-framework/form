<?php
/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Field_DatabaseConnection;

/**
 * Test class for JFormFieldDatabaseConnection.
 *
 * @since  1.0
 */
class JFormFieldDatabaseConnectionTest extends \PHPUnit_Framework_TestCase
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
		\Joomla\Form\FormHelper::loadFieldClass('databaseconnection');

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
			$form->load('<form><field name="databaseconnection" type="databaseconnection" supported="mysqli" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		/** @var Field_DatabaseConnection $field */
		$field = \Joomla\Form\FormHelper::loadFieldType('databaseconnection');
		$field->setForm($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			strlen($field->input),
			$this->greaterThan(0),
			'Line:' . __LINE__ . ' The getInput method should return something without error; in this case, a "Mysqli" option.'
		);

		$this->assertThat(
			$form->load('<form><field name="databaseconnection" type="databaseconnection" supported="non-existing" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = \Joomla\Form\FormHelper::loadFieldType('databaseconnection');
		$field->setForm($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			strlen($field->input),
			$this->greaterThan(0),
			'Line:' . __LINE__ . ' The getInput method should return something without error; in this case, a "None" option.'
		);

		// TODO: Should check all the attributes have come in properly.
	}
}
