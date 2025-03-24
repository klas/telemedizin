<?php

namespace App\Exceptions;
use hamidreza2005\LaravelApiErrorHandler\Traits\ApiErrorHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiErrorHandler;

    /**
     * A list of the exception types that are not reported.
     *
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $e)
    {
        return $this->handle($this->prepareException($e));
    }
}
