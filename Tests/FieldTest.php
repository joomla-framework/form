<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Form;
use Joomla\Form\FormHelper;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field.
 *
 * @coversDefaultClass  Joomla\Form\Field
 */
class FieldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Text object for injection
	 *
	 * @var  Text
	 */
	private $text;

	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass()
	{
		/** @var \Composer\Autoload\ClassLoader $loader */
		$loader = include dirname(__DIR__) . '/vendor/autoload.php';

		// Add our test fields to the autoload paths for testing
		$loader->addPsr4('Foo\\Form\\Field\\', __DIR__ . '/_testfields');
	}

	/**
	 * Set up for testing
	 */
	public function setUp()
	{
		parent::setUp();

		include_once 'inspectors.php';

		// Prepare a Text object to be injected into test objects
		$this->text = new Text(new Language(__DIR__, 'en-GB'));
	}

	/**
	 * Tests the Joomla\Form\Field::__construct method
	 *
	 * @covers  ::__construct
	 */
	public function testConstruct()
	{
		$form = new Form('form1');

		$this->assertTrue(
			$form->load(DataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new JFormFieldInspector($form);

		$this->assertTrue(
			$field instanceof \Joomla\Form\Field,
			'Line:' . __LINE__ . ' The \\Joomla\\Form\\Field constuctor should return a \\Joomla\\Form\\Field object.'
		);

		$this->assertThat(
			$field->getForm(),
			$this->identicalTo($form),
			'Line:' . __LINE__ . ' The internal form should be identical to the variable passed in the contructor.'
		);

		$class = FormHelper::loadFieldClass('foo.bar');

		/** @var \Foo\Form\Field\BarField $field */
		$field = new $class($form);
		$this->assertEquals(
			$field->type,
			'Foo\Field\BarField',
			'Line:' . __LINE__ . ' The field type should have been guessed by the constructor.'
		);

		$field = new \Joomla\Form\Field\TextField;
		$this->assertEquals(
			$field->type,
			'Text',
			'Line:' . __LINE__ . ' The field type should have been guessed by the constructor.'
		);

		$this->assertNull(
			$field->formControl,
			'Line:' . __LINE__ . ' The internal form should be identical to the variable passed in the contructor.'
		);

		$this->assertNull(
			TestHelper::getValue($field, 'form'),
			'Line:' . __LINE__ . ' The internal form should be identical to the variable passed in the contructor.'
		);

		$class = FormHelper::loadFieldClass('foo.modal\\bar');

		/** @var \Foo\Form\Field\Modal\BarField $field */
		$field = new $class($form);
		$this->assertEquals(
			$field->type,
			'Foo\Field\Modal\BarField',
			'Line:' . __LINE__ . ' The field type should have been guessed by the constructor.'
		);
	}

	/**
	 * Tests the Joomla\Form\Field::GetId method
	 *
	 * @covers  ::getId
	 */
	public function testGetId()
	{
		$form = new Form('form1', array('control' => 'jform'));

		$this->assertTrue(
			$form->load(DataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new JFormFieldInspector($form);

		// Standard usage.

		$xml = TestHelper::getvalue($form, 'xml');
		$colours = array_pop($xml->xpath('fields/fields[@name="params"]/field[@name="colours"]'));

		$this->assertTrue(
			$field->setup($colours, 'red', 'params'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'jform_params_colours',
			// Use original 'id' and 'name' here (from XML definition of the form field)
			$field->getId((string) $colours['id'], (string) $colours['name']),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$form = new Form('form1');

		$this->assertTrue(
			$form->load(DataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new JFormFieldInspector($form);

		$xml = TestHelper::getvalue($form, 'xml');
		$date = array_pop($xml->xpath('fields/field[@name="created_date"]'));

		// No form control with group
		$this->assertTrue(
			$field->setup($colours, 'red', 'params'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'params_colours',
			// Use original 'id' and 'name' here (from XML definition of the form field)
			$field->getId((string) $colours['id'], (string) $colours['name']),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		// No form control with no group
		$this->assertTrue(
			$field->setup($date, '01-01-1990'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'created_date',
			// Use original 'id' and 'name' here (from XML definition of the form field)
			$field->getId((string) $date['id'], (string) $date['name']),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);
	}

	/**
	 * Tests the Joomla\Form\Field::getLabel method
	 *
	 * @covers  ::getLabel
	 */
	public function testGetLabel()
	{
		$form = new Form('form1');
		$form->setText($this->text);

		$this->assertTrue(
			$form->load(DataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new JFormFieldInspector($form);
		$field->setText($this->text);

		// Standard usage.

		$xml = TestHelper::getvalue($form, 'xml');
		$title = array_pop($xml->xpath('fields/field[@name="title"]'));

		$this->assertTrue(
			$field->setup($title, 'The title'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$equals = '<label id="title_id-lbl" for="title_id" class="hasTip required" ' .
			'title="Title::The title.">Title</label>';

		$this->assertEquals(
			$equals,
			$field->getLabel(),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		// Not required

		$colours = array_pop($xml->xpath('fields/fields[@name="params"]/field[@name="colours"]'));

		$this->assertTrue(
			$field->setup($colours, 'id'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'<label id="colours-lbl" for="colours" class="">colours</label>',
			$field->getLabel(),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		// Hidden field

		$id = array_pop($xml->xpath('fields/field[@name="id"]'));

		$this->assertTrue(
			$field->setup($id, 'id'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'',
			$field->getLabel(),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);
	}

	/**
	 * Tests the Joomla\Form\Field::getTitle method
	 *
	 * @covers  ::getTitle
	 */
	public function testGetTitle()
	{
		$form = new Form('form1');
		$form->setText($this->text);

		$this->assertTrue(
			$form->load(DataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new JFormFieldInspector($form);
		$field->setText($this->text);

		// Standard usage.

		$xml = TestHelper::getvalue($form, 'xml');
		$title = array_pop($xml->xpath('fields/field[@name="title"]'));

		$this->assertTrue(
			$field->setup($title, 'The title'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'Title',
			$field->getTitle(),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		// Hidden field

		$id = array_pop($xml->xpath('fields/field[@name="id"]'));

		$this->assertTrue(
			$field->setup($id, 'id'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'',
			$field->getTitle(),
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);
	}

	/**
	 * Tests the Joomla\Form\Field::setForm method
	 *
	 * @covers  ::setForm
	 */
	public function testSetForm()
	{
		$form1 = new Form('form1');
		$form2 = new Form('form2');

		$field = new JFormFieldInspector($form1);
		$field->setForm($form2);

		$this->assertThat(
			$field->getForm(),
			$this->identicalTo($form2),
			'Line:' . __LINE__ . ' The internal form should be identical to the last set.'
		);
	}

	/**
	 * Test an invalid argument for the Joomla\Form\Field::setup method
	 *
	 * @covers             ::setup
	 * @expectedException  \PHPUnit_Framework_Error
	 */
	public function testSetupInvalidArgument()
	{
		$form = new Form('form1');
		$field = new JFormFieldInspector($form);

		$this->assertFalse(
			$field->setup('wrong', 0),
			'Line:' . __LINE__ . ' If not a form object, setup should return false.'
		);
	}

	/**
	 * Test an invalid element for the Joomla\Form\Field::setup method
	 *
	 * @covers  ::setup
	 */
	public function testSetupInvalidElement()
	{
		$form = new Form('form1');
		$field = new JFormFieldInspector($form);

		$wrong = new \SimpleXmlElement('<form></form>');
		$this->assertFalse(
			$field->setup($wrong, 0),
			'Line:' . __LINE__ . ' If not a field object, setup should return false.'
		);
	}

	/**
	 * Tests the Joomla\Form\Field::setup method
	 *
	 * @covers  ::__get
	 * @covers  ::setup
	 */
	public function testSetup()
	{
		$form = new Form('form1');

		$this->assertTrue(
			$form->load(DataHelper::$loadFieldDocument),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new JFormFieldInspector($form);
		$field->setText($this->text);

		// Standard usage.

		$xml = TestHelper::getvalue($form, 'xml');
		$title = array_pop($xml->xpath('fields/field[@name="title"]'));

		$this->assertTrue(
			$field->setup($title, 'The title'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'title',
			$field->name,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertEquals(
			'The title',
			$field->value,
			'Line:' . __LINE__ . ' The value should be set from the setup method argument.'
		);

		$this->assertEquals(
			'title_id',
			$field->id,
			'Line:' . __LINE__ . ' The property should be set from the XML (non-alpha transposed to underscore).'
		);

		$this->assertEquals(
			'inputbox',
			(string) $title['class'],
			'Line:' . __LINE__ . ' The property should be set from the XML.'
		);

		$this->assertequals(
			'none',
			$field->validate,
			'Line:' . __LINE__ . ' The property should be set from the XML.'
		);

		$this->assertFalse(
			$field->multiple,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertTrue(
			$field->required,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertEquals(
			'',
			$field->input,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$equals = '<label id="title_id-lbl" for="title_id" class="hasTip required" title="Title::The title.">Title</label>';

		$this->assertEquals(
			$equals,
			$field->label,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertEquals(
			'Title',
			$field->title,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertNull(
			$field->unexisting,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		// Test multiple attribute and form group name.

		$colours = array_pop($xml->xpath('fields/fields[@name="params"]/field[@name="colours"]'));

		$this->assertTrue(
			$field->setup($colours, 'green', 'params'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'params_colours',
			$field->id,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertEquals(
			'params[colours][]',
			$field->name,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertTrue(
			$field->multiple,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertEquals(
			'params',
			$field->group,
			'Line:' . __LINE__ . ' The property should be set to the the group name.'
		);

		// Test hidden field type.

		$id = array_pop($xml->xpath('fields/field[@name="id"]'));

		$this->assertTrue(
			$field->setup($id, 42),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertTrue(
			$field->hidden,
			'Line:' . __LINE__ . ' The hidden property should be set from the field type.'
		);

		// Test hidden attribute.

		$createdDate = array_pop($xml->xpath('fields/field[@name="created_date"]'));

		$this->assertTrue(
			$field->setup($createdDate, '0000-00-00 00:00:00'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertTrue(
			$field->hidden,
			'Line:' . __LINE__ . ' The hidden property should be set from the hidden attribute.'
		);

		// Test automatic generated name.

		$spacer = array_pop($xml->xpath('fields/field[@type="spacer"]'));

		$this->assertTrue(
			$field->setup($spacer, ''),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'__field1',
			$field->name,
			'Line:' . __LINE__ . ' The spacer name should be set using an automatic generated name.'
		);

		// Test nested groups and forced multiple.

		$comment = array_pop($xml->xpath('fields/fields[@name="params"]/fields[@name="subparams"]/field[@name="comment"]'));
		$field->forceMultiple = true;

		$this->assertTrue(
			$field->setup($comment, 'My comment', 'params.subparams'),
			'Line:' . __LINE__ . ' The setup method should return true if successful.'
		);

		$this->assertEquals(
			'params_subparams_comment',
			$field->id,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertEquals(
			'params[subparams][comment][]',
			$field->name,
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);

		$this->assertEquals(
			'params.subparams',
			$field->group,
			'Line:' . __LINE__ . ' The property should be set to the the group name.'
		);

		$this->assertEquals(
			'',
			$field->element['class'],
			'Line:' . __LINE__ . ' The property should be computed from the XML.'
		);
	}

	/**
	 * Tests the Joomla\Form\Field::getName method
	 *
	 * @covers  ::getName
	 */
	public function testGetName()
	{
		$form = new Form('form1');

		$field = new JFormFieldInspector($form);

		$this->assertEquals(
			'foo',
			TestHelper::invoke($field, 'getName', 'foo'),
			'Line:' . __LINE__ . ' getName should return generated name correctly.'
		);

		TestHelper::setValue($field, 'multiple', true);
		$this->assertEquals(
			'foo[]',
			TestHelper::invoke($field, 'getName', 'foo'),
			'Line:' . __LINE__ . ' getName should return generated name correctly.'
		);
		TestHelper::setValue($field, 'multiple', false);

		TestHelper::setValue($field, 'group', 'myGroup');
		$this->assertEquals(
			'myGroup[foo]',
			TestHelper::invoke($field, 'getName', 'foo'),
			'Line:' . __LINE__ . ' getName should return generated name correctly.'
		);

		TestHelper::setValue($field, 'group', 'myGroup.one.two');
		$this->assertEquals(
			'myGroup[one][two][foo]',
			TestHelper::invoke($field, 'getName', 'foo'),
			'Line:' . __LINE__ . ' getName should return generated name correctly.'
		);

		TestHelper::setValue($field, 'group', '');
		TestHelper::setValue($field, 'formControl', 'bar');
		$this->assertEquals(
			'bar[foo]',
			TestHelper::invoke($field, 'getName', 'foo'),
			'Line:' . __LINE__ . ' getName should return generated name correctly.'
		);

		TestHelper::setValue($field, 'group', 'myGroup');
		$this->assertEquals(
			'bar[myGroup][foo]',
			TestHelper::invoke($field, 'getName', 'foo'),
			'Line:' . __LINE__ . ' getName should return generated name correctly.'
		);
	}

	/**
	 * Tests the Joomla\Form\Field::getFieldName method
	 *
	 * @covers  ::getFieldName
	 */
	public function testGetFieldName()
	{
		$form = new Form('form1');

		$field = new JFormFieldInspector($form);

		TestHelper::setValue($field, 'count', 0);
		$this->assertEquals(
			'__field1',
			TestHelper::invoke($field, 'getFieldName', ''),
			'Line:' . __LINE__ . ' getFieldname should return generated field name using count correctly.'
		);

		$this->assertEquals(
			1,
			TestHelper::getValue($field, 'count'),
			'Line:' . __LINE__ . ' getFieldname should increment counter.'
		);

		$this->assertEquals(
			'foo',
			TestHelper::invoke($field, 'getFieldName', 'foo'),
			'Line:' . __LINE__ . ' getFieldname should return generated field name using count correctly.'
		);

		$this->assertEquals(
			1,
			TestHelper::getValue($field, 'count'),
			'Line:' . __LINE__ . ' getFieldname should not increment counter if fieldname is given.'
		);
	}
}
