<?php

namespace App\Libraries\ChatApp\Handlers;

use GuzzleHttp\Client;
use App\Libraries\ChatApp\ChatAppHandler;
use App\Libraries\ChatApp\Messages\Slack as SlackMessage;
use Illuminate\Notifications\Channels\SlackWebhookChannel;

class Slack extends SlackWebhookChannel implements ChatAppHandler
{
    /**
     * slack message.
     *
     * @var object
     */
    public $message;

    /**
     * Request End point.
     *
     * @var string
     */
    protected $endPoint = '';

    /**
     * Constructor.
     *
     * @param $apiKey
     * @param string $method
     */
    public function __construct(SlackMessage $message, Client $client)
    {
        $this->message = $message;

        parent::__construct($client);
    }

    public function withEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    public function withEnv()
    {
        $this->endPoint = env('SLACK_HOOK');

        return $this;
    }

    /**
     * slack message content data.
     *
     * @return string
     */
    public function withMessage($message)
    {
        $this->message->content = $message;

        return $this;
    }

    /**
     * Send Request to Slack.
     *
     * @throws RequestFailException
     *
     * @return array
     */
    public function dispatch()
    {
        $this->http->post($this->endPoint, $this->buildJsonPayload(
            $this->message
        ));
    }
}
