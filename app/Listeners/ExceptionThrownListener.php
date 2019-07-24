<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ExceptionThrownListener implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        $this->connection = 'redis';
        $this->queue = 'monitor';
    }

    public function handle($event)
    {
        resolve('chatapp')
            ->handle('chatwork')
            ->withEnv()
            ->withMessage(generate_message('notification_template.chatwork', $event))
            ->dispatch();
        resolve('chatapp')
            ->handle('slack')
            ->withEnv()
            ->withMessage(generate_message('notification_template.slack', $event))
            ->dispatch();
    }
}
