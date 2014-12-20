<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests\Field;

use Joomla\Test\TestHelper;
use Joomla\Form\Field\IntegerField;

/**
 * Test class for Joomla\Form\Field\IntegerField.
 *
 * @coversDefaultClass  Joomla\Form\Field\IntegerField
 */
class IntegerFieldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test data for getOptions test
	 *
	 * @return  array
	 */
	public function dataGetOptions()
	{
		return array(
			array("0", "0", "0", null,
					array( ),
				),
			array("0", "0", "1", null,
					array(
						array(
							'value' => '0',
							'text' => '0',
							'disable' => false,
						)
					)
				),
			array("0", "0", "-1", null,
					array(
						array(
							'value' => '0',
							'text' => '0',
							'disable' => false,
						)
					)
				),
			array("0", "2", "0", null,
					array( ),
				),
			array("0", "2", "1", null,
					array(
						array(
							'value' => '0',
							'text' => '0',
							'disable' => false,
						),
						array(
							'value' => '1',
							'text' => '1',
							'disable' => false,
						),
						array(
							'value' => '2',
							'text' => '2',
							'disable' => false,
						)
					)
				),
			array("0", "2", "-1", null,
					array( ),
				),
			array("2", "0", "0", null,
					array( ),
				),
			array("2", "0", "1", null,
					array( ),
				),
			array("2", "0", "-1", null,
					array(
						array(
							'value' => '2',
							'text' => '2',
							'disable' => false,
						),
						array(
							'value' => '1',
							'text' => '1',
							'disable' => false,
						),
						array(
							'value' => '0',
							'text' => '0',
							'disable' => false,
						)
					)
				),
			array("0", "0", "1",
					'<option value="50">50</option>'
					. '<option value="foo">bar</option>',
					array(
						array(
							'value' => '50',
							'text' => '50',
							'disable' => false,
						),
						array(
							'value' => 'foo',
							'text' => 'bar',
							'disable' => false,
						),
						array(
							'value' => '0',
							'text' => '0',
							'disable' => false,
						)
					)
				),
			);
	}

	/**
	 * Test the getOptions method.
	 *
	 * @param   string  $first     @todo
	 * @param   string  $last      @todo
	 * @param   string  $step      @todo
	 * @param   string  $options   @todo
	 * @param   string  $expected  @todo
	 *
	 * @covers ::getOptions
	 * @dataProvider dataGetOptions
	 */
	public function testGetOptions($first, $last, $step, $options, $expected)
	{
		$field = new IntegerField;

		$fieldStartTag = '<field name="myName" type="integer" ';
		$fieldAttr = 'first="' . $first . '" last="' . $last . '" step="' . $step . '"';

		if ($options)
		{
			$fieldEndTag = '>' . $options . '</field>';
		}
		else
		{
			$fieldEndTag = ' />';
		}

		$xml = new \SimpleXmlElement($fieldStartTag . $fieldAttr . $fieldEndTag);
		$this->assertTrue(
			$field->setup($xml, 'value'),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$options = TestHelper::invoke($field, 'getOptions');

		$i = 0;

		if (empty($expected))
		{
			$this->assertThat(
				empty($options),
				$this->isTrue(),
				'Line:' . __LINE__ . ' The getOption method should compute range correctly.'
			);
		}
		else
		{
			foreach ($expected as $expectedOption)
			{
				foreach ($expectedOption as $attr => $value)
				{
					$this->assertEquals(
						$options[$i]->$attr,
						$value,
						'Line:' . __LINE__ . ' The getOption method should compute range correctly.'
					);
				}

				++$i;
			}
		}
	}
}
