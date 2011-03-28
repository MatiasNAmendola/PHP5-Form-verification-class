<?php
/**
 * class formHandlerException extends genericException
 * @author Codefalse <codefalse@altern.org>
 * @version 20071129
 * @licence : GPL
 */
class formHandlerException extends genericException {
	/**
	 * All the constants used by the core class
	 */
	const ERR_FORMHANDLER_FORMRULE_INSTANCE = 'Argument N°xx must be an instance of formRule';

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
		$sCorrectMessage = str_replace ('xx', $sComplement, constant ('formHandlerException::'.$sMessage));

		parent::__construct ($sCorrectMessage, $iLine, $sMethodName, 'formHandler');
	}
}
?>
