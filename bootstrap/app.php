<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'isAdmin' => IsAdmin::class,
            'isUser'  => IsUser::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
            '/biteship/webhook',
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: function (Request $request) {
                if ($request->user() && $request->user()->isAdmin) {
                    return route('admin.produk.index');
                }
                return route('agen.profile');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
