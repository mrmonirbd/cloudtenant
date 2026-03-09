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
        $role = $role ?? (auth()->check() ? auth()->user()->role : 'guest');
        if($role =='owner'){
            $menus = menu::get();
        } else {
            $menus = auth()->user()->menus();
        }
        // dd(auth()->user());
        return $menus;
        
        
    }
}

    /**
     * Build menu HTML
     */

    function getmenu(){
        $role = auth()->user()->role;
        if($role =='owner'){
             $menus = menu::get();
        } else {
        $menus = auth()->user()->menus()->get();
        }
        return $menus;
    }



 function renderMenu($menus)
    {
        $html = '';
        $menus = auth()->user()->menus();
        dd($menus);

        foreach ($menus as $menu) {
            if ($menu->section == 'header') {
                $html .= '<li class="menu-header">' . $menu->header_text . '</li>';
            } else {
                $hasChildren = $menu->children->count() > 0;
                $html .= '<li class="' . ($hasChildren ? 'has-submenu' : '') . '">';
                $html .= '<a href="' . ($menu->route ? route($menu->route) : '#') . '">';
                if ($menu->icon) {
                    $html .= '<i class="' . $menu->icon . '"></i>';
                }
                $html .= '<span>' . $menu->name . '</span>';
                if ($hasChildren) {
                    $html .= '<span class="arrow"></span>';
                }
                $html .= '</a>';

                if ($hasChildren) {
                    $html .= '<ul>';
                    $html .= renderMenu($menu->children); // recursion for submenus
                    $html .= '</ul>';
                }

                $html .= '</li>';
            }
        }

        return $html;
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