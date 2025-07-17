<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SettingsController;

Route::get('/', [AuthController::class, 'showLoginForm']);

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes - Simple authentication check
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/configurations', [SettingsController::class, 'updateConfigurations'])->name('settings.configurations');
    Route::put('/settings/waves', [SettingsController::class, 'updateWaves'])->name('settings.waves');
    Route::post('/settings/waves', [SettingsController::class, 'createWave'])->name('settings.waves.create');
    Route::delete('/settings/waves/{id}', [SettingsController::class, 'deleteWave'])->name('settings.waves.delete');
    Route::put('/settings/banks', [SettingsController::class, 'updateBankAccounts'])->name('settings.banks');
    Route::post('/settings/banks', [SettingsController::class, 'createBankAccount'])->name('settings.banks.create');
    Route::delete('/settings/banks/{id}', [SettingsController::class, 'deleteBankAccount'])->name('settings.banks.delete');
    Route::put('/settings/kip', [SettingsController::class, 'updateKipQuotas'])->name('settings.kip');
    Route::post('/settings/kip', [SettingsController::class, 'createKipQuota'])->name('settings.kip.create');
    Route::delete('/settings/kip/{id}', [SettingsController::class, 'deleteKipQuota'])->name('settings.kip.delete');
    Route::put('/settings/registration-paths', [SettingsController::class, 'updateRegistrationPaths'])->name('settings.registration-paths');
    Route::post('/settings/registration-paths', [SettingsController::class, 'createRegistrationPath'])->name('settings.registration-paths.create');
    Route::delete('/settings/registration-paths/{id}', [SettingsController::class, 'deleteRegistrationPath'])->name('settings.registration-paths.delete');
});
