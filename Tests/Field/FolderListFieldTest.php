<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Form\Field\FolderListField;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Form\Field\FolderListField.
 *
 * @coversDefaultClass  Joomla\Form\Field\FolderListField
 */
class FolderListFieldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Text object for injection
	 *
	 * @var  Text
	 */
	private $text;

	/**
	 * Set up for testing
	 */
	public function setUp()
	{
		parent::setUp();

		// Prepare a Text object to be injected into test objects
		$this->text = new Text(new Language(dirname(__DIR__), 'en-GB'));
	}

	/**
	 * Test data for getGroups test
	 *
	 * @return  array
	 */
	public function dataGetOptions()
	{
		return array(
			array(
				array(),
				array(
					(object) array('value' => '-1', 'text' => 'JOPTION_DO_NOT_USE', 'disable' => false),
					(object) array('value' => '', 'text' => 'JOPTION_USE_DEFAULT', 'disable' => false),
					(object) array('value' => 'data', 'text' => 'data', 'disable' => false),
					(object) array('value' => 'testfiles', 'text' => 'testfiles', 'disable' => false),
				)
			),
			array(
				array('hide_none' => 'true'),
				array(
					(object) array('value' => '', 'text' => 'JOPTION_USE_DEFAULT', 'disable' => false),
					(object) array('value' => 'data', 'text' => 'data', 'disable' => false),
					(object) array('value' => 'testfiles', 'text' => 'testfiles', 'disable' => false),
				)
			),
			array(
				array('hide_default' => 'true'),
				array(
					(object) array('value' => '-1', 'text' => 'JOPTION_DO_NOT_USE', 'disable' => false),
					(object) array('value' => 'data', 'text' => 'data', 'disable' => false),
					(object) array('value' => 'testfiles', 'text' => 'testfiles', 'disable' => false),
				)
			),
			array(
				array(
					'hide_default' => 'true',
					'hide_none' => 'true',
					'exclude' => 'foobar'
				),
				array(
					(object) array('value' => 'data', 'text' => 'data', 'disable' => false),
					(object) array('value' => 'testfiles', 'text' => 'testfiles', 'disable' => false),
				)
			),
			array(
				array('exclude' => 'data'),
				array(
					(object) array('value' => '-1', 'text' => 'JOPTION_DO_NOT_USE', 'disable' => false),
					(object) array('value' => '', 'text' => 'JOPTION_USE_DEFAULT', 'disable' => false),
					(object) array('value' => 'testfiles', 'text' => 'testfiles', 'disable' => false),
				)
			),
		);
	}

	/**
	 * Test the getInput method.
	 *
	 * @param   array  $inputs    Inputs to set the state
	 * @param   array  $expected  Expected folder list
	 *
	 * @covers        ::getOptions
	 * @dataProvider  dataGetOptions
	 */
	public function testGetOptions($inputs, $expected)
	{
		$xml = '<field name="folderlist" type="folderlist"';
		$inputs['directory'] = __DIR__;

		foreach ($inputs as $attr => $value)
		{
			$xml .= " $attr=\"$value\"";
		}

		$xml .= ' />';

		$field = new FolderListField;
		$field->setText($this->text);

		$xml = new \SimpleXmlElement($xml);

		$this->assertTrue(
			$field->setup($xml, 'setupValue'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$options = TestHelper::invoke($field, 'getOptions');

		$this->assertEquals($expected, $options);
	}
}
