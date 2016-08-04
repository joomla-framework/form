<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form;

use Joomla\String\StringHelper;

/**
 * Helper class for the Form package.
 *
 * Provides a storage for filesystem's paths where Joomla\Form entities reside and methods for creating those entities.
 * Also stores objects with entities' prototypes for further reusing.
 *
 * @since  1.0
 */
class FormHelper
{
	/**
	 * Array with paths where entities(field, rule, form) can be found.
	 *
	 * Array's structure:
	 * <code>
	 * paths:
	 * {ENTITY_NAME}:
	 * - /path/1
	 * - /path/2
	 * </code>
	 *
	 * @var    array
	 * @since  1.0
	 *
	 */
	protected static $paths;

	/**
	 * Attempt to import the Field class file if it isn't already imported.
	 *
	 * You can use this method outside of Joomla\Form for loading a field for inheritance or composition.
	 *
	 * @param   string  $type  Type of a field whose class should be loaded.
	 *
	 * @return  mixed  Class name on success or false otherwise.
	 *
	 * @since   1.0
	 */
	public static function loadFieldClass($type)
	{
		return self::loadClass('field', $type);
	}

	/**
	 * Attempt to import the Rule class file if it isn't already imported.
	 *
	 * You can use this method outside of Joomla\Form for loading a rule for inheritance or composition.
	 *
	 * @param   string  $type  Type of a rule whose class should be loaded.
	 *
	 * @return  mixed  Class name on success or false otherwise.
	 *
	 * @since   1.0
	 */
	public static function loadRuleClass($type)
	{
		return self::loadClass('rule', $type);
	}

	/**
	 * Load a class for one of the form's entities of a particular type.
	 *
	 * Currently, it makes sense to use this method for the "field" and "rule" entities
	 * (but you can support more entities in your subclass).
	 *
	 * @param   string  $entity  One of the form entities (field or rule).
	 * @param   string  $type    Type of an entity.
	 *
	 * @return  boolean|string  Class name on success or false otherwise.
	 *
	 * @since   1.0
	 */
	protected static function loadClass($entity, $type)
	{
		if (strpos($type, '.'))
		{
			list($prefix, $type) = explode('.', $type);
		}
		else
		{
			$prefix = 'Joomla';
		}

		$class = ucfirst($prefix) . '\\Form\\' . ucfirst($entity);

		// If type is complex like modal\foo, do uppercase each term
		if (strpos($type, '\\'))
		{
			$class .= '\\' . StringHelper::ucfirst($type, '\\');
		}
		else
		{
			$class .= '\\' . ucfirst($type);
		}

		$class .= ucfirst($entity);

		// Check for all if the class exists.
		return class_exists($class) ? $class : false;
	}

	/**
	 * Method to add a path to the list of form include paths.
	 *
	 * @param   mixed  $new  A path or array of paths to add.
	 *
	 * @return  array  The list of paths that have been added.
	 *
	 * @since   1.0
	 */
	public static function addFormPath($new = null)
	{
		return self::addPath('form', $new);
	}

	/**
	 * Method to add a path to the list of include paths for one of the form's entities.
	 * Currently supported entities: field, rule and form. You are free to support your own in a subclass.
	 *
	 * @param   string  $entity  Form's entity name for which paths will be added.
	 * @param   mixed   $new     A path or array of paths to add.
	 *
	 * @return  array  The list of paths that have been added.
	 *
	 * @since   1.0
	 */
	protected static function addPath($entity, $new = null)
	{
		// Reference to an array with paths for current entity
		$paths = &self::$paths[$entity];

		// Add the default entity's search path if not set.
		if (empty($paths))
		{
			// While we support limited number of entities (form, field and rule)
			// we can do this simple pluralisation:
			$entity_plural = $entity . 's';

			/*
			 * But when someday we would want to support more entities, then we should consider adding
			 * an inflector class to "libraries/joomla/utilities" and use it here (or somebody can use a real inflector in his subclass).
			 * See also: pluralization snippet by Paul Osman in JControllerForm's constructor.
			 */
			$paths[] = __DIR__ . '/' . $entity;
		}

		// Force the new path(s) to an array.
		settype($new, 'array');

		// Add the new paths to the stack if not already there.
		foreach ($new as $path)
		{
			if (!in_array($path, $paths))
			{
				array_unshift($paths, trim($path));
			}

			if (!is_dir($path))
			{
				array_unshift($paths, trim($path));
			}
		}

		return $paths;
	}
}
