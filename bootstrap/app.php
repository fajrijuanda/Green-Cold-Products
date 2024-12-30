<?php

use App\Http\Middleware\CheckAdminRole;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\CustomErrorHandling;
use Illuminate\Foundation\Application;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\SessionTimeoutRedirect;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'session.timeout' => SessionTimeoutRedirect::class,
            'custom.error' => CustomErrorHandling::class,
        ]);

        $middleware->web([
            LocaleMiddleware::class,
            'session.timeout',
            'custom.error',
        ]);
    })
    ->withExceptions(function ($exceptions) {
        $exceptions->renderable(function (\Illuminate\Http\Exceptions\HttpResponseException $exception) {
            $statusCode = $exception->getStatusCode();

            if ($statusCode === 404) {
                return response()->view('errors.404', [], 404);
            } elseif ($statusCode === 403) {
                return response()->view('errors.403', [], 403);
            } elseif ($statusCode === 503) {
                return response()->view('errors.503', [], 503);
            }

            return response()->view('errors.generic', [], $statusCode);
        });
    })
    ->create();
