<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'partenaire' => \App\Http\Middleware\PartenaireMiddleware::class,
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
