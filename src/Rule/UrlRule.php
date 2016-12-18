<?php
/**
 * Part of the Joomla! Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Rule;

use Joomla\Form\Form;
use Joomla\Form\Rule;
use Joomla\String\StringHelper;
use Joomla\Uri\UriHelper;
use Joomla\Registry\Registry;

/**
 * URL Form Rule class for the Joomla! Framework.
 *
 * Validates that a field's value is formatted as a valid URL.
 *
 * @since  1.0
 */
class UrlRule extends Rule
{
	/**
	 * Method to test an external url for a valid parts.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 * @param   Registry           $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   Form               $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   1.0
	 * @link    http://www.w3.org/Addressing/URL/url-spec.txt
	 * @see	    \Joomla\String\StringHelper
	 */
	public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		// If the field is empty and not required, the field is valid.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');

		if (!$required && $value === '')
		{
			return true;
		}

		$urlParts = UriHelper::parse_url($value);

		// See http://www.w3.org/Addressing/URL/url-spec.txt
		// Use the full list or optionally specify a list of permitted schemes.
		if ($element['schemes'] == '')
		{
			$scheme = array('http', 'https', 'ftp', 'ftps', 'gopher', 'mailto', 'news', 'prospero', 'telnet', 'rlogin', 'tn3270', 'wais', 'url',
				'mid', 'cid', 'nntp', 'tel', 'urn', 'ldap', 'file', 'fax', 'modem', 'git');
		}
		else
		{
			$scheme = explode(',', $element['schemes']);
		}

		/*
		 * This rule is only for full URLs with schemes because parse_url does not parse
		 * accurately without a scheme.
		 * @see http://php.net/manual/en/function.parse-url.php
		 */
		if ($urlParts && !array_key_exists('scheme', $urlParts))
		{
			return false;
		}

		$urlScheme = (string) $urlParts['scheme'];
		$urlScheme = strtolower($urlScheme);

		if (in_array($urlScheme, $scheme) == false)
		{
			return false;
		}

		// For some schemes here must be two slashes.
		$scheme = array('http', 'https', 'ftp', 'ftps', 'gopher', 'wais', 'prospero', 'sftp', 'telnet', 'git');

		if (in_array($urlScheme, $scheme) && substr($value, strlen($urlScheme), 3) !== '://')
		{
			return false;
		}

		// The best we can do for the rest is make sure that the strings are valid UTF-8
		// and the port is an integer.
		if (array_key_exists('host', $urlParts) && !StringHelper::valid((string) $urlParts['host']))
		{
			return false;
		}

		if (array_key_exists('port', $urlParts) && !is_int((int) $urlParts['port']))
		{
			return false;
		}

		if (array_key_exists('path', $urlParts) && !StringHelper::valid((string) $urlParts['path']))
		{
			return false;
		}

		return true;
	}
}
