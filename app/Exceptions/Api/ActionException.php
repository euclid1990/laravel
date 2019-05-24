<?php

namespace App\Exceptions\Api;

class ActionException extends ApiException
{
    public function __construct($action = null, $statusCode = 500)
    {
        $action = $action ? trans('exception.' . $action) : trans('http_message.500');
        parent::__construct($action, $statusCode);
    }
}
