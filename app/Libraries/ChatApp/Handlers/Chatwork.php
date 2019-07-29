<?php

namespace App\Libraries\ChatApp\Handlers;

use GuzzleHttp\Client;
use App\Libraries\ChatApp\ChatAppHandler;
use App\Libraries\ChatApp\Messages\Chatwork as ChatworkMessage;

class Chatwork implements ChatAppHandler
{
    /**
     * Chatwork api base url.
     *
     * @var string
     */
    protected $base = 'https://api.chatwork.com';

    /**
     * Chatwork api version.
     *
     * @var string
     */
    protected $version = 'v2';

    /**
     * Chatwork api token request header name.
     *
     * @var string
     */
    protected $tokenHeader = 'X-ChatWorkToken';

    /**
     * Chatwork api request header.
     *
     * @var string
     */
    protected $header = [];

    /**
     * Message.
     *
     * @var object
     */
    public $message;

    /**
     * Request Method.
     *
     * @var string
     */
    protected $method = 'POST';

    /**
     * Request End point.
     *
     * @var string
     */
    protected $endPoint = '';

    /**
     * Chatwork Api Key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor.
     *
     * @param $apiKey
     * @param string $method
     */
    public function __construct(ChatworkMessage $message)
    {
        $this->message = $message;
    }

    public function withEnv()
    {
        $this->endPoint = 'rooms/' . env('CHATWORK_ROOM_ID') . '/messages';
        $this->apiKey = env('CHATWORK_API_KEY');
        $this->withHeader([]);

        return $this;
    }

    public function withEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    public function withApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->withHeader([]);

        return $this;
    }

    public function withMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function withMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Build header.
     *
     * @return string
     */
    public function withHeader($header)
    {
        $this->header = $header;
        $this->header[$this->tokenHeader] = $this->apiKey;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    protected function buildUrl()
    {
        return "{$this->base}/{$this->version}/{$this->endPoint}";
    }

    /**
     * Set params.
     *
     * @return string
     */
    protected function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Send Request to Chatwork.
     *
     * @throws RequestFailException
     *
     * @return array
     */
    public function dispatch()
    {
        $client = new Client([
            'headers' => $this->header,
        ]);
        $url = $this->buildUrl();

        $client->request($this->method, $url, [
            'form_params' => [
                'body' => $this->message,
            ],
        ]);
    }
}
