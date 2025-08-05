<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\User\RegistrationController;
use App\Http\Controllers\User\RegistrationFormController;
use App\Http\Controllers\User\SelectionResultController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;

Route::get('/', [AuthController::class, 'showLoginForm']);

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// User Routes
Route::middleware(['auth', 'user'])->group(function () {
    // Registration Routes
    Route::get('/registration', [RegistrationController::class, 'index'])->name('registration.index');
    Route::post('/registration/create', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('/registration/upload-admin-payment', [RegistrationController::class, 'uploadAdminPayment'])->name('registration.upload-admin-payment');
    Route::post('/registration/upload-registration-payment', [RegistrationController::class, 'uploadRegistrationPayment'])->name('registration.upload-registration-payment');
    Route::post('/registration/upload-payment', [RegistrationController::class, 'uploadPayment'])->name('registration.upload-payment');
    Route::delete('/registration/{id}/cancel', [RegistrationController::class, 'cancelRegistration'])->name('registration.cancel');

    // Registration Form Routes
    Route::prefix('registration')->name('registration.')->group(function () {
        Route::get('/form', [RegistrationFormController::class, 'index'])->name('form');
        Route::post('/form/save', [RegistrationFormController::class, 'saveForm'])->name('form.save');
        Route::post('/form/upload-document', [RegistrationFormController::class, 'uploadDocument'])->name('form.upload-document');
        Route::post('/form/submit', [RegistrationFormController::class, 'submitRegistration'])->name('form.submit');
    });

    // Selection Result Routes
    Route::prefix('selection-result')->name('selection-result.')->group(function () {
        Route::get('/', [SelectionResultController::class, 'index'])->name('index');
        Route::post('/upload', [SelectionResultController::class, 'uploadPayment'])->name('upload');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Settings
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

    // Payment Verification Routes - CLEAN & SIMPLE untuk Alpine.js
    Route::prefix('payments')->name('payments.')->group(function () {
        // Main pages
        Route::get('/', [PaymentVerificationController::class, 'index'])->name('index');
        Route::get('/{id}', [PaymentVerificationController::class, 'show'])->name('show');
        Route::get('/download/{id}', [PaymentVerificationController::class, 'downloadFile'])->name('download');
    });

    // Payment API Routes - UNTUK ALPINE.JS
    Route::prefix('api/payments')->name('api.payments.')->group(function () {
        Route::get('/list', [PaymentVerificationController::class, 'apiPayments'])->name('list');
        Route::get('/stats', [PaymentVerificationController::class, 'apiStats'])->name('stats');
        Route::post('/{id}/approve', [PaymentVerificationController::class, 'apiApprove'])->name('approve');
        Route::post('/{id}/reject', [PaymentVerificationController::class, 'apiReject'])->name('reject');
        Route::post('/bulk-approve', [PaymentVerificationController::class, 'apiBulkApprove'])->name('bulk-approve');
        Route::post('/export', [PaymentVerificationController::class, 'apiExport'])->name('export');
    });

    // Registration Management
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminRegistrationController::class, 'updateStatus'])->name('update-status');
        Route::put('/{id}/data', [AdminRegistrationController::class, 'updateData'])->name('update-data');
        Route::delete('/{id}', [AdminRegistrationController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [AdminRegistrationController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/excel', [AdminRegistrationController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf', [AdminRegistrationController::class, 'exportPdf'])->name('export-pdf');
    });
});