<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use stdClass;

abstract class ApiResource extends JsonResource
{
    protected $message;
    protected $errors;

    public function __construct($resource, string $message = null, string $errors = null)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->errors = $errors;
    }

    public function with($request)
    {
        $this->message = $this->message ?? '';
        $this->errors = $this->errors ?? new stdClass;

        return [
            'message' => $this->message,
            'errors' => $this->errors,
            'code' => Response::HTTP_OK,
        ];
    }
}
