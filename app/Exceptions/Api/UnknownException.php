<?php

namespace App\Exceptions\Api;

class UnknownException extends ApiException
{
    public function __construct($message = null, $statusCode = 400)
    {
        $message = $message ? $message : trans('http_message.400');

        parent::__construct($message, $statusCode);
    }
}
