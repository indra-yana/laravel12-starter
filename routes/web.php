<?php

use Nwidart\Modules\Facades\Module;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Render default home page if Home module not exist or disabled
if (!Module::has('Home') || !Module::isEnabled('Home')) {
    Route::get('/', function () {
        return inertia('Welcome');
    })->name('home');
}