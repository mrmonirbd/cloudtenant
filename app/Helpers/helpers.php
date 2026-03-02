<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

if (!function_exists('getMenusByRole')) {
    /**
     * Get menus by role with caching
     */
    function getMenusByRole($role = null)
    {
        $role = $role ?? auth()->user()->role ?? 'guest';

        // dd(auth()->user());
        
        return Cache::remember("menus_role_{$role}", 3600, function () use ($role) {
            return Menu::active()
                ->byRole($role)
                ->whereNull('parent_id')  
                ->orderBy('order')
                ->with(['children' => function($query) use ($role) {
                    $query->active()
                        ->byRole($role)
                        ->orderBy('order');
                }])
                ->get();
        });
    }
}

if (!function_exists('buildMenu')) {
    /**
     * Build menu HTML
     */
    function buildMenu($menus, $currentRoute = null)
    {
        $html = '';
        $currentRoute = $currentRoute ?? request()->route()?->getName();
        
        foreach ($menus as $menu) {
            if ($menu->children->count() > 0) {
                $active = $menu->children->contains(function($child) use ($currentRoute) {
                    return $child->route === $currentRoute;
                }) ? 'mm-active' : '';
                
                $html .= '<li class="mm-dropdown ' . $active . '">';
                $html .= '<a class="has-arrow" href="#" aria-expanded="false">';
                $html .= getMenuIcon($menu);
                $html .= '<span>' . e($menu->name) . '</span>';
                $html .= '</a>';
                $html .= '<ul class="mm-collapse">';
                
                foreach ($menu->children as $child) {
                    $childActive = $child->route === $currentRoute ? 'active' : '';
                    $url = getMenuUrl($child);
                    
                    $html .= '<li class="' . $childActive . '">';
                    $html .= '<a href="' . $url . '">';
                    $html .= getMenuIcon($child, true);
                    $html .= e($child->name);
                    $html .= '</a>';
                    $html .= '</li>';
                }
                
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $active = $menu->route === $currentRoute ? 'mm-active' : '';
                $url = getMenuUrl($menu);
                
                $html .= '<li class="' . $active . '">';
                $html .= '<a href="' . $url . '">';
                $html .= getMenuIcon($menu);
                $html .= '<span>' . e($menu->name) . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        
        return $html;
    }
}

if (!function_exists('getMenuIcon')) {
    /**
     * Get menu icon HTML
     */
    function getMenuIcon($menu, $isChild = false)
    {
        if (!$menu->icon) {
            return '';
        }
        
        if ($isChild) {
            return '<i class="' . e($menu->icon) . '"></i> ';
        }
        
        return '<i class="' . e($menu->icon) . '" style="font-size: 26px;"></i> ';
    }
}

if (!function_exists('getMenuUrl')) {
    /**
     * Get menu URL safely
     */
    function getMenuUrl($menu)
    {
        if ($menu->route && Route::has($menu->route)) {
            try {
                return route($menu->route);
            } catch (\Exception $e) {
                return '#';
            }
        }
        
        return $menu->url ?? '#';
    }
}

if (!function_exists('clearMenuCache')) {
    /**
     * Clear menu cache
     */
    function clearMenuCache()
    {
        $roles = ['admin', 'user', 'both', 'guest'];
        
        foreach ($roles as $role) {
            Cache::forget("menus_role_{$role}");
        }
    }
}