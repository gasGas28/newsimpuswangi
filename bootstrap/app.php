<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
// ⬇️ import middleware yang kamu punya

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\Auth\CheckRole;
use App\Http\Middleware\VerifyRecaptcha;
use Inertia\Inertia;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ⬇️ PENTING: tempelkan Inertia middleware ke group "web"
        $middleware->appendToGroup('web', [
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
        $exceptions->report(function (TokenMismatchException $e) {
            $req = request();
            $short = fn($v) => $v ? Str::limit($v, 16, '…') : null;

            Log::warning('CSRF mismatch detected', [
                'path'            => $req?->path(),
                'method'          => $req?->method(),
                'origin'          => $req?->headers->get('Origin'),
                'referer'         => $req?->headers->get('Referer'),
                'host'            => $req?->getHost(),
                'session_id'      => $req?->session()?->getId(),
                'has_cookie_xsrf' => $req?->cookies->has('XSRF-TOKEN'),
                'has_cookie_sess' => $req?->cookies->has(config('session.cookie')),
                'token_session'   => $short($req?->session()?->token()),
                'token_input'     => $short($req?->input('_token')),
                'token_header'    => $short($req?->header('X-CSRF-TOKEN')),
                'token_x_xsrf'    => $short($req?->header('X-XSRF-TOKEN')),
                'session_driver'  => config('session.driver'),
                'app_url'         => config('app.url'),
                'session_domain'  => config('session.domain'),
                'secure_cookie'   => config('session.secure'),
                'samesite'        => config('session.same_site'),
            ]);

                \Log::debug('EXCEPTION-CATCHALL', [
        'type' => get_class($e),
        'msg'  => $e->getMessage(),
    ]);
        });

        // (opsional) render JSON untuk SPA:
        // $exceptions->render(function (TokenMismatchException $e, $request) {
        //     if ($request->expectsJson()) {
        //         return response()->json(['message' => 'CSRF token mismatch'], 419);
        //     }
        // });

        // ⬇️ BARU: render error 403/404/419/500/503 lewat Inertia (Error.vue),
        // bukan HTML mentah -- ini yang tadinya bikin muncul "modal" aneh
        // saat CheckRole memanggil abort(403).
        $exceptions->respond(function ($response, \Throwable $e, $request) {
            if (
                !app()->environment(['local', 'testing'])
                && in_array($response->getStatusCode(), [403, 404, 419, 500, 503])
            ) {
                return Inertia::render('Error', [
                    'status'  => $response->getStatusCode(),
                    'message' => $e->getMessage() ?: null,
                ])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            }

            return $response;
        });
    })
    ->create();
