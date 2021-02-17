<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
    }

    public function render($request, Throwable $exception)
    {
        if($exception instanceof ValidationException) {
            if($request->expectsJson()) {
                return response('Sorry, validation failed', 422);
            }
        }

        if($exception instanceof ThrottleRequestsException) {
            if($request->expectsJson()) {
                return response('You are posting too frequently. Please take a break. :)', 429);
            }
        }

        return parent::render($request, $exception);
    }
}
