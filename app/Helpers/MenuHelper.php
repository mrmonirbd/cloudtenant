<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuHelper
{
    /**
     * Get menus for specific user based on permissions
     */
    public static function getUserMenus($userId)
    {
        // Check if table exists first
        if (!Schema::hasTable('user_menu_permissions')) {
            // If table doesn't exist, return all active menus (for development)
            return Menu::active()
                ->whereNull('parent_id')  // parents() এর পরিবর্তে whereNull
                ->orderBy('order')
                ->with(['children' => function($query) {
                    $query->active()->orderBy('order');
                }])
                ->get();
        }
        
        return Cache::remember("user_menus_{$userId}", 3600, function () use ($userId) {
            try {
                $menuIds = DB::table('user_menu_permissions')
                    ->where('user_id', $userId)
                    ->where('can_view', true)
                    ->pluck('menu_id')
                    ->toArray();

                // If no permissions found, return empty collection
                if (empty($menuIds)) {
                    return collect([]);
                }

                return Menu::active()
                    ->whereNull('parent_id')  // parents() এর পরিবর্তে whereNull
                    ->whereIn('id', $menuIds)
                    ->orderBy('order')
                    ->with(['children' => function($query) use ($menuIds) {
                        $query->active()
                            ->whereIn('id', $menuIds)
                            ->orderBy('order');
                    }])
                    ->get();
                    
            } catch (\Exception $e) {
                // Log error and return empty collection
                \Log::error('MenuHelper error: ' . $e->getMessage());
                return collect([]);
            }
        });
    }

    /**
     * Build menu HTML
     */
    function buildMenu($menus, $currentRoute = null)
    {
        if ($menus->isEmpty()) {
            return '<li class="text-center text-muted py-3">No menus available</li>';
        }
        
        $html = '';
        $currentRoute = $currentRoute ?? request()->route()?->getName();
        
        foreach ($menus as $menu) {
            if ($menu->children->count() > 0) {
                $active = $menu->children->contains(function($child) use ($currentRoute) {
                    return $child->route === $currentRoute;
                }) ? 'mm-active' : '';
                
                $html .= '<li class="mm-dropdown ' . $active . '">';
                $html .= '<a class="has-arrow" href="#" aria-expanded="false">';
                $html .= '<i class="' . e($menu->icon) . '" style="font-size: 26px;"></i>';
                $html .= '<span>' . e($menu->name) . '</span>';
                $html .= '</a>';
                $html .= '<ul class="mm-collapse">';
                
                foreach ($menu->children as $child) {
                    $childActive = $child->route === $currentRoute ? 'active' : '';
                    $url = getMenuUrl($child);
                    
                    $html .= '<li class="' . $childActive . '">';
                    $html .= '<a href="' . $url . '">';
                    $html .= '<i class="' . e($child->icon) . '"></i> ';
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
                $html .= '<i class="' . e($menu->icon) . '" style="font-size: 26px;"></i>';
                $html .= '<span>' . e($menu->name) . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        
        return $html;
    }

    /**
     * Get menu URL safely
     */
    function getMenuUrl($menu)
    {
        return url($menu->route);
    }

    /**
     * Clear user menu cache
     */
    public static function clearUserCache($userId)
    {
        Cache::forget("user_menus_{$userId}");
    }
}