<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Form\Form;
use Joomla\Form\FormHelper;
use Joomla\Form\Rule;
use Joomla\Test\TestHelper;
use Joomla\Registry\Registry;

/**
 * Test class for JForm.
 *
 * @coversDefaultClass Joomla\Form\Form
 * @since  1.0
 */
class JFormTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Set up for testing
	 *
	 * @return void
	 *
	 * @since __VERSION_NO__
	 */
	public function setUp()
	{
		parent::setUp();

		include_once 'inspectors.php';
		include_once 'JFormDataHelper.php';
	}

	/**
	 * Test the Form::addNode method.
	 *
	 * @return void
	 *
	 * @covers  ::addNode
	 * @since   __VERSION_NO__
	 */
	public function testAddNode()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><fields /></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><field name="foo" /></form>');

		if ($xml1 === false || $xml2 === false)
		{
			$this->fail('Error in text XML data');
		}

		TestHelper::invoke(new Form('formName'), 'addNode', $xml1->fields, $xml2->field);

		$fields = $xml1->xpath('fields/field[@name="foo"]');
		$this->assertCount(
			1,
			$fields,
			'Line:' . __LINE__ . ' The field should be added, ungrouped.'
		);
	}

	/**
	 * Tests the Form::bind method.
	 *
	 * This method is used to load data into the JForm object.
	 *
	 * @return void
	 *
	 * @covers ::bind
	 * @since __VERSION_NO__
	 */
	public function testBind()
	{
		$form = new Form('formName');

		$data = array(
			'title' => 'Joomla Framework',
			'author' => 'Should not bind',
			'params' => array(
				'show_title' => 1,
				'show_abstract' => 0,
				'show_author' => 1,
				'categories' => array(
					1,
					2
				),
				'keywords' => array('en-GB' => 'Joomla', 'fr-FR' => 'Joomla')
			)
		);

		$xml = JFormDataHelper::$bindDocument;

		// Try to bind data with no valid xml.
		$this->assertFalse(
			$form->bind($data),
			'Line:' . __LINE__ . ' Form should not bind data with invalid xml document.'
		);

		// Check the test data loads ok.
		$this->assertTrue(
			$form->load($xml),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test invalid data format (string)
		$this->assertFalse(
			$form->bind("title:Joomla Framework;author:Should not bind;"),
			'Line:' . __LINE__ . ' Invalid format data should not bind successfully.'
		);

		// Test invalid data format (int)
		$this->assertFalse(
			$form->bind(1234),
			'Line:' . __LINE__ . ' Invalid format data should not bind successfully.'
		);

		$this->assertTrue(
			$form->bind($data),
			'Line:' . __LINE__ . ' The data should bind successfully.'
		);

		$data = TestHelper::getValue($form, 'data');

		$this->assertEquals(
			'Joomla Framework',
			$data->get('title'),
			'Line:' . __LINE__ . ' The data should bind to form field elements.'
		);

		$this->assertNull(
			$data->get('author'),
			'Line:' . __LINE__ . ' The data should not bind to unknown form field elements.'
		);

		$this->assertTrue(
			is_array($data->get('params.categories')),
			'Line:' . __LINE__ . ' The categories param should be an array.'
		);

		$registryData = new Registry($data);

		$this->assertTrue(
			$form->bind($registryData),
			'Line:' . __LINE__ . ' The data should bind successfully.'
		);

		$data = TestHelper::getValue($form, 'data');

		$this->assertEquals(
			'Joomla Framework',
			$data->get('title'),
			'Line:' . __LINE__ . ' The data should bind to form field elements.'
		);

		$this->assertNull(
			$data->get('author'),
			'Line:' . __LINE__ . ' The data should not bind to unknown form field elements.'
		);

		$this->assertTrue(
			is_array($data->get('params.categories')),
			'Line:' . __LINE__ . ' The categories param should be an array.'
		);
	}

	/**
	 * Testing methods used by the instantiated object.
	 *
	 * @return void
	 *
	 * @covers ::__construct
	 * @since __VERSION_NO__
	 */
	public function testConstruct()
	{
		$form = new Form('formName');

		$this->assertTrue(
			($form instanceof Form),
			'Line:' . __LINE__ . ' The Form constuctor should return a Form object.'
		);

		// Check the integrity of the options.

		$options = TestHelper::getValue($form, 'options');
		$this->assertTrue(
			isset($options['control']),
			'Line:' . __LINE__ . ' The Form object should contain an options array with a control setting.'
		);

		$this->assertFalse(
			$options['control'],
			'Line:' . __LINE__ . ' The control setting should be false by default.'
		);

		$form = new Form('formName', array('control' => 'jform'));

		$options = TestHelper::getValue($form, 'options');
		$this->assertEquals(
			'jform',
			$options['control'],
			'Line:' . __LINE__ . ' The control setting should be what is passed in the constructor.'
		);
	}

	/**
	 * Test for Form::filter method.
	 *
	 * @return void
	 *
	 * @covers ::filter
	 * @since __VERSION_NO__
	 */
	public function testFilter()
	{
		$form = new JFormInspector('formName');

		$xml = JFormDataHelper::$filterDocument;

		$data = array(
			'word' => 'Joomla! Framework',
			'author' => 'Should not bind',
			'params' => array(
				'show_title' => 1,
				'show_author' => false,
			),
			'default' => ''
		);

		// Check the test data loads ok.
		$this->assertFalse(
			$form->filter($data),
			'Line:' . __LINE__ . ' Filter should return false for an invalid xml document.'
		);

		$this->assertTrue(
			$form->load(JFormDataHelper::$filterDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$filtered = $form->filter($data);

		$this->assertTrue(
			is_array($filtered),
			'Line:' . __LINE__ . ' The filtered result should be an array.'
		);

		// Test that filtering is occuring (not that all filters work - done in testFilterField).

		$this->assertEquals(
			'JoomlaFramework',
			$filtered['word'],
			'Line:' . __LINE__ . ' The variable should be filtered by the "word" filter.'
		);

		$this->assertFalse(
			isset($filtered['author']),
			'Line:' . __LINE__ . ' A variable in the data not present in the form should not exist.'
		);

		$this->assertEquals(
			1,
			$filtered['params']['show_title'],
			'Line:' . __LINE__ . ' The nested variable should be present.'
		);

		$this->assertEquals(
			0,
			$filtered['params']['show_author'],
			'Line:' . __LINE__ . ' The nested variable should be present.'
		);

		// Todo : Add test for group fields
	}

	/**
	 * Test for Form::filterField method.
	 *
	 * @return void
	 *
	 * @covers ::filterField
	 * @since __VERSION_NO__
	 */
	public function testFilterField()
	{
		$form = new Form('formName');

		$xml = JFormDataHelper::$filterDocument;

		$input = '<script>alert();</script> <p>Some text.</p>';

		// Check the test data loads ok.
		$this->assertTrue(
			$form->load($xml),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Check invalid SimpleXmlElement as field
		$this->assertFalse(
			TestHelper::invoke($form, 'filterField', "field", $input),
			'Line:' . __LINE__ . ' No filter should be applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'function');
		$this->assertEquals(
			'function',
			TestHelper::invoke($form, 'filterField', $field, $input),
			'Line:' . __LINE__ . ' The function filter should be correctly applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'int');
		$this->assertEquals(
			1,
			TestHelper::invoke($form, 'filterField', $field, 'A1B2C3'),
			'Line:' . __LINE__ . ' The "int" filter should be correctly applied.'
		);

		// Todo : Correct this test case.
		$field = TestHelper::invoke($form, 'findField', 'int_array');
		$this->assertEquals(
			array(0,0),
			TestHelper::invoke($form, 'filterField', $field, array('A1B2C3', false)),
			'Line:' . __LINE__ . ' The "int_array" filter should be correctly applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'method');
		$this->assertEquals(
			'method',
			TestHelper::invoke($form, 'filterField', $field, $input),
			'Line:' . __LINE__ . ' The class method filter should be correctly applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'raw');
		$this->assertEquals(
			$input,
			TestHelper::invoke($form, 'filterField', $field, $input),
			'Line:' . __LINE__ . ' "The safehtml" filter should be correctly applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'safehtml');
		$this->assertEquals(
			'alert(); <p>Some text.</p>',
			TestHelper::invoke($form, 'filterField', $field, $input),
			'Line:' . __LINE__ . ' "The safehtml" filter should be correctly applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'unset');
		$this->assertNull(
			TestHelper::invoke($form, 'filterField', $field, $input),
			'Line:' . __LINE__ . ' The value should be unset.'
		);

		$field = TestHelper::invoke($form, 'findField', 'word');
		$this->assertEquals(
			'scriptalertscriptpSometextp',
			TestHelper::invoke($form, 'filterField', $field, $input),
			'Line:' . __LINE__ . ' The "word" filter should be correctly applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'url');
		$this->assertEquals(
			'http://example.com',
			TestHelper::invoke($form, 'filterField', $field, 'http://example.com'),
			'Line:' . __LINE__ . ' A field with a valid protocol should return as is.'
		);

		$this->assertEquals(
			'http://alert(); Some text.',
			TestHelper::invoke($form, 'filterField', $field, 'http://<script>alert();</script> <p>Some text.</p>'),
			'Line:' . __LINE__ . ' A "url" with scripts should be should be filtered.'
		);

		$this->assertEquals(
			'https://example.com',
			TestHelper::invoke($form, 'filterField', $field, 'https://example.com'),
			'Line:' . __LINE__ . ' A field with a valid protocol that is not http should return as is.'
		);

		// Todo : fix this test. Undefined Uri::root

		/*$this->assertThat(
			TestHelper::invoke($form, 'filterField', $field, 'index.php'),
			$this->equalTo('http://example.com'),
			'Line:' . __LINE__ . ' A field without a protocol should return with a http:// protocol.'
		);*/

		$this->assertEquals(
			'http://example.com',
			TestHelper::invoke($form, 'filterField', $field, 'example.com'),
			'Line:' . __LINE__ . ' A field without a protocol should return with a http:// protocol.'
		);

		$this->assertEquals(
			'http://hptarr.com',
			TestHelper::invoke($form, 'filterField', $field, 'hptarr.com'),
			'Line:' . __LINE__ . ' A field without a protocol and starts with t should return with a http:// protocol.'
		);

		$this->assertEquals(
			'',
			TestHelper::invoke($form, 'filterField', $field, ''),
			'Line:' . __LINE__ . ' An empty "url" filter return nothing.'
		);

		$field = TestHelper::invoke($form, 'findField', 'default');
		$this->assertEquals(
			'alert(); Some text.',
			TestHelper::invoke($form, 'filterField', $field, $input),
			'Line:' . __LINE__ . ' The default strict filter should be correctly applied.'
		);

		$field = TestHelper::invoke($form, 'findField', 'tel');
		$this->assertEquals(
			'31.4211234567',
			TestHelper::invoke($form, 'filterField', $field, '+31 42 1123 4567'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertEquals(
			'222.3333333333',
			TestHelper::invoke($form, 'filterField', $field, '222.3333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertEquals(
			'222.3333333333',
			TestHelper::invoke($form, 'filterField', $field, '+222.3333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			TestHelper::invoke($form, 'filterField', $field, '+2,2,2.3,3,3,3,3,3,3,3,3,3,3,3'),
			$this->equalTo('222.333333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			TestHelper::invoke($form, 'filterField', $field, '33333333333'),
			$this->equalTo('.33333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			TestHelper::invoke($form, 'filterField', $field, '222333333333333'),
			$this->equalTo('222.333333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			TestHelper::invoke($form, 'filterField', $field, '1 (202) 555-5555'),
			$this->equalTo('1.2025555555'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			TestHelper::invoke($form, 'filterField', $field, '+222.33333333333x444'),
			$this->equalTo('222.33333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			TestHelper::invoke($form, 'filterField', $field, 'ABCabc/?.!*x'),
			$this->equalTo(''),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
	}

	/**
	 * Test the Form::findField method.
	 *
	 * @return void
	 *
	 * @covers ::findField
	 * @since __VERSION_NO__
	 */
	public function testFindField()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$xml = JFormDataHelper::$findFieldDocument;

		// Finding fields in a form with invalid xml docuement.
		$this->assertFalse(
			$form->findField('bogus'),
			'Line:' . __LINE__ . ' Form with invalid xml docuement should return false.'
		);

		// Check the test data loads ok.
		$this->assertTrue(
			$form->load($xml),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertFalse(
			$form->findField('bogus'),
			'Line:' . __LINE__ . ' An ungrouped field that does not exist should return false.'
		);

		$this->assertFalse(
			$form->findField('title', 'bogus'),
			'Line:' . __LINE__ . ' A field in a group that does not exist should return false.'
		);

		// Test various find combinations.

		$field = $form->findField('title', null);
		$this->assertEquals(
			'root',
			(string) $field['place'],
			'Line:' . __LINE__ . ' A known ungrouped field should load successfully.'
		);

		$field = $form->findField('title', 'params');
		$this->assertEquals(
			'child',
			(string) $field['place'],
			'Line:' . __LINE__ . ' A known grouped field should load successfully.'
		);

		$field = $form->findField('alias');
		$this->assertEquals(
			'alias',
			(string) $field['name'],
			'Line:' . __LINE__ . ' A known field in a fieldset should load successfully.'
		);

		$field = $form->findField('show_title', 'params');
		$this->assertEquals(
			'1',
			(string) $field['default'],
			'Line:' . __LINE__ . ' A known field in a group fieldset should load successfully.'
		);
	}

	/**
	 * Tests the Form::findFieldsByFieldset method.
	 *
	 * @return void
	 *
	 * @covers ::findFieldsByFieldset
	 * @since __VERSION_NO__
	 */
	public function testFindFieldsByFieldset()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Finding fields in a form with invalid xml docuement.
		$this->assertFalse(
			$form->findFieldsByFieldset('bogus'),
			'Line:' . __LINE__ . ' Form with invalid xml docuement should return false.'
		);

		$this->assertTrue(
			$form->load(JFormDataHelper::$findFieldsByFieldsetDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertEmpty(
			$form->findFieldsByFieldset('bogus'),
			'Line:' . __LINE__ . ' An unknown fieldset should return an empty array.'
		);

		// Test regular usage.

		$this->assertCount(
			3,
			$form->findFieldsByFieldset('params-basic'),
			'Line:' . __LINE__ . ' The params-basic fieldset has 3 fields.'
		);

		$this->assertCount(
			2,
			$form->findFieldsByFieldset('params-advanced'),
			'Line:' . __LINE__ . ' The params-advanced fieldset has 2 fields.'
		);
	}

	/**
	 * Test the Form::findFieldsByGroup method.
	 *
	 * @return void
	 *
	 * @covers ::findFieldsByGroup
	 * @since __VERSION_NO__
	 */
	public function testFindFieldsByGroup()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Finding fields in a form with invalid xml docuement.
		$this->assertFalse(
			$form->findFieldsByGroup('bogus'),
			'Line:' . __LINE__ . ' Form with invalid xml docuement should return false.'
		);

		$this->assertTrue(
			$form->load(JFormDataHelper::$findFieldsByGroupDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertEmpty(
			$form->findFieldsByGroup('bogus'),
			'Line:' . __LINE__ . ' A group that does not exist should return an empty array.'
		);

		// Test all fields.

		$this->assertCount(
			11,
			$form->findFieldsByGroup(),
			'Line:' . __LINE__ . ' There are 9 field elements in total.'
		);

		// Test ungrouped fields.

		$this->assertCount(
			4,
			$form->findFieldsByGroup(false),
			'Line:' . __LINE__ . ' There are 4 ungrouped field elements.'
		);

		// Test grouped fields.

		$this->assertCount(
			2,
			$form->findFieldsByGroup('details'),
			'Line:' . __LINE__ . ' The details group has 2 field elements.'
		);

		$this->assertCount(
			3,
			$form->findFieldsByGroup('params'),
			'Line:' . __LINE__ . ' The params group has 3 field elements, including one nested in a fieldset.'
		);

		// Test nested fields.

		$this->assertCount(
			2,
			$form->findFieldsByGroup('level1', true),
			'Line:' . __LINE__ . ' There should be 2 nested fields.'
		);
	}

	/**
	 * Test the Form::findGroup method.
	 *
	 * @return void
	 *
	 * @covers ::findGroup
	 * @since __VERSION_NO__
	 */
	public function testFindGroup()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Finding fields in a form with invalid xml docuement.
		$this->assertFalse(
			$form->findGroup('bogus'),
			'Line:' . __LINE__ . ' Form with invalid xml docuement should return false.'
		);

		$this->assertTrue(
			$form->load(JFormDataHelper::$findGroupDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertEmpty(
			$form->findGroup('bogus'),
			'Line:' . __LINE__ . ' A group that does not exist should return an empty array.'
		);

		$this->assertCount(
			1,
			$form->findGroup('params'),
			'Line:' . __LINE__ . ' The group should have one element.'
		);

		$this->assertEmpty(
			$form->findGroup('bogus.data'),
			'Line:' . __LINE__ . ' A group path that does not exist should return an empty array.'
		);

		// Check that an existant field returns something.
		$this->assertCount(
			1,
			$form->findGroup('params.cache'),
			'Line:' . __LINE__ . ' The group should have one element.'
		);
	}

	/**
	 * Test for Form::getErrors method.
	 *
	 * @return void
	 *
	 * @covers ::getErrors
	 * @since __VERSION_NO__
	 */
	public function testGetErrors()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$validateDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$fail = array(
			'boolean' => 'comply',
			'required' => '',
		);

		$this->assertFalse(
			$form->validate($fail),
			'Line:' . __LINE__ . ' Validating this data should fail.'
		);

		$errors = $form->getErrors($fail);
		$this->assertCount(
			3,
			$errors,
			'Line:' . __LINE__ . ' This data should invoke 3 errors.'
		);

		$this->assertTrue(
			$errors[0] instanceof \Exception,
			'Line:' . __LINE__ . ' The errors should be exception objects.'
		);
	}

	/**
	 * Test the Form::getField method.
	 *
	 * @return void
	 *
	 * @covers ::getField
	 * @since __VERSION_NO__
	 */
	public function testGetField()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check for errors.

		$this->assertFalse(
			$form->getField('title'),
			'Line:' . __LINE__ . ' A form with invalid xml document should return false.'
		);

		// Load a xml document in the form.
		$this->assertTrue(
			$form->load(JFormDataHelper::$getFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertFalse(
			$form->getField('bogus'),
			'Line:' . __LINE__ . ' A field that does not exist should return false.'
		);

		$this->assertFalse(
			$form->getField('show_title'),
			'Line:' . __LINE__ . ' A field that does exists in a group, without declaring the group, should return false.'
		);

		$this->assertFalse(
			$form->getField('show_title', 'bogus'),
			'Line:' . __LINE__ . ' A field in a group that does not exist should return false.'
		);

		// Checking value defaults.

		$this->assertEquals(
			'',
			$form->getField('title')->value,
			'Line:' . __LINE__ . ' Prior to binding data, the defaults in the field should be used.'
		);

		$this->assertEquals(
			1,
			$form->getField('show_title', 'params')->value,
			'Line:' . __LINE__ . ' Prior to binding data, the defaults in the field should be used.'
		);

		// Check values after binding.

		$data = array(
			'title' => 'The title',
			'show_title' => 3,
			'params' => array(
				'show_title' => 2,
			)
		);

		$this->assertTrue(
			$form->bind($data),
			'Line:' . __LINE__ . ' The input data should bind successfully.'
		);

		$this->assertEquals(
			'The title',
			$form->getField('title')->value,
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);

		$this->assertEquals(
			2,
			$form->getField('show_title', 'params')->value,
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);

		// Check binding with an object.

		$data = new \stdClass;
		$data->title = 'The new title';
		$data->show_title = 5;
		$data->params = new \stdClass;
		$data->params->show_title = 4;

		$this->assertTrue(
			$form->bind($data),
			'Line:' . __LINE__ . ' The input data should bind successfully.'
		);

		$this->assertEquals(
			'The new title',
			$form->getField('title')->value,
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);

		$this->assertEquals(
			4,
			$form->getField('show_title', 'params')->value,
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);
	}

	/**
	 * Test for Form::getFieldAttribute method.
	 *
	 * @return void
	 *
	 * @covers ::getFieldAttribute
	 * @expectedException UnexpectedValueException
	 * @since __VERSION_NO__
	 */
	public function testGetFieldAttributeInvalidXml()
	{
		$form = new JFormInspector('form1');

		// Check for invalid form document.
		$this->assertFalse(
			$form->getFieldAttribute('title', 'description'),
			'Line:' . __LINE__ . ' A form with invalid xml document should return false.'
		);
	}

	/**
	 * Test for Form::getFieldAttribute method.
	 *
	 * @return void
	 *
	 * @covers ::getFieldAttribute
	 * @since __VERSION_NO__
	 */
	public function testGetFieldAttribute()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$getFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test error handling.

		$this->assertEquals(
			'Help',
			$form->getFieldAttribute('bogus', 'unknown', 'Help'),
			'Line:' . __LINE__ . ' The default value of the unknown field should be returned.'
		);

		$this->assertEquals(
			'Use this',
			$form->getFieldAttribute('title', 'unknown', 'Use this'),
			'Line:' . __LINE__ . ' The default value of the unknown attribute should be returned.'
		);

		// Test general usage.

		$this->assertEquals(
			'The title.',
			$form->getFieldAttribute('title', 'description'),
			'Line:' . __LINE__ . ' The value of the attribute should be returned.'
		);

		$this->assertEquals(
			'The title.',
			$form->getFieldAttribute('title', 'description', 'Use this'),
			'Line:' . __LINE__ . ' The value of the attribute should be returned.'
		);
	}

	/**
	 * Test the Form::getFormControl method.
	 *
	 * @return void
	 *
	 * @covers ::getFormControl
	 * @since __VERSION_NO__
	 */
	public function testGetFormControl()
	{
		$form = new Form('form8ion');

		$this->assertEquals(
			'',
			$form->getFormControl(),
			'Line:' . __LINE__ . ' A form control that has not been specified should return nothing.'
		);

		$form = new Form('form8ion', array('control' => 'jform'));

		$this->assertEquals(
			'jform',
			$form->getFormControl(),
			'Line:' . __LINE__ . ' The form control should agree with the options passed in the constructor.'
		);
	}

	/**
	 * Test for Form::getInstance.
	 *
	 * @return void
	 *
	 * @covers ::getInstance
	 * @expectedException InvalidArgumentException
	 * @since __VERSION_NO__
	 */
	public function testGetInstanceNoDataException()
	{
		$form = new Form('form1');

		$this->assertFalse(
			Form::getInstance('form1') == $form,
			'Line:' . __LINE__ . ' getInstance should throw exception if no data is given.'
		);
	}

	/**
	 * Test for Form::getInstance.
	 *
	 * @return void
	 *
	 * @covers ::getInstance
	 * @expectedException RuntimeException
	 * @since __VERSION_NO__
	 */
	public function testGetInstanceMalformedDataException()
	{
		$form = new Form('form2');

		$this->assertFalse(
			Form::getInstance('form2', '<abc') == $form,
			'Line:' . __LINE__ . ' getInstance should thow exception if data is malformed.'
		);
	}

	/**
	 * Test for Form::getInstance.
	 *
	 * @return void
	 *
	 * @covers ::getInstance
	 * @expectedException RuntimeException
	 * @since __VERSION_NO__
	 */
	public function testGetInstanceNoDataFileException()
	{
		$form = new Form('form3');

		$this->assertFalse(
			Form::getInstance('form3', 'abc') == $form,
			'Line:' . __LINE__ . ' getInstance should thow exception if access to data file gives error.'
		);
	}

	/**
	 * Test for Form::getInstance.
	 *
	 * @return void
	 *
	 * @covers ::getInstance
	 * @since __VERSION_NO__
	 */
	public function testGetInstance()
	{
		$form = JFormInspector::getInstance('form1',
			JFormDataHelper::$getFieldDocument
			);

		$this->assertTrue(
			JFormInspector::getInstance('form1') == $form,
			'Line:' . __LINE__ . ' getInstance should return the correct instance of form.'
		);
	}

	/**
	 * Test for Form::getGroup method.
	 *
	 * @return void
	 *
	 * @covers ::getGroup
	 * @since __VERSION_NO__
	 */
	public function testGetGroup()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$findFieldsByGroupDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test error handling.

		$this->assertEmpty(
			$form->getGroup('bogus'),
			'Line:' . __LINE__ . ' A group that does not exist should return an empty array.'
		);

		// Test general usage.

		$this->assertCount(
			3,
			$form->getGroup('params'),
			'Line:' . __LINE__ . ' The params group should have 3 field elements.'
		);

		$this->assertCount(
			2,
			$form->getGroup('level1', true),
			'Line:' . __LINE__ . ' The level1 group should have 2 nested field elements.'
		);

		$this->assertEquals(
			array('level1_field1', 'level1_level2_field2'),
			array_keys($form->getGroup('level1', true)),
			'Line:' . __LINE__ . ' The level1 group should have 2 nested field elements.'
		);

		$this->assertCount(
			1,
			$form->getGroup('level1.level2'),
			'Line:' . __LINE__ . ' The level2 group should have 1 field element.'
		);
	}

	/**
	 * Test for Form::getInput method.
	 *
	 * @return void
	 *
	 * @covers ::getInput
	 * @since __VERSION_NO__
	 */
	public function testGetInput()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertEquals(
			'',
			$form->getInput('bogus'),
			'Line:' . __LINE__ . ' The method should return a empty string if field is not found.'
		);

		$this->assertEquals(
			'<input type="text" name="title" id="title_id" value="The Title" class="inputbox required"/>',
			$form->getInput('title', null, 'The Title'),
			'Line:' . __LINE__ . ' The method should return a simple input text field.'
		);

		$this->assertEquals(
			'<fieldset id="params_show_title" class="radio">' .
				'<input type="radio" id="params_show_title0" name="params[show_title]" value="1"/>' .
				'<label for="params_show_title0">' . Text::_('JYes') . '</label>' .
				'<input type="radio" id="params_show_title1" name="params[show_title]" value="0" checked="checked"/>' .
				'<label for="params_show_title1">' . Text::_('JNo') . '</label>' .
			'</fieldset>',
			$form->getInput('show_title', 'params', '0'),
			'Line:' . __LINE__ . ' The method should return a radio list.'
		);

		$form = new JFormInspector('form1', array('control' => 'jform'));

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertEquals(
			'<select id="jform_params_colours" name="jform[params][colours][]" multiple="multiple">' .
				"\n" . '	<option value="red">Red</option>' .
				"\n" . '	<option value="blue" selected="selected">Blue</option>' .
				"\n" . '	<option value="green">Green</option>' .
				"\n" . '	<option value="yellow">Yellow</option>' .
			"\n" . '</select>' . "\n",
			$form->getInput('colours', 'params', 'blue'),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test translate default
		$this->assertEquals(
			'<input type="text" name="jform[translate_default]" id="jform_translate_default" value="DEFAULT_KEY"/>',
			$form->getInput('translate_default'),
			'Line:' . __LINE__ .
			' The method should return a simple input text field whose value is untranslated since the DEFAULT_KEY does not exist in the language.'
		);

		$lang = Language::getInstance();
		$debug = $lang->setDebug(true);
		$this->assertEquals(
			'<input type="text" name="jform[translate_default]" id="jform_translate_default" value="??DEFAULT_KEY??"/>',
			$form->getInput('translate_default'),
			'Line:' . __LINE__ . ' The method should return a simple input text field whose value is marked untranslated.'
		);

		$lang->load('form_test', __DIR__);
		$this->assertEquals(
			'<input type="text" name="jform[translate_default]" id="jform_translate_default" value="My Default"/>',
			$form->getInput('translate_default'),
			'Line:' . __LINE__ . ' The method should return a simple input text field whose value is translated.'
		);
		$lang->setDebug($debug);
	}

	/**
	 * Test for Form::getLabel method.
	 *
	 * @return void
	 *
	 * @covers ::getLabel
	 * @since __VERSION_NO__
	 */
	public function testGetLabel()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertEquals(
			'',
			$form->getLabel('bogus'),
			'Line:' . __LINE__ . ' The method should return a empty string if field is not found.'
		);

		$this->assertEquals(
			'<label id="title_id-lbl" for="title_id" class="hasTip required" ' .
				'title="Title::The title.">Title<span class="star">&#160;*</span></label>',
			$form->getLabel('title'),
			'Line:' . __LINE__ . ' The method should return a simple label field.'
		);
	}

	/**
	 * Test the Form::getName method.
	 *
	 * @return void
	 *
	 * @covers ::getName
	 * @since __VERSION_NO__
	 */
	public function testGetName()
	{
		$form = new Form('form1');

		$this->assertEquals(
			'form1',
			$form->getName(),
			'Line:' . __LINE__ . ' The form name should agree with the argument passed in the constructor.'
		);
	}

	/**
	 * Test for Form::getValue method.
	 *
	 * @return void
	 *
	 * @covers ::getValue
	 * @since __VERSION_NO__
	 */
	public function testGetValue()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$data = array(
			'title' => 'Avatar',
		);

		$this->assertTrue(
			$form->bind($data),
			'Line:' . __LINE__ . ' The data should bind successfully.'
		);

		$this->assertEquals(
			'Avatar',
			$form->getValue('title'),
			'Line:' . __LINE__ . ' The bind value should be returned.'
		);
	}

	/**
	 * Test for Form::getFieldset method.
	 *
	 * @return void
	 *
	 * @covers ::getFieldset
	 * @since __VERSION_NO__
	 */
	public function testGetFieldset()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$getFieldsetDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertEmpty(
			$form->getFieldset('bogus'),
			'Line:' . __LINE__ . ' A fieldset that does not exist should return an empty array.'
		);

		$this->assertCount(
			4,
			$form->getFieldset('params-basic'),
			'Line:' . __LINE__ . ' There are 3 field elements in a fieldset and 1 field element marked with the fieldset attribute.'
		);

		$this->assertCount(
			8,
			$form->getFieldset(),
			'Line:' . __LINE__ . ' There are total 8 fields in the document.'
		);
	}

	/**
	 * Test for Form::getFieldsets method.
	 *
	 * @return void
	 *
	 * @covers ::getFieldsets
	 * @since __VERSION_NO__
	 */
	public function testGetFieldsets()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check for invalid form document.
		$this->assertCount(
			0,
			$form->getFieldsets(),
			'Line:' . __LINE__ . ' A form with invalid xml document should return empty array.'
		);

		$this->assertTrue(
			$form->load(JFormDataHelper::$getFieldsetsDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$sets = $form->getFieldsets();
		$this->assertCount(
			3,
			$sets,
			'Line:' . __LINE__ . ' The source data has 3 fieldsets in total.'
		);

		$this->assertEquals(
			'params-advanced',
			$sets['params-advanced']->name,
			'Line:' . __LINE__ . ' Ensure the fieldset name is correct.'
		);

		$this->assertEquals(
			'Advanced Options',
			$sets['params-advanced']->label,
			'Line:' . __LINE__ . ' Ensure the fieldset label is correct.'
		);

		$this->assertEquals(
			'The advanced options',
			$sets['params-advanced']->description,
			'Line:' . __LINE__ . ' Ensure the fieldset description is correct.'
		);

		// Test loading by group.

		$this->assertEmpty(
			$form->getFieldsets('bogus'),
			'Line:' . __LINE__ . ' A fieldset that in a group that does not exist should return an empty array.'
		);

		$sets = $form->getFieldsets('details');
		$this->assertCount(
			1,
			$sets,
			'Line:' . __LINE__ . ' The details group has one field marked with a fieldset'
		);

		$this->assertEquals(
			'params-legacy',
			$sets['params-legacy']->name,
			'Line:' . __LINE__ . ' Ensure the fieldset name is correct.'
		);
	}

	/**
	 * Test the Form::load method.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 *
	 * @return void
	 *
	 * @covers ::load
	 * @since __VERSION_NO__
	 */
	public function testLoad()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertTrue(
			($form->getXML() instanceof \SimpleXMLElement),
			'Line:' . __LINE__ . ' The internal XML should be a SimpleXMLElement object.'
		);

		// Test replace false.

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadMergeDocument, false),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertCount(
			4,
			$form->getXML()->xpath('/form/fields/field'),
			'Line:' . __LINE__ . ' There are 2 new ungrouped field and one existing field should merge, resulting in 4 total.'
		);

		// Test replace true (default).

		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadMergeDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertCount(
			6,
			$form->findFieldsByGroup(false),
			'Line:' . __LINE__ . ' There are 2 original ungrouped fields, 1 replaced and 4 new, resulting in 6 total.'
		);

		$this->assertCount(
			2,
			$form->getXML()->xpath('//fields[@name]'),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The XML has 2 fields tags with a name attribute.'
		);

		$this->assertCount(
			2,
			$form->getXML()->xpath('//fields[@name="params"]/field'),
			'Line:' . __LINE__ . ' The params fields have been merged ending with 2 elements.'
		);

		$this->assertCount(
			1,
			$form->getXML()->xpath('/form/fields/fields[@name="params"]/field[@name="show_abstract"]'),
			'Line:' . __LINE__ . ' The show_title in the params group has been replaced by show_abstract.'
		);

		$originalform = new JFormInspector('form1');
		$originalform->load(JFormDataHelper::$loadDocument);
		$originalset = $originalform->getXML()->xpath('/form/fields/field');
		$set = $form->getXML()->xpath('/form/fields/field');

		for ($i = 0; $i < count($originalset); $i++)
		{
			$this->assertEquals(
				(string) ($originalset[$i]->attributes()->name),
				(string) ($set[$i]->attributes()->name),
				'Line:' . __LINE__ . ' Replace should leave fields in the original order.'
			);
		}
	}

	/**
	 * Test the Form::load method for cases of unexpected or bad input.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 *
	 * @return void
	 *
	 * @covers ::load
	 * @since __VERSION_NO__
	 */
	public function testLoad_BadInput()
	{
		$form = new JFormInspector('form1');

		$this->assertFalse(
			$form->load(123),
			'Line:' . __LINE__ . ' A non-string should return false.'
		);

		$this->assertFalse(
			$form->load('junk'),
			'Line:' . __LINE__ . ' An invalid string should return false.'
		);

		$this->assertNull(
			$form->getXml(),
			'Line:' . __LINE__ . ' The internal XML should be false as returned from simplexml_load_string.'
		);

		$this->assertTrue(
			$form->load('<notform><test /></notform>'),
			'Line:' . __LINE__ . ' Invalid root node name from string should still load.'
		);

		$this->assertEquals(
			'form',
			$form->getXml()->getName(),
			'Line:' . __LINE__ . ' The internal XML should still be named "form".'
		);

		// Test for irregular object input.

		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(simplexml_load_string('<notform><test /></notform>')),
			'Line:' . __LINE__ . ' Invalid root node name from XML object should still load.'
		);

		$this->assertEquals(
			'form',
			$form->getXml()->getName(),
			'Line:' . __LINE__ . ' The internal XML should still be named "form".'
		);
	}

	/**
	 * Test the Form::load method for XPath data.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 *
	 * @return void
	 *
	 * @covers ::load
	 * @since __VERSION_NO__
	 */
	public function testLoad_XPath()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadXPathDocument, true, '/extension/fields'),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertEquals(
			'form',
			$form->getXml()->getName(),
			'Line:' . __LINE__ . ' The internal XML should still be named "form".'
		);

		$this->assertEquals(
			2,
			count($form->getXml()->fields->fields),
			'Line:' . __LINE__ . ' The test data has 2 fields.'
		);
	}

	/**
	 * Test for Form::loadField method.
	 *
	 * @return void
	 *
	 * @covers ::loadField
	 * @since __VERSION_NO__
	 */
	public function testLoadField()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertFalse(
			$form->loadField('bogus'),
			'Line:' . __LINE__ . ' An unknown field should return false.'
		);

		$xml = new \SimpleXMLElement('<barfoo name="foobar" required="true" type="abrakadabra" />');
		$field = $form->loadField($xml);

		$this->assertFalse(
			$field,
			'Line:' . __LINE__ . ' A invalid field element should return false.'
		);

		// Test correct usage.
		$xml = new \SimpleXMLElement('<field name="foobar" required="true" type="abrakadabra" />');
		$field = $form->loadField($xml);

		$this->assertTrue(
			$field instanceof \Joomla\Form\Field,
			'Line:' . __LINE__ . ' A field should be loaded successfully and return its instance.'
		);

		$this->assertEquals(
			'Text',
			$field->type,
			'Line:' . __LINE__ . ' An unknown field type should loaded as text field.'
		);
	}

	/**
	 * Test the Form::loadFile method.
	 *
	 * This method loads a file and passes the string to the Form::load method.
	 *
	 * @return void
	 *
	 * @covers ::loadFile
	 * @since __VERSION_NO__
	 */
	public function testLoadFile()
	{
		$form = new JFormInspector('form1');

		// Test for files that don't exist.

		$this->assertFalse(
			$form->loadFile('/tmp/example.xml'),
			'Line:' . __LINE__ . ' A file path that does not exist should return false.'
		);

		$this->assertFalse(
			$form->loadFile('notfound'),
			'Line:' . __LINE__ . ' A file name that does not exist should return false.'
		);

		// Testing loading a file by full path.

		$this->assertTrue(
			$form->loadFile(__DIR__ . '/example.xml'),
			'Line:' . __LINE__ . ' XML file by full path should load successfully.'
		);

		$this->assertTrue(
			($form->getXML() instanceof \SimpleXMLElement),
			'Line:' . __LINE__ . ' XML string should parse successfully.'
		);

		// Testing loading a file by file name.

		$form = new JFormInspector('form1');
		FormHelper::addFormPath(__DIR__);

		$this->assertTrue(
			$form->loadFile('example'),
			'Line:' . __LINE__ . ' XML file by name should load successfully.'
		);

		$this->assertTrue(
			($form->getXML() instanceof \SimpleXMLElement),
			'Line:' . __LINE__ . ' XML string should parse successfully.'
		);
	}

	/**
	 * Test the Form::mergeNode method.
	 *
	 * @return void
	 *
	 * @covers ::mergeNode
	 * @since __VERSION_NO__
	 */
	public function testMergeNode()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><field name="foo" /></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><field name="bar" type="text" /></form>');

		if ($xml1 === false || $xml2 === false)
		{
			$this->fail('Line:' . __LINE__ . ' Error in text XML data');
		}

		TestHelper::invoke(new Form('formName'), 'mergeNode', $xml1->field, $xml2->field);

		$fields = $xml1->xpath('field[@name="foo"] | field[@type="text"]');
		$this->assertCount(
			1,
			$fields,
			'Line:' . __LINE__ . ' Existing attribute "name" should merge, new attribute "type" added.'
		);
	}

	/**
	 * Test the Form::mergeNode method.
	 *
	 * @return void
	 *
	 * @covers ::mergeNodes
	 * @since __VERSION_NO__
	 */
	public function testMergeNodes()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><fields><field name="foo" type="text"/></fields></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><fields><field name="foo"  /><fields><field name="soap" /></fields></fields></form>');

		if ($xml1 === false || $xml2 === false)
		{
			$this->fail('Line:' . __LINE__ . ' Error in text XML data');
		}

		TestHelper::invoke(new Form('formName'), 'mergeNodes', $xml1->fields, $xml2->fields);

		$this->assertCount(
			1,
			$xml1->xpath('fields/field'),
			'Line:' . __LINE__ . ' The merge should have two field tags, one existing, one new.'
		);

		$this->assertCount(
			1,
			$xml1->xpath('fields/field[@name="foo"] | fields/field[@type="text"]'),
			'Line:' . __LINE__ . ' A field of the same name should merge.'
		);

		$this->assertCount(
			1,
			$xml1->xpath('fields/fields/field[@name="soap"]'),
			'Line:' . __LINE__ . ' A new field should be added.'
		);
	}

	/**
	 * Test for Form::removeField method.
	 *
	 * @return void
	 *
	 * @covers ::removeField
	 * @since __VERSION_NO__
	 */
	public function testRemoveField()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertTrue(
			$form->removeField('title'),
			'Line:' . __LINE__ . ' The removeField method should return true.'
		);

		$this->assertFalse(
			$form->findField('title'),
			'Line:' . __LINE__ . ' The field should be removed.'
		);

		$this->assertTrue(
			$form->removeField('show_title', 'params'),
			'Line:' . __LINE__ . ' The removeField method should return true.'
		);

		$this->assertFalse(
			$form->findField('show_title', 'params'),
			'Line:' . __LINE__ . ' The field should be removed.'
		);
	}

	/**
	 * Test for Form::removeGroup method.
	 *
	 * @return void
	 *
	 * @covers ::removeGroup
	 * @since __VERSION_NO__
	 */
	public function testRemoveGroup()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertTrue(
			$form->removeGroup('params'),
			'Line:' . __LINE__ . ' The removeGroup method should return true.'
		);

		$this->assertEmpty(
			$form->findGroup('params'),
			'Line:' . __LINE__ . ' The group should be removed, returning an empty array.'
		);
	}

	/**
	 * Test for Form::reset method.
	 *
	 * @return void
	 *
	 * @covers ::reset
	 * @since __VERSION_NO__
	 */
	public function testReset()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$data = array(
			'title' => 'Joomla Framework',
			'params' => array(
				'show_title' => 2
			)
		);

		$this->assertTrue(
			$form->bind($data),
			'Line:' . __LINE__ . ' The data should bind successfully.'
		);

		$this->assertThat(
			$form->getValue('title'),
			$this->equalTo('Joomla Framework'),
			'Line:' . __LINE__ . ' Confirm the field value is set.'
		);

		$this->assertThat(
			$form->getValue('show_title', 'params'),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' Confirm the field value is set.'
		);

		// Test reset on the data only.

		$this->assertTrue(
			$form->reset(),
			'Line:' . __LINE__ . ' The reset method should return true.'
		);

		$this->assertNotEquals(
			false,
			$form->getField('title'),
			'Line:' . __LINE__ . ' The field should still exist.'
		);

		$this->assertNull(
			$form->getValue('title'),
			'Line:' . __LINE__ . ' The field value should be reset.'
		);

		$this->assertNull(
			$form->getValue('show_title', 'params'),
			'Line:' . __LINE__ . ' The field value should be reset.'
		);

		// Test reset of data and the internal XML.

		$this->assertTrue(
			$form->reset(true),
			'Line:' . __LINE__ . ' The reset method should return true.'
		);

		$this->assertFalse(
			$form->getField('title'),
			'Line:' . __LINE__ . ' The known field should be removed.'
		);

		$this->assertEmpty(
			$form->findGroup('params'),
			'Line:' . __LINE__ . ' The known group should be removed, returning an empty array.'
		);
	}

	/**
	 * Test for Form::setField method.
	 *
	 * @return void
	 *
	 * @covers ::setField
	 * @since __VERSION_NO__
	 */
	public function testSetField()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$xml1 = simplexml_load_string('<form><field name="title" required="true" /></form>');

		if ($xml1 === false)
		{
			$this->fail('Error in text XML data');
		}

		// Test without replace.

		$this->assertTrue(
			$form->setField($xml1->field[0], null, false),
			'Line:' . __LINE__ . ' The setField method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'required', 'default'),
			$this->equalTo('default'),
			'Line:' . __LINE__ . ' The label should contain just the field name.'
		);

		// Test with replace.

		$this->assertTrue(
			$form->setField($xml1->field[0], null, true),
			'Line:' . __LINE__ . ' The setField method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'required', 'default'),
			$this->equalTo('true'),
			'Line:' . __LINE__ . ' We should now get replaced field.'
		);

		$newField = new \SimpleXMLElement('<field name="newName" required="true"/>');

		$this->assertTrue(
			$form->setField($newField, null, true),
			'Line:' . __LINE__ . ' The setField method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('newName', 'required', 'default'),
			$this->equalTo('true'),
			'Line:' . __LINE__ . ' We should now get replaced field.'
		);

		$newField = new \SimpleXMLElement('<field name="anotherName" required="true"/>');

		$this->assertTrue(
			$form->setField($newField, 'params', true),
			'Line:' . __LINE__ . ' The setField method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('anotherName', 'required', 'default', 'params'),
			$this->equalTo('true'),
			'Line:' . __LINE__ . ' We should now get replaced field.'
		);
	}

	/**
	 * Test for Form::setFieldAttribute method.
	 *
	 * @return void
	 *
	 * @covers ::setFieldAttribute
	 * @since __VERSION_NO__
	 */
	public function testSetFieldAttribute()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertFalse(
			$form->setFieldAttribute('bogus', 'label', 'The Title'),
			'Line:' . __LINE__ . ' The method should return false for non-existent field.'
		);

		$this->assertTrue(
			$form->setFieldAttribute('title', 'label', 'The Title'),
			'Line:' . __LINE__ . ' The method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'label'),
			$this->equalTo('The Title'),
			'Line:' . __LINE__ . ' The new value should be set.'
		);

		$this->assertTrue(
			$form->setFieldAttribute('show_title', 'label', 'Show Title', 'params'),
			'Line:' . __LINE__ . ' The method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('show_title', 'label', 'default', 'params'),
			$this->equalTo('Show Title'),
			'Line:' . __LINE__ . ' The new value of the grouped field should be set.'
		);
	}

	/**
	 * Test for Form::setFields method.
	 *
	 * @return void
	 *
	 * @covers ::setFields
	 * @expectedException UnexpectedValueException
	 * @since __VERSION_NO__
	 */
	public function testSetFieldsInvalidElements()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$elements = array('bogus');

		$this->assertFalse(
			$form->setFields($elements, null, false),
			'Line:' . __LINE__ . ' The setFields method should throw error on invalid element(s).'
		);
	}

	/**
	 * Test for Form::setFields method.
	 *
	 * @return void
	 *
	 * @covers ::setFields
	 * @since __VERSION_NO__
	 */
	public function testSetFields()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$xml1 = simplexml_load_string('<form><field name="title" required="true" /><field name="ordering" /></form>');

		if ($xml1 === false)
		{
			$this->fail('Error in text XML data');
		}

		// Test without replace.

		$this->assertTrue(
			$form->setFields($xml1->field, null, false),
			'Line:' . __LINE__ . ' The setFields method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'required', 'default'),
			$this->equalTo('default'),
			'Line:' . __LINE__ . ' The label should contain just the field name.'
		);

		$this->assertNotEquals(
			false,
			$form->getField('ordering'),
			'Line:' . __LINE__ . ' The label should contain just the field name.'
		);
	}

	/**
	 * Test for Form::setValue method.
	 *
	 * @return void
	 *
	 * @covers ::setValue
	 * @since __VERSION_NO__
	 */
	public function testSetValue()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$loadDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test error handling.

		$this->assertFalse(
			$form->setValue('bogus', null, 'Unknown'),
			'Line:' . __LINE__ . ' An unknown field cannot have its value set.'
		);

		// Test regular usage.

		$this->assertTrue(
			$form->setValue('title', null, 'The Title'),
			'Line:' . __LINE__ . ' Should return true for a known field.'
		);

		$this->assertThat(
			$form->getValue('title', null, 'default'),
			$this->equalTo('The Title'),
			'Line:' . __LINE__ . ' The new value should return.'
		);

		$this->assertTrue(
			$form->setValue('show_title', 'params', '3'),
			'Line:' . __LINE__ . ' Should return true for a known field.'
		);

		$this->assertThat(
			$form->getValue('show_title', 'params', 'default'),
			$this->equalTo('3'),
			'Line:' . __LINE__ . ' The new value should return.'
		);
	}

	/**
	 * Test for Form::syncPaths method.
	 *
	 * @return void
	 *
	 * @covers ::syncPaths
	 * @since __VERSION_NO__
	 */
	public function testSyncPaths()
	{
		$form = new JFormInspector('testSyncPaths');

		$this->assertTrue(
			$form->load(JFormDataHelper::$syncPathsDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$fieldPaths = FormHelper::addFieldPath();
		$formPaths = FormHelper::addFormPath();
		$rulePaths = FormHelper::addRulePath();

		$this->assertContains(
			dirname(__DIR__) . '/field1',
			$fieldPaths,
			'Line:' . __LINE__ . ' The field path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/field2',
			$fieldPaths,
			'Line:' . __LINE__ . ' The field path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/field3',
			$fieldPaths,
			'Line:' . __LINE__ . ' The field path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/form1',
			$formPaths,
			'Line:' . __LINE__ . ' The form path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/form2',
			$formPaths,
			'Line:' . __LINE__ . ' The form path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/form3',
			$formPaths,
			'Line:' . __LINE__ . ' The form path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/rule1',
			$rulePaths,
			'Line:' . __LINE__ . ' The rule path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/rule2',
			$rulePaths,
			'Line:' . __LINE__ . ' The rule path from the XML file should be present.'
		);

		$this->assertContains(
			dirname(__DIR__) . '/rule3',
			$rulePaths,
			'Line:' . __LINE__ . ' The rule path from the XML file should be present.'
		);
	}

	/**
	 * Test for Form::validate method.
	 *
	 * @return void
	 *
	 * @covers ::validate
	 * @since __VERSION_NO__
	 */
	public function testValidate()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$validateDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$pass = array(
			'boolean' => 'false',
			'optional' => 'Optional',
			'required' => 'Supplied',
			'group' => array(
				'level1' => 'open'
			)
		);

		$fail = array(
			'boolean' => 'comply',
			'required' => '',
		);

		// Test error conditions.

		$this->assertFalse(
			$form->validate($pass, 'bogus'),
			'Line:' . __LINE__ . ' Validating an unknown group should return false.'
		);

		$this->assertFalse(
			$form->validate($fail),
			'Line:' . __LINE__ . ' Any validation failures should return false.'
		);

		// Test expected behaviour.

		$this->assertTrue(
			$form->validate($pass),
			'Line:' . __LINE__ . ' Validation on this data should pass.'
		);

		$this->assertTrue(
			$form->validate($pass, 'group'),
			'Line:' . __LINE__ . ' Validating an unknown group should return false.'
		);
	}

	/**
	 * Test for Form::validateField method.
	 *
	 * @return   void
	 *
	 * @covers ::validateField
	 * @since   1.0
	 */
	public function testValidateField()
	{
		$form = new JFormInspector('form1');

		$this->assertTrue(
			$form->load(JFormDataHelper::$validateFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$xml = $form->getXML();

		// Test error handling.

		$field = array_pop($xml->xpath('fields/field[@name="boolean"]'));
		$result = $form->validateField($field);
		$this->assertTrue(
			$result instanceof \UnexpectedValueException,
			'Line:' . __LINE__ . ' A failed validation should return an exception.'
		);

		$field = array_pop($xml->xpath('fields/field[@name="required"]'));
		$result = $form->validateField($field);
		$this->assertTrue(
			$result instanceof \RuntimeException,
			'Line:' . __LINE__ . ' A required field missing a value should return an exception.'
		);

		// Test general usage.

		$field = array_pop($xml->xpath('fields/field[@name="boolean"]'));
		$this->assertTrue(
			$form->validateField($field, null, 'true'),
			'Line:' . __LINE__ . ' A field with a passing validate attribute set should return true.'
		);

		$field = array_pop($xml->xpath('fields/field[@name="optional"]'));
		$this->assertTrue(
			$form->validateField($field),
			'Line:' . __LINE__ . ' A field without required set should return true.'
		);

		$field = array_pop($xml->xpath('fields/field[@name="required"]'));
		$this->assertTrue(
			$form->validateField($field, null, 'value'),
			'Line:' . __LINE__ . ' A required field with a value should return true.'
		);
	}

	/**
	 * Test for Form::validateField method for missing rule exception.
	 *
	 * @return   void
	 *
	 * @covers ::validateField
	 * @expectedException  \UnexpectedValueException
	 * @since   1.0
	 */
	public function testValidateField_missingRule()
	{
		$form = new JFormInspector('form1');
		$form->load(JFormDataHelper::$validateFieldDocument);
		$xml = $form->getXML();

		$field = array_pop($xml->xpath('fields/field[@name="missingrule"]'));
		$result = $form->validateField($field, null, 'value');
	}
}
