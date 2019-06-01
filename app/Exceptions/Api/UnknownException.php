<?php

namespace App\Exceptions\Api;

use Symfony\Component\HttpFoundation\Response;

class UnknownException extends ApiException
{
    public function __construct(string $message = null, int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $message = $message ? $message : __('http_message.' . Response::HTTP_BAD_REQUEST);

        parent::__construct($message, $statusCode);
    }
}
