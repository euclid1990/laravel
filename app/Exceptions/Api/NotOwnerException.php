<?php

namespace App\Exceptions\Api;

class NotOwnerException extends ApiException
{
    public function __construct($message = null, $statusCode = 403)
    {
        $message = $message ? $message : trans('http_message.403');
        parent::__construct($message, $statusCode);
    }
}
