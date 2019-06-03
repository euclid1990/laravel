<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

abstract class ApiResource extends JsonResource
{
    protected $message;

    public function __construct($resource, $message = null)
    {
        parent::__construct($resource);
        $this->message = $message;
    }

    public function with($request)
    {
        return [
            'message' => $this->message ? [$this->message] : [],
            'code' => Response::HTTP_OK,
        ];
    }
}
