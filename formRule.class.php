<?php
require_once ('formCheck.class.php');
require_once ('exception'.DIRECTORY_SEPARATOR.'formRuleException.class.php');

/**
 * class formRule
 * @author Codefalse <codefalse@altern.org>
 * @version 20070712
 * @licence : GPL
 */
class formRule {
	/**
	 * Delimiter for the subs Elements
	 *
	 * @var String
	 */
	protected $_sDelimiter;

	/**
	 * The value of the Element analysed
	 *
	 * @var Array
	 */
	protected $_aElementValue;

	/**
	 * The number of errors occured
	 *
	 * @var integer
	 */
	protected $_iCountErrors = 0;

	/**
	 * Contains all the Errors
	 *
	 * @var Array
	 */
	protected $_aErrors = array ();

	/**
	 * Contains all the rules
	 *
	 * @var Array
	 */
	protected $_aRules = array ();

	/**
	 * @name __construct
	 * Constructor
	 * Prepare an element for the specifics rules
	 *
	 * @param String or Array $sElement : Name of the element, if it's an array, can be separated by $sDelimiter (default : '.');
	 * @param Array $aFrom : Array where the Element is
	 * @param String $sDelimiter (default : '.') : Delimiter for array Elements
	 */
	public function __construct ($mElement, &$aFrom=null, $sDelimiter='.') {
		if (isset ($sDelimiter) && !is_string ($sDelimiter))
			throw new formRuleException ('ERR_FORMRULE_STRING', 3, __LINE__, __FUNCTION__);
		
		if (!isset ($aFrom))
			$aFrom = &$_POST;
		
		$this->_sDelimiter = $sDelimiter;

		if (count ($aFrom) > 0) {
			if (isset ($aFrom[$mElement]) && is_array ($aFrom[$mElement]))
				$this->_aElementValue = $aFrom[$mElement];

			else if (is_string ($mElement)) {
				$sSElts = explode ($this->_sDelimiter, $mElement);
				$aToElement = $aFrom;
			
				foreach ($sSElts as $sCurrentElement) {
					if (isset ($aToElement[$sCurrentElement]))
						$aToElement = $aToElement[$sCurrentElement];
					else {
						//throw new formRuleException ('ERR_FORMRULE_ELEMENT_NOT_EXISTS', $mElement, __LINE__, __FUNCTION__);
						$aToElement = '';
						break;
					}
				}
				
				$this->_aElementValue = $aToElement;
			}
			else
				throw new formRuleException ('ERR_FORMRULE_STRING_ARRAY', 1, __LINE__, __FUNCTION__);
		}
	}

	/**
	 * @name __call
	 * Magic function to call functions from formCheck class if it's exists !
	 *
	 * @param String $sName : Name of the function
	 * @param Array $aArgs : Array of parameters
	 */
	public function __call ($sName, $aArgs) {
		if (isset ($this->_aElementValue)) {
			$sRuleName = strtolower (substr ($sName, 3, 1)).substr ($sName, 4);
			
			if (isset ($aArgs[1])) {
				$sErrorMsg = $aArgs[1];
				$mParams = $aArgs[0];
			}
			else {
				$sErrorMsg = $aArgs[0];
				$mParams = null;
			}
	
			if (is_callable ('formCheck::'.$sRuleName)) {
				if (is_string ($this->_aElementValue))
					$aElementValue = array ($this->_aElementValue);
				else
					$aElementValue = $this->_aElementValue;
				
				foreach ($aElementValue as $sValue) {
					if ((formCheck::$sRuleName ($sValue, $mParams)) === false) {
						$this->_iCountErrors++;
						$this->_aErrors[] = $sErrorMsg;
					}
				}
			}
			else
				throw new formRuleException ('ERR_FORMRULE_FUNC_NOT_EXISTS', $sRuleName, __LINE__, __FUNCTION__);
		}
	}

