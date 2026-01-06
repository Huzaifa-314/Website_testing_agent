<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('websites.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('websites', \App\Http\Controllers\WebsiteController::class);
    Route::resource('test-definitions', \App\Http\Controllers\TestDefinitionController::class);
    Route::post('/test-definitions/{testDefinition}/run', [\App\Http\Controllers\TestDefinitionController::class, 'run'])->name('test-definitions.run');
    Route::get('/test-definitions/{testDefinition}/execute/{testRun}', [\App\Http\Controllers\TestDefinitionController::class, 'execute'])->name('test-definitions.execute');
    Route::get('/test-definitions/{testDefinition}/progress/{testRun}', [\App\Http\Controllers\TestDefinitionController::class, 'progress'])->name('test-definitions.progress');
    
    Route::get('/websites/{website}/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/test-runs/{testRun}', [\App\Http\Controllers\ReportController::class, 'show'])->name('reports.show');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        
        // User Management Routes
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\AdminController::class, 'userCreate'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'userStore'])->name('users.store');
        Route::get('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userShow'])->name('users.show');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'userEdit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userUpdate'])->name('users.update');
        Route::patch('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userDestroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';
