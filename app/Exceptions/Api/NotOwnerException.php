<?php

namespace App\Exceptions\Api;

use Symfony\Component\HttpFoundation\Response;

class NotOwnerException extends ApiException
{
    public function __construct(string $message = null, int $statusCode = Response::HTTP_FORBIDDEN)
    {
        $message = $message ? $message : __('http_message.' . Response::HTTP_FORBIDDEN);
        parent::__construct($message, $statusCode);
    }
}
