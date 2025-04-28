<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Services\FideliteService;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\CartSessionMiddleware::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(function (FideliteService $fideliteService) {
            $fideliteService->verifierEtEnvoyerCodesPromo();
        })
        ->cron('11 22 * * 0') 
        ->timezone('Africa/Casablanca')
        ->name('envoyer-codes-fidelite')
        ->withoutOverlapping();
    }) 
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    

