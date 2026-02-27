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
        
        // Middleware aliases - একবারেই সঠিকভাবে define করুন
        $middleware->alias([
            // User Status Middleware
            'user.active' => \App\Http\Middleware\CheckUserStatus::class,
            'check.status' => \App\Http\Middleware\CheckUserStatus::class,
            
            // Role Middleware
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'editor' => \App\Http\Middleware\EditorMiddleware::class,
            
            // Other middleware
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'user.status' => \App\Http\Middleware\UserStatusMiddleware::class,
        ]);
        
        // Web middleware group এ append করুন (optional)
        $middleware->web(append: [
            // 'user.active', // সব web route এ apply করতে চাইলে
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();