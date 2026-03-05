<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Menu;

class CheckMenuPermission
{
    public function handle(Request $request, Closure $next, $permission = 'can_view')
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $routeName = $request->route()->getName();
        
        if ($routeName) {
            $menu = Menu::where('route', $routeName)->first();
            
            if ($menu && !auth()->user()->hasMenuPermission($menu->id)) {
                abort(403, 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}