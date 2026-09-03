<?php

use App\Http\Middleware\AutenticarJwtApi;
use App\Http\Middleware\SeguridadApi;
use App\Http\Middleware\VerificarPropietarioCarrito;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('api', SeguridadApi::class);

        $middleware->alias([
            'autenticar.jwt' => AutenticarJwtApi::class,
            'jwt.cart.owner' => VerificarPropietarioCarrito::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );
    })->create();
