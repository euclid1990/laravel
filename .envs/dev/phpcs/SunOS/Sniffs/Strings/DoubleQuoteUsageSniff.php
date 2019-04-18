<?php
/**
 * Framgia_Sniffs_String_DoubleQuoteUsageSniff.
 *
 * Makes sure that any use of Double Quotes ("") are warranted.
 *
 */
namespace Framgia\Sniffs\Strings;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DoubleQuoteUsageSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_CONSTANT_ENCAPSED_STRING,
            T_DOUBLE_QUOTED_STRING,
        ];
    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // We are only interested in the first token in a multi-line string.
        if ($tokens[$stackPtr]['code'] === $tokens[($stackPtr - 1)]['code']) {
            return;
        }

        $workingString   = $tokens[$stackPtr]['content'];
        $lastStringToken = $stackPtr;

        $i = ($stackPtr + 1);
        if (isset($tokens[$i]) === true) {
            while ($tokens[$i]['code'] === $tokens[$stackPtr]['code']) {
                $workingString  .= $tokens[$i]['content'];
                $lastStringToken = $i;
                $i++;
            }
        }

        // Check if it's a double quoted string.
        if (strpos($workingString, '"') === false) {
            return;
        }

        // Make sure it's not a part of a string started in a previous line.
        // If it is, then we have already checked it.
        if ($workingString[0] !== '"') {
            return;
        }

        // The use of variables in double quoted strings is not allowed.
        if ($tokens[$stackPtr]['code'] === T_DOUBLE_QUOTED_STRING) {
            $stringTokens = token_get_all('<?php '.$workingString);
            foreach ($stringTokens as $token) {
                if (is_array($token) === true && $token[0] === T_VARIABLE) {
                    return;
                }
            }
        }//end if

        $allowedChars = [
            '\0',
            '\n',
            '\r',
            '\f',
            '\t',
            '\v',
            '\x',
            '\b',
            '\'',
        ];

        foreach ($allowedChars as $testChar) {
            if (strpos($workingString, $testChar) !== false) {
                return;
            }
        }

        $error = 'String %s does not require double quotes; use single quotes instead';
        $data  = [str_replace("\n", '\n', $workingString)];
        $phpcsFile->addError($error, $stackPtr, 'NotRequired', $data);
    }//end process()
}//end class
