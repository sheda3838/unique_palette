<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->use([
            \App\Http\Middleware\TrustProxies::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'artist' => \App\Http\Middleware\ArtistMiddleware::class,
            'buyer' => \App\Http\Middleware\BuyerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /**
         * Handle unauthorized API requests.
         */
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
        });

        /**
         * Securely handle Database Query Exceptions (like SQL Injection attempts or malformed queries).
         * This prevents raw SQL errors or table structures from being exposed to the client.
         */
        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                // Log the actual error for developers (Laravel logs to storage/logs/laravel.log by default)
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request input'
                ], 400); // 400 Bad Request for malformed database queries
            }
        });

        /**
         * Handle Validation Failures for API requests.
         */
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        /**
         * Handle Resource Not Found (ModelNotFoundException).
         */
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found'
                ], 404);
            }
        });

        /**
         * Global Fallback for any other unexpected server errors in the API.
         */
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected server error occurred'
                ], 500);
            }
        });
    })->create();
