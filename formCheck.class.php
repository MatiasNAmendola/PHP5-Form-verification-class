<?php
/**
 * class formCheck
 * @author Codefalse <codefalse@altern.org>
 * @version 20070712
 * @licence : GPL
 */
class formCheck {
	/**
	 * @name isRequired
	 * The input value is mandatory
	 *
	 * @param String $sValue  : The value to test
	 * 
	 * @return boolean
	 */
	public static function isRequired ($sValue) {
		return (!isset ($sValue) || empty ($sValue)) ? false : true;
	}

	/**
	 * @name isEmail
	 * the input value must correspond to the general form of an email address
	 *
	 * @param String $sValue  : The value to test
	 * 
	 * @return boolean
	 */
	public static function isEmail ($sValue) {
		return (preg_match ('#^([_a-z0-9-+}{]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#i', $sValue) == 0) ? false : true;
	}

	/**
	 * @name isAlpha
	 * the input value can only contain alphabetic characters
	 *
	 * @param String $sValue  : The value to test
	 * 
	 * @return boolean
	 */
	public static function isAlpha ($sValue) {
		return (preg_match ('#([^a-z])#i', $sValue) == 0) ? true : false;
	}

	/**
	 * @name isAlphanumeric
	 * the input value can only contain alphanumeric characters
	 * 
	 *
	 * @param String $sValue  : The value to test
	 * 
	 * @return boolean
	 */
	public static function isAlphanumeric ($sValue) {
		return (preg_match ('#([^a-z0-9])#i', $sValue) == 0) ? true : false;
	}

	/**
	 * @name isNumeric
	 * the input value can only contain numbers
	 *
	 * @param String $sValue  : The value to test
	 * 
	 * @return boolean
	 */
	public static function isNumeric ($sValue) {
		return (preg_match ('#([^0-9])#', $sValue) == 0) ? true : false;
	}

	/**
	 * @name maxLength
	 * the input value must not contain more than the specified number of characters
	 *
	 * @param Mixed $mValue  : The value to test (Int or String)
	 * @param int $iLength : The max value
	 * 
	 * @return boolean
	 */
	public static function maxLength ($mValue, $iLength) {
		if (is_int ($mValue))
			return ($mValue > $iLength) ? false : true;
		else if (is_string ($mValue))
			return (strlen($mValue) > $iLength) ? false : true;
	}

	/**
	 * @name minLength
	 * the input value must not contain less than the specified number of characters
	 *
	 * @param Mixed $mValue  : The value to test (Int or String)
	 * @param int $iLength : The min value
	 * 
	 * @return boolean
	 */
	public static function minLength ($mValue, $iLength) {
		if (is_int ($mValue))
			return ($mValue < $iLength) ? false : true;
		else if (is_string ($mValue))
			return (strlen($mValue) < $iLength) ? false : true;
	}

	/**
	 * @name rangeLength
	 * the length of the input value must fall within the specified range
	 *
	 * @param Mixed $mValue  : The value to test (Int or String)
	 * @param Array $aLengths : Array of two integer (min, max)
	 * 
	 * @return boolean
	 */
	public static function rangeLength ($mValue, $aLengths) {
		if (is_int ($mValue))
			return ($mValue < $aLengths[0] || $mValue > $aLengths[1]) ? false : true;
		else if (is_string ($mValue))
			return (strlen($mValue) < $aLengths[0] || strlen($mValue) > $aLengths[1]) ? false : true;
	}

	/**
	 * @name useRegex
	 * the input value must correspond to the specified regular expression
	 *
	 * @param String $sValue  : The value to test
	 * @param String $sRegex : The regexp
	 * 
	 * @return boolean
	 */
	public static function regex ($sValue, $sRegex) {
		return (preg_match ($sRegex, $sValue) != 0) ? false : true;
	}

	/**
	 * @name callback
	 * the input value is to be passed to a named external function for validation
	 *
	 * @param String $sValue  : The value to test
	 * @param Mixed $mCallback : Function to call
	 * 
	 * @return boolean
	 */
	public static function callback ($sValue, $mCallback) {
		if (is_array ($mCallback)) {
			if (method_exists ($mCallback[0], $mCallback[1])) {
				return call_user_func ($mCallback, $sValue);
			}
			else
				return false;
		}
		else
			return $mCallback ($sValue);
	}

	/**
	 * @name isEqual
	 * the input value must be equal to the specified value
	 *
	 * @param String $sValue  : The value to test
	 * @param Mixed $mTest : Value to test (int or String)
	 * 
	 * @return boolean
	 */
	public static function isEqual ($sValue, $mTest) {
		return ($sValue == $mTest) ? true : false;
	}

	/**
	 * @name isNotEqual
	 * the input value must not be equal to the specified value
	 *
	 * @param String $sValue  : The value to test
	 * @param Mixed $mTest : Value to test (int or String)
	 * 
	 * @return boolean
	 */
	public static function isNotEqual ($sValue, $mTest) {
		return ($sValue !== $mTest) ? true : false;
	}

	/**
	 * @name hasExtension
	 * The extension must be in the mExtension array or string
	 *
	 * @param String $sValue
	 * @param Array $mExtension
	 * 
	 * @return boolean
	 */
	public static function hasExtension ($sValue, $mExtension) {
		$sExt = preg_replace ('`.*\.([^\.]*)$`', '$1', $sValue);
		if (is_string ($mExtension))
			$mExtension = array ($mExtension);

		return (in_array ($sExt, $mExtension)) ? true : false;
	}

	/**
	 * @name isUrl
	 * Indicate if the value is an Url or not (
	 *
	 * @param unknown_type $sValue
	 * @return unknown
	 */
	public static function isUrl ($sValue) {
		return (preg_match ('#^http[s]?://[a-z0-9./-]*[.]{1}[a-z0-9./-]*[/]{0,1}.*$#i', $sValue) == 0) ? false : true;
	}
}
?>
