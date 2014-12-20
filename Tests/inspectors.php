<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

/**
 * JFormFieldInspector class.
 *
 * @since  1.0
 */
class JFormFieldInspector extends \Joomla\Form\Field
{
	/**
	 * Test...
	 *
	 * @param   string  $name  Element name
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function __get($name)
	{
		if ($name == 'element')
		{
			return $this->element;
		}
		else
		{
			return parent::__get($name);
		}
	}

	/**
	 * Test...
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function getInput()
	{
		return null;
	}

	/**
	 * Test...
	 *
	 * @return  \Joomla\Form\Form
	 *
	 * @since   1.0
	 */
	public function getForm()
	{
		return $this->form;
	}

	/**
	 * Test...
	 *
	 * @param   string  $fieldId    The field element id.
	 * @param   string  $fieldName  The field element name.
	 *
	 * @return  string  The id to be used for the field input tag.
	 *
	 * @since   1.0
	 */
	public function getId($fieldId, $fieldName)
	{
		return parent::getId($fieldId, $fieldName);
	}

	/**
	 * Test...
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getLabel()
	{
		return parent::getLabel();
	}

	/**
	 * Test...
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getTitle()
	{
		return parent::getTitle();
	}
}
