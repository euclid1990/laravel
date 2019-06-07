<?php

namespace App\Http\Resources;

class AuthResource extends ApiResource
{
    protected $method;

    public function __construct($resource, $method = 'issueToken', $message = null)
    {
        parent::__construct($resource, $message);
        $this->method = $method;
    }

    public function toArray($request)
    {
        return $this->{$this->method}();
    }

    public function login()
    {
        return $this->resource;
    }

    public function logout()
    {
        return [
            'data' => null,
        ];
    }

    public function register()
    {
        return $this->resource;
    }

    public function sendResetTokenEmail()
    {
        return [
            'data' => (bool) $this->resource,
        ];
    }

    public function resetPassword()
    {
        return [
            'data' => (bool) $this->resource,
        ];
    }

    public function refreshToken()
    {
        return $this->resource;
    }
}
