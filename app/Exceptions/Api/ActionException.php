<?php

namespace App\Exceptions\Api;
use Symfony\Component\HttpFoundation\Response;

class ActionException extends ApiException
{
    public function __construct(string $action = null, int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $action = $action ? __('exception.' . $action) : __('http_message.' . Response::HTTP_INTERNAL_SERVER_ERROR500);
        parent::__construct($action, $statusCode);
    }
}
