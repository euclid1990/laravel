<?php

namespace App\Libraries\ChatApp;

use Illuminate\Support\Manager;

class ChatAppManager extends Manager
{
    /**
     * Call a custom chatapp handler.
     *
     * @param  string|null $driver
     * @return mixed
     */
    public function handle($driver = null)
    {
        return $this->driver($driver);
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return 'chatwork';
    }
}
