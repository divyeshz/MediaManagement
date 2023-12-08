<?php

namespace App\Exceptions;

use ArgumentCountError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->view('content.pages.pages-misc-error');
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->view('content.pages.pages-misc-error');
        });

        $this->renderable(function (ArgumentCountError $e) {
            return response()->view('content.pages.pages-misc-error');
        });
    }
}
