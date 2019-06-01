<?php

namespace App\Http\Resources;

class UserResource extends ApiResource
{
    protected $method;

    public function __construct($resource, $method = 'show', $message = null)
    {
        $this->method = $method;
        parent::__construct($resource, $message);
    }

    public function toArray(Request $request)
    {
        return $this->{$this->method}();
    }

    public function show()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->toDateTimeString(),      // phpcs:ignore Squiz.NamingConventions.ValidVariableName
            'updated_at' => $this->updated_at->toDateTimeString(),      // phpcs:ignore Squiz.NamingConventions.ValidVariableName
        ];
    }
}
