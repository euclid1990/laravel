<?php

namespace App\Http\Resources;

class ExportResource extends ApiResource
{
    protected $method;

    public function __construct($resource, $method = 'export', $message = null)
    {
        $this->method = $method;
        parent::__construct($resource, $message);
    }

    public function toArray($request)
    {
        return $this->{$this->method}();
    }

    public function export()
    {
        return [
            'data' => $this->resource,
        ];
    }
}
