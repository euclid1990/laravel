<?php
/**
 * Framgia_Sniffs_Variable_ValidVariableNameSniff.
 *
 * Checks the naming of variables and member variables.
 */
namespace Framgia\Sniffs\Variables;

use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Common;

class ValidVariableNameSniff extends AbstractVariableSniff
{

    /**
     * Tokens to ignore so that we can find a DOUBLE_COLON.
     *
     * @var array
     */
    private $_ignore = [
        T_WHITESPACE,
        T_COMMENT,
    ];


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    protected function processVariable(File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        $phpReservedVars = [
            '_SERVER',
            '_GET',
            '_POST',
            '_REQUEST',
            '_SESSION',
            '_ENV',
            '_COOKIE',
            '_FILES',
            'GLOBALS',
            'http_response_header',
            'HTTP_RAW_POST_DATA',
            'php_errormsg',
        ];

        // If it's a php reserved var, then its ok.
        if (in_array($varName, $phpReservedVars) === true) {
            return;
        }

        $this->checkCamelCase($phpcsFile, $stackPtr, $varName);
    }//end processVariable()


    /**
     * Processes class member variables.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(File $phpcsFile, $stackPtr)
    {
        // We do not care about class property
    }//end processMemberVar()


    /**
     * Processes the variable found within a double quoted string.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the double quoted
     *                                        string.
     *
     * @return void
     */
    protected function processVariableInString(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $phpReservedVars = [
            '_SERVER',
            '_GET',
            '_POST',
            '_REQUEST',
            '_SESSION',
            '_ENV',
            '_COOKIE',
            '_FILES',
            'GLOBALS',
            'http_response_header',
            'HTTP_RAW_POST_DATA',
            'php_errormsg',
        ];

        if (preg_match_all(
            '|[^\\\]\${?([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)|',
            $tokens[$stackPtr]['content'],
            $matches
            ) !== 0) {
            foreach ($matches[1] as $varName) {
                // If it's a php reserved var, then its ok.
                if (in_array($varName, $phpReservedVars) === true) {
                    continue;
                }

                $this->checkCamelCase($phpcsFile, $stackPtr, $varName);
            }
        }
    }//end processVariableInString()

    private function checkCamelCase(File $phpcsFile, $stackPtr, $objVarName)
    {
        $originalVarName = $objVarName;
        if (substr($objVarName, 0, 1) === '_') {
            $error = 'Variable "%s" must not contain a leading underscore';
            $data  = [$originalVarName];
            $phpcsFile->addError($error, $stackPtr, 'VariableHasUnderscore', $data);
            return;
        }

        if (Common::isCamelCaps($objVarName, false, true, false) === false) {
            $error = 'Variable "%s" is not in valid camel caps format';
            $data  = [$originalVarName];
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
        }
    }
}//end class
