<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use stdClass;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof AuthenticationException) {
                return $this->responseException(__('auth.failed'), Response::HTTP_UNAUTHORIZED);
            }

            if ($exception instanceof ValidationException) {
                $errors = $exception->validator->errors()->toArray();
                return $this->responseException(
                    '',
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $errors
                );
            }
        }
        return parent::render($request, $exception);
    }

    protected function responseException($message, $code, $errors = null)
    {
        if (is_null($errors)) {
            $errors = new stdClass();
        }

        return response()->json([
            'code' => $code,
            'data' => null,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
