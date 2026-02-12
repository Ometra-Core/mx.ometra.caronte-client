<?php

/**
 * @author Gabriel Ruelas
 * @license MIT
 * @version 1.4.0
 *
 */

use Illuminate\Support\Facades\Route;
use Ometra\Caronte\Http\Controllers\AuthController;
use Ometra\Caronte\Http\Controllers\ManagementController;
use Ometra\Caronte\Http\Controllers\UserController;
use Ometra\Caronte\Http\Controllers\RoleController;
use Equidna\Toolkit\Http\Middleware\ExcludeFromHistory;
use Equidna\Toolkit\Http\Middleware\DisableDebugbar;

//* Caronte
Route::middleware([DisableDebugbar::class])->group(function () {
    // Authentication routes
    Route::get('/login', [AuthController::class, 'loginForm'])->name('caronte.login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('caronte.login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('caronte.logout');

    // Two-factor authentication
    Route::post('/2fa', [AuthController::class, 'twoFactorTokenRequest'])->name('caronte.2fa.request');
    Route::get('/2fa/{token}', [AuthController::class, 'twoFactorTokenLogin'])->name('caronte.2fa.login');

    // Password recovery
    Route::prefix('password/recover')->name('caronte.password.recover.')->group(
        function () {
            Route::get('', [AuthController::class, 'passwordRecoverRequestForm'])->name('form');
            Route::post('', [AuthController::class, 'passwordRecoverRequest'])->name('request');
            Route::get('{token}', [AuthController::class, 'passwordRecoverTokenValidation'])->name('validate-token');
            Route::post('{token}', [AuthController::class, 'passwordRecover'])->name('submit');
        }
    );

    // Token and metadata management
    Route::get('get-token', [ManagementController::class, 'getToken'])->name('caronte.token')
        ->middleware([ExcludeFromHistory::class,]);
    Route::post('set-metadata', [ManagementController::class, 'setMetadata'])->name('caronte.set-metadata');

    // Management dashboard and CRUD operations
    Route::prefix('management')->name('caronte.management.')->group(function () {

        Route::get('/', [ManagementController::class, 'dashboard'])->name('dashboard');
        Route::post('/synchronize', [ManagementController::class, 'synchronize'])->name('synchronize');

        // User management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('list');
            Route::post('/create', [UserController::class, 'store'])->name('store');
            Route::post('/update', [UserController::class, 'update'])->name('update');
            Route::post('/delete', [UserController::class, 'delete'])->name('delete');

            // User roles management
            Route::prefix('{uri_user}/roles')->name('roles.')->group(function () {
                Route::get('', [RoleController::class, 'listByUser'])->name('list');
                Route::post('/attach', [RoleController::class, 'attach'])->name('attach');
                Route::post('/delete', [RoleController::class, 'detach'])->name('delete');
            });
        });

        // Role management
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('', [RoleController::class, 'index'])->name('list');
            Route::post('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/update', [RoleController::class, 'update'])->name('update');
            Route::post('/delete', [RoleController::class, 'delete'])->name('delete');
        });
    });
});
