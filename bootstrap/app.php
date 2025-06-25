<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AfterLoginRedirect;
use App\Http\Middleware\CreditMiddleware;
use App\Http\Middleware\HeadMiddleware;
use App\Http\Middleware\MarketingMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\SupervisorMiddleware;
use App\Http\Middleware\SurveyorMiddleware;
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
        $middleware->alias([
            'marketingMiddleware' => MarketingMiddleware::class,
            'creditMiddleware' => CreditMiddleware::class,
            'headMiddleware' => HeadMiddleware::class,
            'supervisorMiddleware' => SupervisorMiddleware::class,
            'adminMiddleware' => AdminMiddleware::class,
            'superAdminMiddleware' => SuperAdminMiddleware::class,
            'surveyorMiddleware' => SurveyorMiddleware::class,
            'afterLoginRedirect' => AfterLoginRedirect::class, // Middleware baru
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
