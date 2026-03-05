<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch menus with children (assuming user has many menus)
        $menus = $user->menus()->with('children')->get();

        // Return the view with menus
        return view('dashboard', compact('menus'));
    }
}
