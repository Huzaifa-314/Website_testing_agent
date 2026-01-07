<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('websites.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Regular user routes - admins are redirected to admin dashboard
    Route::middleware('not.admin')->group(function () {
        Route::resource('websites', \App\Http\Controllers\WebsiteController::class);
        Route::resource('test-definitions', \App\Http\Controllers\TestDefinitionController::class);
        Route::post('/test-definitions/{testDefinition}/run', [\App\Http\Controllers\TestDefinitionController::class, 'run'])->name('test-definitions.run');
        Route::get('/test-definitions/{testDefinition}/execute/{testRun}', [\App\Http\Controllers\TestDefinitionController::class, 'execute'])->name('test-definitions.execute');
        Route::get('/test-definitions/{testDefinition}/progress/{testRun}', [\App\Http\Controllers\TestDefinitionController::class, 'progress'])->name('test-definitions.progress');
        
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'dashboard'])->name('reports.dashboard');
        Route::get('/websites/{website}/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/test-runs/{testRun}', [\App\Http\Controllers\ReportController::class, 'show'])->name('reports.show');
        Route::get('/test-runs/{testRun}/export/json', [\App\Http\Controllers\ReportController::class, 'exportJson'])->name('reports.export.json');
        Route::get('/test-runs/{testRun}/export/csv', [\App\Http\Controllers\ReportController::class, 'exportCsv'])->name('reports.export.csv');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [\App\Http\Controllers\AdminController::class, 'exportDashboard'])->name('dashboard.export');
        
        // User Management Routes
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\AdminController::class, 'userCreate'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'userStore'])->name('users.store');
        Route::get('/users/export', [\App\Http\Controllers\AdminController::class, 'usersExport'])->name('users.export');
        Route::post('/users/bulk-activate', [\App\Http\Controllers\AdminController::class, 'usersBulkActivate'])->name('users.bulk-activate');
        Route::post('/users/bulk-deactivate', [\App\Http\Controllers\AdminController::class, 'usersBulkDeactivate'])->name('users.bulk-deactivate');
        Route::post('/users/bulk-delete', [\App\Http\Controllers\AdminController::class, 'usersBulkDelete'])->name('users.bulk-delete');
        Route::post('/users/bulk-change-role', [\App\Http\Controllers\AdminController::class, 'usersBulkChangeRole'])->name('users.bulk-change-role');
        Route::get('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userShow'])->name('users.show');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'userEdit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userUpdate'])->name('users.update');
        Route::patch('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userDestroy'])->name('users.destroy');
        
        // Activity Logs
        Route::get('/activity-logs', [\App\Http\Controllers\AdminController::class, 'activityLogs'])->name('activity-logs');
        
        // System Settings
        Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
        
        // Email Settings
        Route::get('/email-settings', [\App\Http\Controllers\AdminController::class, 'emailSettings'])->name('email-settings');
        Route::put('/email-settings', [\App\Http\Controllers\AdminController::class, 'updateEmailSettings'])->name('email-settings.update');
        
        // Websites Management
        Route::get('/websites', [\App\Http\Controllers\AdminController::class, 'websites'])->name('websites.index');
        Route::get('/websites/{website}', [\App\Http\Controllers\AdminController::class, 'websiteShow'])->name('websites.show');
        Route::delete('/websites/{website}', [\App\Http\Controllers\AdminController::class, 'websiteDestroy'])->name('websites.destroy');
        Route::post('/websites/bulk-delete', [\App\Http\Controllers\AdminController::class, 'websitesBulkDelete'])->name('websites.bulk-delete');
        
        // Test Definitions Management
        Route::get('/test-definitions', [\App\Http\Controllers\AdminController::class, 'testDefinitions'])->name('test-definitions.index');
        Route::get('/test-definitions/{testDefinition}', [\App\Http\Controllers\AdminController::class, 'testDefinitionShow'])->name('test-definitions.show');
        Route::delete('/test-definitions/{testDefinition}', [\App\Http\Controllers\AdminController::class, 'testDefinitionDestroy'])->name('test-definitions.destroy');
        Route::post('/test-definitions/bulk-delete', [\App\Http\Controllers\AdminController::class, 'testDefinitionsBulkDelete'])->name('test-definitions.bulk-delete');
        
        // Test Runs Management
        Route::get('/test-runs', [\App\Http\Controllers\AdminController::class, 'testRuns'])->name('test-runs.index');
        Route::get('/test-runs/{testRun}', [\App\Http\Controllers\AdminController::class, 'testRunShow'])->name('test-runs.show');
        Route::delete('/test-runs/{testRun}', [\App\Http\Controllers\AdminController::class, 'testRunDestroy'])->name('test-runs.destroy');
        Route::post('/test-runs/bulk-delete', [\App\Http\Controllers\AdminController::class, 'testRunsBulkDelete'])->name('test-runs.bulk-delete');
        
        // Reports Management
        Route::get('/reports', [\App\Http\Controllers\AdminController::class, 'reports'])->name('reports.index');
        Route::get('/reports/{report}', [\App\Http\Controllers\AdminController::class, 'reportShow'])->name('reports.show');
        Route::delete('/reports/{report}', [\App\Http\Controllers\AdminController::class, 'reportDestroy'])->name('reports.destroy');
        Route::post('/reports/bulk-delete', [\App\Http\Controllers\AdminController::class, 'reportsBulkDelete'])->name('reports.bulk-delete');
        Route::get('/reports/{report}/export/json', [\App\Http\Controllers\AdminController::class, 'reportExportJson'])->name('reports.export.json');
        Route::get('/reports/{report}/export/csv', [\App\Http\Controllers\AdminController::class, 'reportExportCsv'])->name('reports.export.csv');
    });
});

require __DIR__.'/auth.php';
