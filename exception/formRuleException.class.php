<?php
/**
 * class formRuleException extends genericException
 * @author Codefalse <codefalse@altern.org>
 * @version 20070712
 * @licence : GPL
 */
class formRuleException extends genericException {
	/**
	 * All the constants used by the core class
	 */
	const ERR_FORMRULE_BOOLEAN = 'Argument N°xx must be a Boolean';
	const ERR_FORMRULE_INT = 'Argument N°xx must be an Int';
	const ERR_FORMRULE_STRING = 'Argument N°xx must be a String';
	const ERR_FORMRULE_STRING_INT = 'Argument N°xx must be a String or an Integer';
	const ERR_FORMRULE_STRING_ARRAY = 'Argument N°xx must be a String or an Array';
	const ERR_FORMRULE_ELEMENT_NOT_EXISTS = 'Element xx does not exists';
	const ERR_FORMRULE_FUNC_NOT_EXISTS = 'Function xx apparently not exists';
	const ERR_FORMRULE_INDEX = 'Index xx does not exist';
	
	// ERR_FORMRULE_STRING_INT

	/**
	 * @name __construct
	 * Constructor
	 * 
	 * @param String sMessage : The generic and internationalised message to display
	 * @param (optional) String sComplement : A complement of the generic message
	 * @param (optional) int iLine : The line where the exception was thrown
	 * @param (optional) String sMethodName : The name of the method where the exception was thrown
	 * @param (optional) String sClassName : The name of the class who throw the exception
	 */
	public function __construct($sMessage, $sComplement, $iLine = 0, $sMethodName = 'unknowMethod') {
		$sCorrectMessage = str_replace ('xx', $sComplement, constant ('formRuleException::'.$sMessage));

		parent::__construct ($sCorrectMessage, $iLine, $sMethodName, 'formRuleException');
	}
}
?>