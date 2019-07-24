<?php

namespace App\Libraries\ChatApp;

interface ChatAppHandler
{
    public function dispatch();

    public function withMessage($message);
}