	/**
	 * @name __toString
	 * Magic function of php to return the first value when the object is written
	 *
	 * @return String
	 */
	public function __toString () {
		if (isset ($this->_aElementValue)) {
			if (is_array ($this->_aElementValue))
				return reset ($this->_aElementValue);
			else if (is_string ($this->_aElementValue))
				return $this->_aElementValue;
		}
		
		return '';
	}

	/**
	 * @name getValue
	 * Return the values if first parameter is set, or, a an array containing all values
	 * if it's an array, or a string
	 *
	 * @param String or Int $mKeyValue : The Value or Key in the Element
	 * @param Boolean $bByValue : Search by Value (true) or by key (default, false)
	 * @return unknown
	 */
	public function getValue ($mKeyValue = null, $bByValue = false) {
		if (isset ($this->_aElementValue)) {
			if (is_string ($this->_aElementValue))
				return $this->_aElementValue;

			else {
				if (isset ($mKeyValue)) {
					if (!is_string ($mKeyValue) || !is_int ($mKeyValue))
						throw new formRuleException ('ERR_FORMRULE_STRING_INT', $sRuleName, __LINE__, __FUNCTION__);
					
					if (!is_bool ($bByValue))
						throw new formRuleException ('ERR_FORMRULE_BOOLEAN', $sRuleName, __LINE__, __FUNCTION__);
					
					if ($bByValue == true) {
						$mKeyInArray = array_search($mKeyValue, $this->_aElementValue);
						return $this->_aElementValue[$mKeyInArray];
					}
					else {
						if (isset ($this->_aElementValue[$mKeyValue]))
							return $this->_aElementValue[$mKeyValue];
					}
				}
				else {
					return $this->_aElementValue;
				}
			}
		}
		
		return null;
	}

	/**
	 * @name isValid
	 * Indicate if the element has no errors from the rules
	 *
	 * @return boolean
	 */
	public function isValid () {
		return (isset ($this->_aElementValue) && $this->_iCountErrors == 0) ? true : false;
	}

	/**
	 * @name getError
	 * Return the error given by $iIdError or the first
	 *
	 * @param integer $iIdError : The number of the error
	 * 
	 * @return String : The error or null
	 */
	public function getError ($iIdError = 0) {
		if (isset ($this->_aElementValue)) {
			if (!is_int ($iIdError))
				throw new formRuleException ('ERR_FORMRULE_INT', $iIdError, __LINE__, __FUNCTION__);
			
			if ($iIdError >= count ($this->_aErrors))
				throw new formRuleException ('ERR_FORMRULE_INDEX', $iIdError, __LINE__, __FUNCTION__);
			
			return $this->_aErrors[$iIdError];
		}
		
		return null;
	}

	/**
	 * @name getAllErrors
	 * Return an array containing all the Errors
	 *
	 * @return Array
	 */
	public function getAllErrors () {
		return (isset ($this->_aElementValue)) ? $this->_aErrors : null;
	}

	/**
	 * @name callFunction
	 * Transform value to the return of the called function
	 *
	 * @param Mixed $mFunction : The called function in (String) or Array ("class", "function");
	 * @param Mixed $mIndex : Index in the array of values (default 0) Could be Int or String
	 */
	public function callFunction ($mFunction, $mIndex=0) {
		if (!is_int ($mIndex) || is_string ($mIndex))
			throw new formRuleException ('ERR_FORMRULE_STRING_INT', $iIdError, __LINE__, __FUNCTION__);
		
		if (is_callable ($mFunction)) {
			if (isset ($this->_aElementValue[$mIndex]))
				$this->_aElementValue[$mIndex] = call_user_func ($mFunction, $this->_aElementValue[$mIndex]);
			else if (isset ($this->_aElementValue) && is_string ($this->_aElementValue))
				$this->_aElementValue = call_user_func ($mFunction, $this->_aElementValue);
			else
				throw new formRuleException ('ERR_FORMRULE_ELEMENT_NOT_EXISTS', $mIndex, __LINE__, __FUNCTION__);
		}
		else
			throw new formRuleException ('ERR_FORMRULE_FUNC_NOT_EXISTS', print_r ($mFunction, true), __LINE__, __FUNCTION__);
	}
}
?>