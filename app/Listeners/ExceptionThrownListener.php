<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Libraries\ChatApp\ChatAppException;
use Throwable;
use Log;

class ExceptionThrownListener implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'monitor';

    public function handle($event)
    {
        try {
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
        } catch (Throwable $e) {
            Log::error($e);

            throw new ChatAppException();
        }
    }
}
