<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Exceptions\PickupSchedulingException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        $middleware->append(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle PickupSchedulingException
        $exceptions->renderable(function (PickupSchedulingException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        });

        // Catch-all: prevent HTML errors from leaking to API
        $exceptions->renderable(function (\Throwable $e) {
            if (request()->expectsJson()) {
                $statusCode = method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : 500;

                return response()->json([
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'Server error',
                ], $statusCode);
            }
        });
    })->create();
