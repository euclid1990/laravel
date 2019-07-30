<?php

namespace App\Libraries\ChatApp;

use Exception;

class ChatAppException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
