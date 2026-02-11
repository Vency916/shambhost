<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Panel Routes
Route::middleware(['auth'])->prefix('panel')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // /panel maps to dashboard
    Route::post('/sync', [DashboardController::class, 'sync'])->name('dashboard.sync');
    Route::post('/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');

    // Deployment Details
    Route::get('/deployments/{id}/logs', [DashboardController::class, 'logs'])->name('deployments.logs');
    
    // Status Polling (JSON)
    Route::get('/deployments/status', [DashboardController::class, 'status'])->name('deployments.status');
});
