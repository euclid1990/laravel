<?php
/**
 * Unit test class for the ValidVariableNameSniff sniff.
 */

namespace Framgia\Tests\Variables;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

class ValidVariableNameSniffUnitTest extends AbstractSniffUnitTest
{


    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getErrorList()
    {
        return [
            3 => 1,
            13 => 1,
            14 => 1,
            16 => 1,
        ];

    }//end getErrorList()


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return [
            1 => 1,
        ];

    }//end getWarningList()


}//end class