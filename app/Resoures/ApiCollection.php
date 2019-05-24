<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

abstract class ApiCollection extends ResourceCollection
{
    protected $message;

    public function __construct($resource, $message = null)
    {
        $this->message = $message;

        parent::__construct($resource);
    }

    public function with(Request $request): array
    {
        return [
            'message' => $this->message ? [$this->message] : [],
            'code' => Response::HTTP_OK,
        ];
    }
}
