<?php

namespace App\Http;

use App\Http\Middleware\IsAdmin;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // ...existing middleware...
        'is.admin' => \App\Http\Middleware\IsAdmin::class,
    ];

    protected $middlewareAliases = [
        // ... other middleware ...
        'is.admin' => \App\Http\Middleware\IsAdmin::class,
    ];

    /**
     * The application's route middleware.
     *
     * These can be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ...existing middleware...
        'is.admin' => \App\Http\Middleware\IsAdmin::class,
    ];
}