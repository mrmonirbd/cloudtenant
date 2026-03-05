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
   function buildMenu($currentRoute = null)
{
    $html = '';
    $user = auth()->user();

    // Fetch menus based on role
    if ($user->role === 'owner') {
        // Fetch all menus
        $menus = Menu::whereNull('parent_id')->orderBy('order')->with('children')->get();
    } else {
        // Fetch assigned menus for non-owner users
        $menus = $user->menus()->with('children')->get();
    }

    // Set current route if not provided
    $currentRoute = $currentRoute ?? request()->route()?->getName();

    // Loop through each menu
    foreach ($menus as $menu) {
        // Check if the menu has children (submenus)
        if ($menu->children->count() > 0) {
            // Check if any child menu is active
            $active = ($menu->route === $currentRoute || $menu->children->contains(function($child) use ($currentRoute) {
                return $child->route === $currentRoute;
            })) ? 'mm-active' : '';

            // Build the parent menu (with submenus)
            $html .= '<li class="mm-dropdown ' . $active . '">';
            $html .= '<a class="has-arrow" href="#" aria-expanded="false">';
            $html .= getMenuIcon($menu);
            $html .= '<span>' . e($menu->name) . '</span>';
            $html .= '</a>';
            $html .= '<ul class="mm-collapse">';
            
            // Loop through child menus
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

            // Close submenu
            $html .= '</ul>';
            $html .= '</li>';
        } else {
            // No children, just a simple menu item
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