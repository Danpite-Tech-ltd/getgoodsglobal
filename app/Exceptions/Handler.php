<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use RuntimeException;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated',
            'data' => []
        ], 401);
    }
    public function render($request, Throwable $exception)
    {
        // Always return JSON for API
        if ($request->is('api/*')) {

            // 404 for wrong slug, wrong URL, wrong route
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Route or resource not found',
                ], 404);
            }

            // When Model::findOrFail / firstOrFail fails
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data not found',
                ], 404);
            }

            // Wrong HTTP method (e.g. POST instead of GET)
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid request method',
                ], 405);
            }

            // For any other error
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }

        return parent::render($request, $exception);
    }


}
