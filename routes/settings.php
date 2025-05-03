<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\PermissionsController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('settings')->middleware('auth')->group(function () {
    Route::redirect('/', '/settings/profile');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('password')->group(function () {
        Route::get('/', [PasswordController::class, 'edit'])->name('password.edit');
        Route::put('/', [PasswordController::class, 'update'])->name('password.update');
    });

    Route::prefix('appearance')->group(function () {
        Route::get('/', function () {
            return Inertia::render('settings/Appearance');
        })->name('appearance');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('users.index');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionsController::class, 'index'])->name('permissions.index');
    });
});
