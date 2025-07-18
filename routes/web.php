<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\User\RegistrationController;
use App\Http\Controllers\User\RegistrationFormController;

Route::get('/', [AuthController::class, 'showLoginForm']);

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::middleware('auth')->get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// User Routes - menggunakan middleware 'user' yang sudah ada
Route::middleware(['auth', 'user'])->group(function () {
    // Registration Routes
    Route::get('/registration', [RegistrationController::class, 'index'])->name('registration.index');
    Route::post('/registration/create', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('/registration/upload-admin-payment', [RegistrationController::class, 'uploadAdminPayment'])->name('registration.upload-admin-payment');
    Route::post('/registration/upload-registration-payment', [RegistrationController::class, 'uploadRegistrationPayment'])->name('registration.upload-registration-payment');
    Route::post('/registration/upload-payment', [RegistrationController::class, 'uploadPayment'])->name('registration.upload-payment');
    Route::delete('/registration/{id}/cancel', [RegistrationController::class, 'cancelRegistration'])->name('registration.cancel');

    // Registration Form Routes (BARU)
    Route::prefix('registration')->name('registration.')->group(function () {
        Route::get('/form', [RegistrationFormController::class, 'index'])->name('form');
        Route::post('/form/save', [RegistrationFormController::class, 'saveForm'])->name('form.save');
        Route::post('/form/upload-document', [RegistrationFormController::class, 'uploadDocument'])->name('form.upload-document');
        Route::post('/form/submit', [RegistrationFormController::class, 'submitRegistration'])->name('form.submit');
    });
});

// Admin Routes - menggunakan middleware 'admin' yang sudah ada
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

    // Payment Verification
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentVerificationController::class, 'index'])->name('index');
        Route::get('/{id}', [PaymentVerificationController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [PaymentVerificationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PaymentVerificationController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [PaymentVerificationController::class, 'bulkApprove'])->name('bulk-approve');
        Route::get('/download/{id}', [PaymentVerificationController::class, 'downloadFile'])->name('download');
        Route::get('/export/data', [PaymentVerificationController::class, 'export'])->name('export');
        Route::get('/api/statistics', [PaymentVerificationController::class, 'statistics'])->name('statistics');
    });
});