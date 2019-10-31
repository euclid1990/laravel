<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Validation\ValidationException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Check a message exist in array
     *
     * @param array $array
     * @param message $message
     */
    public function assertContainErrorMessage($message, $array)
    {
        $this->assertContains($message, $array);
    }

    /**
     * Check a message exist in array error of ValidationException
     *
     * @param Illuminate\Validation\ValidationException $e
     * @param message $message
     */
    public function assertValidatorExceptionContainErrorMsg(ValidationException $e, $message)
    {
        $allMessage = $e->validator->getMessageBag()->all();
        $this->assertContainErrorMessage($message, $allMessage);
    }
}
