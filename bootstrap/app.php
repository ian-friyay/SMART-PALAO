<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Disable CSRF for ESP32 telemetry endpoint so hardware can post lightweight JSON
        $middleware->validateCsrfTokens(except: [
            'api/telemetry/push',
            'telemetry/push',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
