<?php
use app\Http\Middleware\Admin;
use app\Http\Middleware\Vendor;
use app\Http\Middleware\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // filtragem de roles
        $middleware->alias([
            'admin' => Admin::class,
            'vendor' => Vendor::class,
            'user' => User::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();