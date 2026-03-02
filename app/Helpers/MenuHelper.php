<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class MenuHelper
{
    /**
     * Get menus by role with caching
     */
    public static function getMenusByRole($role = null)
    {
        $role = $role ?? auth()->user()->role ?? 'guest';
        
        return Cache::remember("menus_role_{$role}", 3600, function () use ($role) {
            return Menu::active()
                ->byRole($role)
                ->parents()
                ->orderBy('order')
                ->with(['children' => function($query) use ($role) {
                    $query->active()
                        ->byRole($role)
                        ->orderBy('order');
                }])
                ->get();
        });
    }

    /**
     * Build menu HTML
     */
    public static function buildMenu($menus, $currentRoute = null)
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
                $html .= '<i class="' . $menu->icon . '" style="font-size: 26px;"></i>';
                $html .= '<span>' . $menu->name . '</span>';
                $html .= '</a>';
                $html .= '<ul class="mm-collapse">';
                
                foreach ($menu->children as $child) {
                    $childActive = $child->route === $currentRoute ? 'active' : '';
                    $url = self::getMenuUrl($child);
                    
                    $html .= '<li class="' . $childActive . '">';
                    $html .= '<a href="' . $url . '">';
                    $html .= '<i class="' . $child->icon . '"></i> ';
                    $html .= $child->name;
                    $html .= '</a>';
                    $html .= '</li>';
                }
                
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $active = $menu->route === $currentRoute ? 'mm-active' : '';
                $url = self::getMenuUrl($menu);
                
                $html .= '<li class="' . $active . '">';
                $html .= '<a href="' . $url . '">';
                $html .= '<i class="' . $menu->icon . '" style="font-size: 26px;"></i>';
                $html .= '<span>' . $menu->name . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        
        return $html;
    }

    /**
     * Get menu URL safely
     */
    public static function getMenuUrl($menu)
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