<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Html\Select;
use SimpleXMLElement;

/**
 * Test class for JFormFieldTel.
 *
 * @coversDefaultClass Joomla\Form\Html\Select
 * @since  1.0
 */
class SelectTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Generic list dataset
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function dataGenericlist()
	{
		return array(
			// Function parameters array($expected, $data, $name, $attribs = null, $optKey = 'value', $optText = 'text',
			// 						$selected = null, $idtag = false, $translate = false)
			array(
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myName',
						'name' => 'myName'
					),
					'child' => array(
						'tag' => 'option',
						'attributes' => array(
							'value' => '1'
						),
						'content' => 'Foo'
					)
				),
				array(
					array(
						'value' => '1',
						'text' => 'Foo',
					),
					array(
						'value' => '2',
						'text' => 'Bar',
					),
				),
				'myName',
			),
			array(
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => 'myName'
					),
					'child' => array(
						'tag' => 'option',
						'attributes' => array(
							'value' => '2',
							'selected' => 'selected'
						),
						'content' => 'Bar'
					)
				),
				array(
					array(
						'value' => '1',
						'text' => 'Foo',
					),
					array(
						'value' => '2',
						'text' => 'Bar',
					),
				),
				'myName',
				null,
				'value',
				'text',
				'2',
				'myId',
			),
			array(
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => 'myName'
					),
					'child' => array(
						'tag' => 'option',
						'attributes' => array(
							'value' => '2',
							'selected' => 'selected'
						),
						'content' => 'Bar'
					)
				),
				array(
					array(
						'value' => '1',
						'text' => 'Foo',
					),
					array(
						'value' => '2',
						'text' => 'Bar',
					),
				),
				'myName',
				array(
					'id' => 'myId',
					'list.select' => '2',
				),
			),
			array(
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myName',
						'name' => 'myName',
						'class' => 'foobar',
						'onchange' => 'barfoo();'
					)
				),
				array(
					array(
						'value' => '1',
						'text' => 'Foo',
					),
					array(
						'value' => '2',
						'text' => 'Bar',
					),
				),
				'myName',
				'class="foobar" onchange="barfoo();"',
			),
		);
	}

	/**
	 * Test the genericlist method.
	 *
	 * @param   string   $expected   Expected generated HTML <select> tag.
	 * @param   array    $data       An array of objects, arrays, or scalars.
	 * @param   string   $name       The value of the HTML name attribute.
	 * @param   mixed    $attribs    Additional HTML attributes for the <select> tag. This
	 *                               can be an array of attributes, or an array of options. Treated as options
	 *                               if it is the last argument passed. Valid options are:
	 *                               Format options, see {@see JHtml::$formatOptions}.
	 *                               Selection options, see {@see JHtmlSelect::options()}.
	 *                               list.attr, string|array: Additional attributes for the select
	 *                               element.
	 *                               id, string: Value to use as the select element id attribute.
	 *                               Defaults to the same as the name.
	 *                               list.select, string|array: Identifies one or more option elements
	 *                               to be selected, based on the option key values.
	 * @param   string   $optKey     The name of the object variable for the option value. If
	 *                               set to null, the index of the value array is used.
	 * @param   string   $optText    The name of the object variable for the option text.
	 * @param   mixed    $selected   The key that is selected (accepts an array or a string).
	 * @param   mixed    $idtag      Value of the field id or null by default
	 * @param   boolean  $translate  True to translate
	 *
	 * @return  void
	 *
	 * @covers        ::genericList
	 * @dataProvider  dataGenericList
	 * @since         3.2
	 */
	public function testGenericlist($expected, $data, $name, $attribs = null,
		$optKey = 'value', $optText = 'text', $selected = null, $idtag = false,
		$translate = false)
	{
		if (func_num_args() == 4)
		{
			$this->assertTag(
				$expected,
				Select::genericlist($data, $name, $attribs)
			);
		}
		else
		{
			$this->assertTag(
				$expected,
				Select::genericlist($data, $name, $attribs, $optKey, $optText, $selected, $idtag, $translate)
			);
		}
	}

	/**
	 * Grouped list dataset
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function dataGroupedlist()
	{
		return array(
			array(
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myName',
						'name' => 'myName',
						'class' => 'aClass'
					),
					'child' => array(
						'tag' => 'option',
						'attributes' => array(
							'value' => 'foo',
						),
						'content' => 'Foo'
					)
				),
				array(
					0 => array(
						(object) array(
							'value' => 'foo',
							'text' => 'Foo',
							'disable' => false,
							'class' => '',
							'onclick' => ''
						),
					),
				),
				'myName',
				array('group.items' => null, 'list.attr' => 'class="aClass"')
			),
			array(
				array(
					'tag' => 'select',
					'attributes' => array(
						'id' => 'myId',
						'name' => 'myName',
						'class' => 'aClass'
					),
					'child' => array(
						'tag' => 'optgroup',
						'attributes' => array(
							'label' => 'barfoo'
						)
					)
				),
				array(
					'barfoo' => array(
						(object) array(
							'value' => 'oof',
							'text' => 'Foo',
							'disable' => false,
							'class' => '',
							'onclick' => ''
						),
					),
				),
				'myName',
				array(
					'group.items' => null,
					'id' => 'myId',
					'list.attr' => array('class' => "aClass")
				)
			),
		);
	}

	/**
	 * Generates a grouped HTML selection list from nested arrays.
	 *
	 * @param   string  $expected  Expected generated HTML <select> string.
	 * @param   array   $data      An array of groups, each of which is an array of options.
	 * @param   string  $name      The value of the HTML name attribute
	 * @param   array   $options   Options, an array of key/value pairs. Valid options are:
	 *                             Format options, {@see Select::$formatOptions}.
	 *                             Selection options. See {@see Select::options()}.
	 *                             group.id: The property in each group to use as the group id
	 *                             attribute. Defaults to none.
	 *                             group.label: The property in each group to use as the group
	 *                             label. Defaults to "text". If set to null, the data array index key is
	 *                             used.
	 *                             group.items: The property in each group to use as the array of
	 *                             items in the group. Defaults to "items". If set to null, group.id and
	 *                             group. label are forced to null and the data element is assumed to be a
	 *                             list of selections.
	 *                             id: Value to use as the select element id attribute. Defaults to
	 *                             the same as the name.
	 *                             list.attr: Attributes for the select element. Can be a string or
	 *                             an array of key/value pairs. Defaults to none.
	 *                             list.select: either the value of one selected option or an array
	 *                             of selected options. Default: none.
	 *                             list.translate: Boolean. If set, text and labels are translated via
	 *                             Text::_().
	 *
	 * @return  string  HTML for the select list
	 *
	 * @covers        ::groupedlist
	 * @dataProvider  dataGroupedlist
	 * @since         3.2
	 */
	public function testGroupedlist($expected, $data, $name, $options = array())
	{
		if (func_num_args() == 3)
		{
			$this->assertTag(
				$expected,
				Select::groupedlist($data, $name)
			);
		}
		else
		{
			$this->assertTag(
				$expected,
				Select::groupedlist($data, $name, $options)
			);
		}
	}

	/**
	 * Radio list dataset
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function dataRadiolist()
	{
		return array(
			// Function parameters array($expected, $data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false,
			// 						$translate = false)
			array(
				array(
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myRadioListName',
							'id' => 'yesId',
							'value' => '1'
						)
					),
					array(
						'tag' => 'label',
						'content' => 'Yes',
						'attributes' => array(
							'for' => 'yesId',
							'class' => 'radiobtn',
							'id' => 'yesId-lbl',
						)
					),
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myRadioListName',
							'id' => 'myRadioListName0',
							'value' => '0'
						)
					),
					array(
						'tag' => 'label',
						'content' => 'No',
						'attributes' => array(
							'for' => 'myRadioListName0',
							'class' => 'radiobtn',
							'id' => 'myRadioListName0-lbl',
						)
					),
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myRadioListName',
							'id' => 'myRadioListName-1',
							'value' => '-1'
						)
					),
					array(
						'tag' => 'label',
						'content' => 'Maybe',
						'attributes' => array(
							'for' => 'myRadioListName-1',
							'class' => 'radiobtn',
							'id' => 'myRadioListName-1-lbl',
						)
					),
				),
				array(
					array(
						'value' => '1',
						'text' => 'Yes',
						'id' => "yesId",
					),
					array(
						'value' => '0',
						'text' => 'No',
					),
					array(
						'value' => '-1',
						'text' => 'Maybe',
					),
				),
				"myRadioListName"
			),
			array(
				array(
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myFooBarListName',
							'id' => 'fooId',
							'value' => 'foo',
							'class' => 'i am radio',
							'onchange' => 'jsfunc();'
						)
					),
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myFooBarListName',
							'id' => 'myFooBarListNamebar',
							'value' => 'bar',
							'class' => 'i am radio',
							'onchange' => 'jsfunc();',
							'checked' => 'checked',
						)
					),
				),
				array(
					array(
						'key' => 'foo',
						'val' => 'FOO',
						'id' => "fooId",
					),
					array(
						'key' => 'bar',
						'val' => 'BAR',
					),
				),
				"myFooBarListName",
				array(
					'class' => 'i am radio',
					'onchange' => 'jsfunc();',
				),
				'key',
				'val',
				array('one', 'bar'),
			),
		);
	}

	/**
	 * Test the radiolist method.
	 *
	 * @param   string   $expected   Expected generated HTML of radio list.
	 * @param   array    $data       An array of objects
	 * @param   string   $name       The value of the HTML name attribute
	 * @param   string   $attribs    Additional HTML attributes for the <select> tag
	 * @param   mixed    $optKey     The key that is selected
	 * @param   string   $optText    The name of the object variable for the option value
	 * @param   string   $selected   The name of the object variable for the option text
	 * @param   boolean  $idtag      Value of the field id or null by default
	 * @param   boolean  $translate  True if options will be translated
	 *
	 * @return  void
	 *
	 * @covers ::radioList
	 * @dataProvider  dataRadiolist
	 * @since         3.2
	 */
	public function testRadiolist($expected, $data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false,
		$translate = false)
	{
		foreach ($data as $arr)
		{
			$dataObject[] = (object) $arr;
		}

		$data = $dataObject;

		if (func_num_args() == 4)
		{
			$html = Select::radiolist((object) $data, $name, $attribs);
		}
		else
		{
			$html = Select::radiolist((object) $data, $name, $attribs, $optKey, $optText, $selected, $idtag, $translate);
		}

		foreach ($expected as $tag)
		{
			$this->assertTag($tag, $html);
		}
	}

	/**
	 * Radio list dataset
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function dataBooleanlist()
	{
		return array(
			// Function parameters array(
			// $expected, $name, $attribs = null, $selected = null, yes = 'JYES', $no = 'JNO', $id = false
			array(
				array(
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myRadioListName',
							'id' => 'myRadioListName1',
							'value' => '1'
						)
					),
					array(
						'tag' => 'label',
						'content' => 'JYES',
						'attributes' => array(
							'for' => 'myRadioListName1',
							'class' => 'radiobtn',
							'id' => 'myRadioListName1-lbl',
						)
					),
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myRadioListName',
							'id' => 'myRadioListName0',
							'value' => '0',
							'checked' => 'checked'
						)
					),
					array(
						'tag' => 'label',
						'content' => 'JNO',
						'attributes' => array(
							'for' => 'myRadioListName0',
							'class' => 'radiobtn',
							'id' => 'myRadioListName0-lbl',
						)
					),
				),
				"myRadioListName"
			),
			array(
				array(
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myFooBarListName',
							'id' => 'myId0',
							'value' => '0',
							'class' => 'i am radio',
							'onchange' => 'jsfunc();'
						)
					),
					array(
						'tag' => 'input',
						'attributes' => array(
							'type' => 'radio',
							'name' => 'myFooBarListName',
							'id' => 'myId1',
							'value' => '1',
							'class' => 'i am radio',
							'onchange' => 'jsfunc();',
							'checked' => 'checked'
						)
					),
				),
				"myFooBarListName",
				array(
					'class' => 'i am radio',
					'onchange' => 'jsfunc();',
				),
				'1',
				'foo',
				'bar',
				'myId'
			),
		);
	}

	/**
	 * Test the radiolist method.
	 *
	 * @param   string  $expected  Expected generated HTML of radio list.
	 * @param   string  $name      The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 * @param   string  $selected  The key that is selected
	 * @param   string  $yes       Language key for Yes
	 * @param   string  $no        Language key for no
	 * @param   string  $id        The id for the field
	 *
	 * @return  void
	 *
	 * @covers ::booleanList
	 * @dataProvider  dataBooleanlist
	 * @since         3.2
	 */
	public function testBooleanlist($expected, $name, $attribs = null, $selected = null, $yes = 'JYES', $no = 'JNO', $id = false)
	{
		if (func_num_args() == 3)
		{
			$html = Select::booleanlist($name, $attribs);
		}
		else
		{
			$html = Select::booleanlist($name, $attribs, $selected, $yes, $no, $id);
		}

		foreach ($expected as $tag)
		{
			$this->assertTag($tag, $html);
		}
	}

	/**
	 * Radio list dataset
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function dataIntegerlist()
	{
		return array(
			// Function parameters array(
			// $start, $end, $inc, $name, $attribs = null, $selected = null, $format = ''
			array(
				array(),
				0, 0, 0,
				'myName'
			),
			array(
				array(),
				1, 0, 1,
				'myName'
			),
			array(
				array(),
				0, 1, -1,
				'myName'
			),
			array(
				array(
					array(
						'tag' => 'select',
						'attributes' => array(
							'name' => 'myName',
						),
						'children' => array(
							'count' => 1
						)
					),
				),
				0, 0, 1,
				'myName'
			),
			array(
				array(
					array(
						'tag' => 'select',
						'attributes' => array(
							'name' => 'myName',
						),
						'children' => array(
							'count' => 6
						)
					),
				),
				0, 5, 1,
				'myName'
			),
			array(
				array(
					array(
						'tag' => 'select',
						'attributes' => array(
							'name' => 'myName',
							'class' => 'i am radio',
							'onchange' => 'jsfunc();',
						),
						'children' => array(
							'count' => 6
						)
					),
				),
				5, 0, -1,
				'myName',
				'class="i am radio" onchange="jsfunc();"',
			),
			array(
				array(
					array(
						'tag' => 'select',
						'attributes' => array(
							'name' => 'myName',
							'class' => 'i am radio',
							'onchange' => 'jsfunc();',
						),
						'children' => array(
							'count' => 6
						)
					),
				),
				5, 0, -1,
				'myName',
				array(
					'list.attr' => 'class="i am radio" onchange="jsfunc();"',
				),
			),
		);
	}

	/**
	 * Test the radiolist method.
	 *
	 * @param   string   $expected  Expected generated HTML of radio list.
	 * @param   integer  $start     The start integer
	 * @param   integer  $end       The end integer
	 * @param   integer  $inc       The increment
	 * @param   string   $name      The value of the HTML name attribute
	 * @param   mixed    $attribs   Additional HTML attributes for the <select> tag, an array of
	 *                              attributes, or an array of options. Treated as options if it is the last
	 *                              argument passed.
	 * @param   mixed    $selected  The key that is selected
	 * @param   string   $format    The printf format to be applied to the number
	 *
	 * @return  void
	 *
	 * @covers ::integerList
	 * @dataProvider  dataIntegerlist
	 * @since         3.2
	 */
	public function testIntegerlist($expected, $start, $end, $inc, $name, $attribs = null, $selected = null, $format = '')
	{
		if (func_num_args() == 6)
		{
			$html = Select::integerlist($start, $end, $inc, $name, $attribs);
		}
		else
		{
			$html = Select::integerlist($start, $end, $inc, $name, $attribs, $selected, $format);
		}

		foreach ($expected as $tag)
		{
			$this->assertTag($tag, $html);
		}
	}

	/**
	 * Test...
	 *
	 * @return  array
	 *
	 * @since   3.1
	 */
	public function dataOptions()
	{
		return array(
			// Function parameters array($expected, $arr, $optKey = 'value', $optText = 'text', $selected = null, $translate = false)
			array(
				"<option value=\"1\" selected=\"selected\">Test</option>\n" .
				"<option value=\"2\">Bar</option>\n" .
				"<option value=\"3\" selected=\"selected\">Foo</option>\n",
				array(
					'1' => 'Test',
					'2' => 'Bar',
					'3' => 'Foo',
				),
				array(
					'list.select' => array('1', '3')
				)
			),
			array(
				"<option value=\"1\" selected=\"selected\">&nbsp;Test</option>\n",
				array(
					array(
						'value' => '1',
						'text' => '&nbsp;Test',
					),
				),
				array(
					'list.select' => '1'
				)
			),
			array(
				"<option value=\"1\">&nbsp;Test</option>\n",
				array(
					(object) array(
						'value' => '1',
						'text' => '&nbsp;Test',
					),
				),
			),
			array(
				"<option value=\"1\" disabled=\"disabled\">&nbsp;Test - foo</option>\n",
				array(
					array(
						'value' => '1',
						'text' => '&nbsp;Test - foo',
						'disable' => true,
					),
				),
			),
			array(
				"<option value=\"1\">-&nbsp;Test -</option>\n",
				array(
					array(
						'optionValue' => '1',
						'optionText' => '-&nbsp;Test -',
						'list.translate' => true,
					),
				),
				array(
					'option.key' => 'optionValue',
					'option.text' => 'optionText'
				),
			),
			array(
				"<option value=\"1\" id=\"myId\" label=\"My Label\" readonly>&nbsp;Test</option>\n",
				array(
					array(
						'value' => '1',
						'text' => '&nbsp;Test -         ',
						'label' => 'My Label',
						'id' => 'myId',
						'extraAttrib' => 'readonly',
					),
				),
				array(
					'option.label' => 'label',
					'option.id' => 'id',
					'option.attr' => 'extraAttrib',
				),
			),
			array(
				"<option value=\"1\" class=\"foo bar\" style=\"color:red;\">&nbsp;Test</option>\n",
				array(
					array(
						'value' => '1',
						'text' => '&nbsp;Test -         ',
						'label' => 'My Label',
						'id' => 'myId',
						'attrs' => array('class' => "foo bar",'style' => 'color:red;',),
					),
				),
				array(
					'option.attr' => 'attrs',
				),
			),
		);
	}

	/**
	 * Test the options method.
	 *
	 * @param   string   $expected   Expected generated HTML <option> list.
	 * @param   array    $arr        An array of objects, arrays, or values.
	 * @param   mixed    $optKey     If a string, this is the name of the object variable for
	 *                               the option value. If null, the index of the array of objects is used. If
	 *                               an array, this is a set of options, as key/value pairs. Valid options are:
	 *                               -Format options, {@see JHtml::$formatOptions}.
	 *                               -groups: Boolean. If set, looks for keys with the value
	 *                                "&lt;optgroup>" and synthesizes groups from them. Deprecated. Defaults
	 *                                true for backwards compatibility.
	 *                               -list.select: either the value of one selected option or an array
	 *                                of selected options. Default: none.
	 *                               -list.translate: Boolean. If set, text and labels are translated via
	 *                                JText::_(). Default is false.
	 *                               -option.id: The property in each option array to use as the
	 *                                selection id attribute. Defaults to none.
	 *                               -option.key: The property in each option array to use as the
	 *                                selection value. Defaults to "value". If set to null, the index of the
	 *                                option array is used.
	 *                               -option.label: The property in each option array to use as the
	 *                                selection label attribute. Defaults to null (none).
	 *                               -option.text: The property in each option array to use as the
	 *                               displayed text. Defaults to "text". If set to null, the option array is
	 *                               assumed to be a list of displayable scalars.
	 *                               -option.attr: The property in each option array to use for
	 *                                additional selection attributes. Defaults to none.
	 *                               -option.disable: The property that will hold the disabled state.
	 *                                Defaults to "disable".
	 *                               -option.key: The property that will hold the selection value.
	 *                                Defaults to "value".
	 *                               -option.text: The property that will hold the the displayed text.
	 *                               Defaults to "text". If set to null, the option array is assumed to be a
	 *                               list of displayable scalars.
	 * @param   string   $optText    The name of the object variable for the option text.
	 * @param   mixed    $selected   The key that is selected (accepts an array or a string)
	 * @param   boolean  $translate  Translate the option values.
	 *
	 * @return  void
	 *
	 * @covers ::options
	 * @dataProvider  dataOptions
	 * @since         3.1
	 */
	public function testOptions($expected, $arr, $optKey = 'value', $optText = 'text', $selected = null, $translate = false)
	{
		$this->assertEquals(
			$expected,
			Select::options($arr, $optKey, $optText, $selected, $translate)
		);
	}

	/**
	 * Options dataset
	 *
	 * @return  array
	 *
	 * @since   3.1
	 */
	public function dataOption()
	{
		return array(
			// Function parameters array($expected, $value, $text = '', $optKey = 'value', $optText = 'text', $disable = false)
			array(
				array(
					'value' => 'optionValue',
					'text' => 'optionText',
					'disable' => false,
				),
				'optionValue',
				'optionText'
			),
			array(
				array(
					'fookey' => 'optionValue',
					'bartext' => 'optionText',
					'disable' => false,
				),
				'optionValue',
				'optionText',
				'fookey',
				'bartext',
			),
			array(
				array(
					'value' => 'optionValue',
					'text' => 'optionText',
					'disable' => true,
				),
				'optionValue',
				'optionText',
				'value',
				'text',
				true,
			),
			array(
				array(
					'optionValue' => 'optionValue',
					'optionText' => 'optionText',
					'foobarDisabled' => false,
					'lebal' => 'My Label',
					'class' => 'foo bar',
				),
				'optionValue',
				'optionText',
				array(
					'option.disable' => 'foobarDisabled',
					'option.attr' => 'class',
					'attr' => 'foo bar',
					'option.label' => 'lebal',
					'label' => "My Label",
					'option.key' => 'optionValue',
					'option.text' => 'optionText',
				),
			),
			array(
				array(
					'value' => 'optionValue',
					'text' => 'optionText',
					'label' => '',
					'disable' => false,
				),
				'optionValue',
				'optionText',
				array(
					'option.label' => 'label',
				),
			),
		);
	}

	/**
	 * Test the option method.
	 *
	 * @param   object   $expected  Expected Object.
	 * @param   string   $value     The value of the option
	 * @param   string   $text      The text for the option
	 * @param   mixed    $optKey    If a string, the returned object property name for
	 *                              the value. If an array, options. Valid options are:
	 *                              attr: String|array. Additional attributes for this option.
	 *                              Defaults to none.
	 *                              disable: Boolean. If set, this option is disabled.
	 *                              label: String. The value for the option label.
	 *                              option.attr: The property in each option array to use for
	 *                              additional selection attributes. Defaults to none.
	 *                              option.disable: The property that will hold the disabled state.
	 *                              Defaults to "disable".
	 *                              option.key: The property that will hold the selection value.
	 *                              Defaults to "value".
	 *                              option.label: The property in each option array to use as the
	 *                              selection label attribute. If a "label" option is provided, defaults to
	 *                              "label", if no label is given, defaults to null (none).
	 *                              option.text: The property that will hold the the displayed text.
	 *                              Defaults to "text". If set to null, the option array is assumed to be a
	 *                              list of displayable scalars.
	 * @param   string   $optText   The property that will hold the the displayed text. This
	 *                              parameter is ignored if an options array is passed.
	 * @param   boolean  $disable   Not used.
	 *
	 * @return  void
	 *
	 * @covers ::option
	 * @dataProvider  dataOption
	 * @since         3.2
	 */
	public function testOption($expected, $value, $text = '', $optKey = 'value',
		$optText = 'text', $disable = false)
	{
		$this->assertEquals(
			(object) $expected,
			Select::option($value, $text, $optKey, $optText, $disable)
		);
	}
}
