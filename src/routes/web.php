<?php

/**
 * @author Gabriel Ruelas
 * @license MIT
 * @version 1.3.2
 *
 */

use Illuminate\Support\Facades\Route;
use Ometra\Caronte\Http\Controllers\CaronteController;
use Equidna\Toolkit\Http\Middleware\ExcludeFromHistory;
use Equidna\Toolkit\Http\Middleware\DisableDebugbar;

//* Caronte
Route::middleware([DisableDebugbar::class])->group(function () {
    Route::get('/login', [CaronteController::class, 'loginForm'])->name('caronte.login.form');
    Route::post('/login', [CaronteController::class, 'login'])->name('caronte.login');
    Route::get('/logout', [CaronteController::class, 'logout'])->name('caronte.logout');

    Route::post('/2fa', [CaronteController::class, 'twoFactorTokenRequest'])->name('caronte.2fa.request');
    Route::get('/2fa/{token}', [CaronteController::class, 'twoFactorTokenLogin'])->name('caronte.2fa.login');

    // Ensure a single blank line after use statements
    Route::prefix('password/recover')->group(
        function () {
            Route::get('', [CaronteController::class, 'passwordRecoverRequestForm'])->name('caronte.password.recover.form');
            Route::post('', [CaronteController::class, 'passwordRecoverRequest'])->name('caronte.password.recover.request');
            Route::get('{token}', [CaronteController::class, 'passwordRecoverTokenValidation'])->name('caronte.password.recover.validate-token');
            Route::post('{token}', [CaronteController::class, 'passwordRecover'])->name('caronte.password.recover');
        }
    );

    Route::get('get-token', [CaronteController::class, 'getToken'])->name('caronte.token')
        ->middleware([ExcludeFromHistory::class,]);

    Route::post('set-metadata', [CaronteController::class, 'setMetadata'])->name('caronte.set-metadata');
    Route::get('/caronte-client-management', [CaronteController::class, 'managementApp'])->name('caronte.caronte-client.management');

    Route::prefix('caronte-client-management')->group(function () {
        Route::get('/', [CaronteController::class, 'managementApp'])->name('caronte.caronte-client.management');
        Route::post('/create-user', [CaronteController::class, 'createUser'])->name('caronte.management.store');
        Route::get('/create', [CaronteController::class, 'managementCreate'])->name('caronte.management.create');
        Route::get('roles-user/{uri_user}', [CaronteController::class, 'getRolesByUser'])->name('caronte.management.roles-by-user');
        Route::get('all-roles', [CaronteController::class, 'getAllRoles'])->name('caronte.management.all-roles');
        Route::post('attach-roles', [CaronteController::class, 'attachRolesToUser'])->name('caronte.management.attach-roles');
        Route::post('delete-roles', [CaronteController::class, 'deleteRolesFromUser'])->name('caronte.management.delete-roles');
        Route::post('/update-user', [CaronteController::class, 'updateUser'])->name('caronte.management.update-user');
        Route::post('/delete-user', [CaronteController::class, 'deleteUser'])->name('caronte.management.delete-user');
    });
});
