<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use stdClass;

abstract class ApiCollection extends ResourceCollection
{
    protected $message;

    public function __construct(object $resource, string $message = null)
    {
        $this->message = $message;

        parent::__construct($resource);
    }

    public function with($request, object $errors = null)
    {
        if ($errors === null) {
            $errors = new stdClass;
        }

        return [
            'message' => $this->message,
            'code' => Response::HTTP_OK,
            'errors' => $errors,
        ];
    }
}
