<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
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
        })->name('appearance.index');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('store', [UserController::class, 'store'])->name('users.store');
        Route::put('update', [UserController::class, 'update'])->name('users.update');
        Route::delete('destroy', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('data-table', [UserController::class, 'dataTable'])->name('users.datatable');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::post('assign', [RoleController::class, 'assign'])->name('roles.assign');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('assign', [PermissionController::class, 'assign'])->name('permissions.assign');
    });
});
