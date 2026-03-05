<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (Protected)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'user.active'])->name('dashboard');

// Authenticated Routes (Active User Only)
Route::middleware(['auth', 'user.active'])->group(function () {
    
    // ==================== PROFILE ROUTES ====================
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
    
    // ==================== USER MANAGEMENT ROUTES ====================
Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
    // Export/Import
    Route::get('/export/csv', 'exportCsv')->name('export.csv');        // users.export.csv
    Route::get('/export/excel', 'exportExcel')->name('export.excel');  // users.export.excel
    Route::post('/import', 'import')->name('import');                  // users.import
    
    // Search/Filter
    Route::get('/search', 'search')->name('search');                    // users.search
    Route::get('/filter/{status}', 'filterByStatus')->name('filter');   // users.filter
    
    // Email Check
    Route::post('/check-email', 'checkEmail')->name('check-email');     // users.check-email
    
    // Bulk Actions
    Route::post('/bulk-approve', 'bulkApprove')->name('bulk-approve');  // users.bulk-approve
    Route::post('/bulk-delete', 'bulkDelete')->name('bulk-delete');     // users.bulk-delete
    
    // CRUD Operations
    Route::get('/', 'index')->name('index');                // users.index (index page)
    Route::get('/create', 'create')->name('create');        // users.create
    Route::post('/', 'store')->name('store');               // users.store (POST)
    Route::get('/{user}', 'index')->name('show');            // users.show
    Route::get('/{user}/edit', 'edit')->name('edit');       // users.edit
    Route::put('/{user}', 'update')->name('update');        // users.update
    Route::patch('/{user}', 'update');                      // users.update (PATCH)
    Route::delete('/{user}', 'destroy')->name('destroy');   // users.destroy
    
    // Status Operations
    Route::patch('/{user}/approve', 'approve')->name('approve');    // users.approve
    Route::patch('/{user}/reject', 'reject')->name('reject');       // users.reject
    Route::patch('/{user}/status', 'updateStatus')->name('status'); // users.status
});
    // ==================== COMPANY ROUTES ====================
    Route::resource('companies', CompanyController::class);
    
    // ==================== TEAM ROUTES ====================
    Route::prefix('team')->name('team.')->controller(TeamController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{team}', 'show')->name('show');
        Route::get('/{team}/edit', 'edit')->name('edit');
        Route::put('/{team}', 'update')->name('update');
        Route::delete('/{team}', 'destroy')->name('destroy');
    });
    
    // ==================== PROJECT ROUTES ====================
    Route::resource('projects', ProjectController::class);
    
    // ==================== INVOICE ROUTES ====================
    Route::resource('invoices', InvoiceController::class);
    
    // ==================== SUBSCRIPTION ROUTES ====================
    Route::prefix('subscription')->name('subscription.')->controller(SubscriptionController::class)->group(function () {
        Route::get('/upgrade', 'upgrade')->name('upgrade');
        Route::get('/history', 'history')->name('history');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/resume', 'resume')->name('resume');
    });
    
    // ==================== REPORT ROUTES ====================
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        // Admin Reports
        Route::get('/usage', 'usage')->name('usage');
        Route::get('/revenue', 'revenue')->name('revenue');
        Route::get('/activity', 'activity')->name('activity');
        
        // User Reports
        Route::get('/user-usage', 'userUsage')->name('user-usage');
        Route::get('/user-activity', 'userActivity')->name('user-activity');
    });
    
    // ==================== SETTINGS ROUTES ====================
    Route::prefix('settings')->name('settings.')->controller(SettingController::class)->group(function () {
        // Admin Settings
        Route::get('/general', 'general')->name('general');
        Route::post('/general', 'updateGeneral')->name('general.update');
        
        Route::get('/notifications', 'notifications')->name('notifications');
        Route::post('/notifications', 'updateNotifications')->name('notifications.update');
        
        Route::get('/payment', 'payment')->name('payment');
        Route::post('/payment', 'updatePayment')->name('payment.update');
        
        // User Settings
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'updateProfile')->name('profile.update');
    });
    
    // ==================== ROLE & PERMISSION ROUTES ====================
    Route::resource('roles', RoleController::class);
    
    // ==================== PLAN ROUTES ====================
    Route::resource('plans', PlanController::class);
    
    // ==================== TRANSACTION ROUTES ====================
    Route::resource('transactions', TransactionController::class);
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