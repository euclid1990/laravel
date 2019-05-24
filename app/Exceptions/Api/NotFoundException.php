<?php

namespace App\Exceptions\Api;

class NotFoundException extends ApiException
{
    public function __construct($message = null, $statusCode = 404)
    {
        $message = $message ? $message : trans('http_message.404');

        parent::__construct($message, $statusCode);
    }
}
