<?php

namespace App\Libraries\ChatApp\Messages;

use Illuminate\Notifications\Messages\SlackMessage;

class Slack extends SlackMessage
{
    /**
     * @var string
     */
    protected $message = '';

    /**
     * Set message.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Reset message to empty string.
     */
    public function resetMessage()
    {
        $this->setMessage('');
    }

    /**
     * @return string message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
