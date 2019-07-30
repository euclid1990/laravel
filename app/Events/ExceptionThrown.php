<?php

namespace App\Events;

use Exception;
use Throwable;

class ExceptionThrown
{
    /**
     * @var string
     */
    public $message = '';
    /**
     * @var array
     */
    public $context = '';
    /**
     * @var string
     */
    public $trace = '';

    /**
     * @param string $message, array $context, string $trace
     */
    public function __construct($exception)
    {
        $this->message = $exception->getMessage();

        $this->trace = $exception->getTraceAsString();

        $e = $this->convertException($exception);
        $auth = $this->getAuth();
        $url = $this->getUrl();
        $server = $this->getServer();
        $client = $this->getClient();
        $git = $this->getGit();

        $this->context = $e + $auth + $url + $server + $client + $git;
    }

    /**
     * Convert Exception.
     *
     * @param \Exception $exception
     * @return array
     */
    public function convertException(Exception $exception)
    {
        try {
            return array_filter([
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Get url.
     *
     * @return array
     */
    public function getUrl()
    {
        try {
            return array_filter([
                'route_url' => app('url')->current(),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Get auth.
     *
     * @return array
     */
    public function getAuth()
    {
        try {
            return array_filter([
                'user_id' => app('auth')->id(),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Get info server
     *
     * @return array
     */
    public function getServer(): array
    {
        try {
            return array_filter([
                'server_ip' => request()->server('SERVER_ADDR'),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Get info client
     *
     * @return array
     */
    public function getClient(): array
    {
        try {
            return array_filter([
                'client_ip' => request()->ip(),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getGit()
    {
        try {
            return array_filter([
                'commit' => env('APP_VERSION'),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }
}
