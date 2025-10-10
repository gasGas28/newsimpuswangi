<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ⬇️ import middleware yang kamu punya

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\Auth\CheckRole;
use App\Http\Middleware\VerifyRecaptcha;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ⬇️ PENTING: tempelkan Inertia middleware ke group "web"
        $middleware->web(append: [
            HandleInertiaRequests::class,
            \App\Http\Middleware\BindUserToSession::class,

        ]);

        // ⬇️ alias custom middleware
        $middleware->alias([
            'role' => CheckRole::class,
            'verify.recaptcha' => VerifyRecaptcha::class, // kalau dipakai
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
