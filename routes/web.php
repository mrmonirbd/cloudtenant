<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
    
    // User Routes
    Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
        
        // Export/Import
        Route::get('/export/csv', 'exportCsv')->name('export.csv');
        Route::get('/export/excel', 'exportExcel')->name('export.excel');
        Route::post('/import', 'import')->name('import');
        
        // Search/Filter
        Route::get('/search', 'search')->name('search');
        Route::get('/filter/{status}', 'filterByStatus')->name('filter');
        
        // Bulk Actions
        Route::post('/bulk-approve', 'bulkApprove')->name('bulk-approve');
        Route::post('/bulk-delete', 'bulkDelete')->name('bulk-delete');
        
        // CRUD Operations
        Route::get('/', 'index')->name('index');
        Route::get('/check-email', 'checkEmail')->name('check-email');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::patch('/{user}', 'update');
        Route::delete('/{user}', 'destroy')->name('destroy');
        
        // Status Operations
        Route::patch('/{user}/approve', 'approve')->name('approve');
        Route::patch('/{user}/reject', 'reject')->name('reject');
        Route::patch('/{user}/status', 'updateStatus')->name('status');
    });
});

// CSRF Token Refresh
Route::get('/csrf-token-refresh', function() {
    return response()->json([
        'csrf_token' => csrf_token(),
        'message' => 'Token refreshed successfully'
    ]);
})->name('csrf.refresh');

// Auth Routes
require __DIR__.'/auth.php';