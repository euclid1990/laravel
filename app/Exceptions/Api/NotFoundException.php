<?php

namespace App\Exceptions\Api;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends ApiException
{
    public function __construct(string $message = null, int $statusCode = Response::HTTP_NOT_FOUND)
    {
        $message = $message ? $message : __('http_message.' . Response::HTTP_NOT_FOUND);

        parent::__construct($message, $statusCode);
    }
}
