<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Append security headers to API responses
        $middleware->appendToGroup('api', [\App\Http\Middleware\SecurityHeaders::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $render = static function (string $message, int $status = 500, array $errors = null) {
            $payload = [
                'status'  => 'error',
                'message' => $message,
            ];
            if ($errors) {
                $payload['errors'] = $errors;
            }
            return response()->json($payload, $status);
        };

        // Validation errors
        $exceptions->renderable(function (ValidationException $e, $request) use ($render) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $render('Validation failed', 422, $e->errors());
            }
        });

        // Not found (invalid route/model)
        $exceptions->renderable(function (NotFoundHttpException $e, $request) use ($render) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $render('Resource not found', 404);
            }
        });

        // Wrong HTTP verb
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) use ($render) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $render('Method not allowed', 405);
            }
        });

        // Unauthenticated
        $exceptions->renderable(function (AuthenticationException $e, $request) use ($render) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $render('Unauthenticated', 401);
            }
        });

        // Forbidden
        $exceptions->renderable(function (AuthorizationException $e, $request) use ($render) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $render('Forbidden', 403);
            }
        });

        // Fallback – any other uncaught exception
        $exceptions->renderable(function (Throwable $e, $request) use ($render) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $render('Server error', 500);
            }
        });
    })->create();
