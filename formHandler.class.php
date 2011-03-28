<?php
require_once ('exception'.DIRECTORY_SEPARATOR.'formHandlerException.class.php');

/**
 * class formHandler
 * @author Codefalse <codefalse@altern.org>
 * @version 20070712
 * @licence : GPL
 */
class formHandler {
	/**
	 * Contain all the rules
	 *
	 * @var Array
	 */
	protected $_aRules = array ();

	/**
	 * Contain the first error occured (if error occured :p)
	 *
	 * @var String
	 */
	protected $_sFirstError;

	/**
	 * @name addRule
	 * Add one or more rule (instances of formRule)
	 * 
	 * @param formRule 1-xxx : One or more instance of formRule
	 */
	public function addRule () {
		if (func_num_args () > 0) {
			$aArgs = func_get_args ();
			foreach ($aArgs as $iKey=>$oRule) {
				if ($oRule instanceof formRule)
					$this->_aRules[] = $oRule;
				else 
					throw new formHandlerException ('ERR_FORMHANDLER_FORMRULE_INSTANCE', $iKey, __LINE__, __FUNCTION__);
			}
		}
	}

	/**
	 * @name validate
	 * Check if the rules are valid
	 *
	 * @return boolean (valid or not)
	 */
	public function validate () {
		foreach ($this->_aRules as $oRule) {
			if (!$oRule->isValid()) {
				$this->_sFirstError = $oRule->getError ();
				return false;
			}
		}
		return true;
	}

	/**
	 * @getFirstError
	 * Return the first Error occured
	 *
	 * @return String
	 */
	public function getFirstError () {
		return $this->_sFirstError;
	}
}
?>