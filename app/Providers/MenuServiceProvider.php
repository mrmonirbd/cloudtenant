<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            // Using view composer to share menus with sidebar
            View::composer('partials.sidebara', function ($view) {
                
                // Get menus for authenticated user
                if (auth()->check()) {
                    $role = auth()->user()->role ?? 'user';
                    
                    // Try to get from cache first
                    $menus = Cache::remember("sidebar_menus_{$role}", 3600, function () use ($role) {
                        return Menu::active()
                            ->byRole($role)
                            ->whereNull('parent_id')
                            ->orderBy('order')
                            ->with(['children' => function($query) {
                                $query->active()->orderBy('order');
                            }])
                            ->get();
                    });
                    
                    // Get headers if any
                    $headers = Cache::remember("sidebar_headers_{$role}", 3600, function () use ($role) {
                        return Menu::active()
                            ->byRole($role)
                            ->where('section', 'header')
                            ->orderBy('order')
                            ->get();
                    });
                    
                } else {
                    // For guests, show only public menus
                    $menus = Cache::remember("sidebar_menus_guest", 3600, function () {
                        return Menu::active()
                            ->byRole('guest')
                            ->whereNull('parent_id')
                            ->orderBy('order')
                            ->with(['children' => function($query) {
                                $query->active()->orderBy('order');
                            }])
                            ->get();
                    });
                    
                    $headers = collect([]);
                }
                
                // Share menus with the view
                $view->with('dynamicMenus', $menus ?? collect([]))
                     ->with('menuHeaders', $headers ?? collect([]));
            });
            
        } catch (\Exception $e) {
            // Log error but don't break the application
            Log::error('MenuServiceProvider Error: ' . $e->getMessage());
            
            // Share empty collections
            View::composer('partials.sidebar', function ($view) {
                $view->with('dynamicMenus', collect([]))
                     ->with('menuHeaders', collect([]));
            });
        }
    }
}