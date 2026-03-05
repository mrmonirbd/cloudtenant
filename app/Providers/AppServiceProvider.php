<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Role check directive
        Blade::if('role', function ($role) {
            if (is_array($role)) {
                return auth()->check() && in_array(auth()->user()->role, $role);
            }
            return auth()->check() && auth()->user()->role === $role;
        });

        // Role hierarchy directive
        Blade::if('rolelevel', function ($minLevel) {
            if (!auth()->check()) {
                return false;
            }
            
            $roleLevels = [
                'owner' => 100,
                'superadmin' => 80,
                'admin' => 60,
                'staff' => 40,
                'user' => 20
            ];
            
            $userLevel = $roleLevels[auth()->user()->role] ?? 0;
            $requiredLevel = $roleLevels[$minLevel] ?? 0;
            
            return $userLevel >= $requiredLevel;
        });
    }
}