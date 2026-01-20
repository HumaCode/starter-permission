<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserAksesController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('testing', fn() => Inertia('Testing'));

Route::controller(DashboardController::class)->group(function () {
    Route::get('dashboard', 'index')->name('dashboard');
});

// master
Route::controller(MenuController::class)->group(function () {
    Route::get('products', 'index')->name('products.index');
    Route::get('products/create', 'create')->name('products.create');
    Route::post('products/create', 'store')->name('products.store');
    Route::get('products/{menu}/edit', 'edit')->name('products.edit');
    Route::put('products/{menu}/edit', 'update')->name('products.update');
    Route::delete('products/{menu}/destroy', 'destroy')->name('products.destroy');
});


// menu
Route::controller(MenuController::class)->group(function () {
    Route::get('menus', 'index')->name('menus.index');
    Route::get('menus/create', 'create')->name('menus.create');
    Route::post('menus/create', 'store')->name('menus.store');
    Route::get('menus/{menu}/edit', 'edit')->name('menus.edit');
    Route::put('menus/{menu}/edit', 'update')->name('menus.update');
    Route::delete('menus/{menu}/destroy', 'destroy')->name('menus.destroy');
});

// roles
Route::controller(RoleController::class)->group(function () {
    Route::get('roles', 'index')->name('roles.index');
    Route::get('roles/create', 'create')->name('roles.create');
    Route::post('roles/create', 'store')->name('roles.store');
    Route::get('roles/{role}/edit', 'edit')->name('roles.edit');
    Route::put('roles/{role}/edit', 'update')->name('roles.update');
    Route::delete('roles/{role}/destroy', 'destroy')->name('roles.destroy');
});

// permission
Route::controller(PermissionController::class)->group(function () {
    Route::get('permissions', 'index')->name('permissions.index');
    Route::get('permissions/create', 'create')->name('permissions.create');
    Route::post('permissions/create', 'store')->name('permissions.store');
    Route::get('permissions/{permission}/edit', 'edit')->name('permissions.edit');
    Route::put('permissions/{permission}/edit', 'update')->name('permissions.update');
    Route::delete('permissions/{permission}/destroy', 'destroy')->name('permissions.destroy');
});

// user-akses
Route::controller(UserAksesController::class)->group(function () {
    Route::get('user-akses', 'index')->name('user-akses.index');
    Route::get('user-akses/create', 'create')->name('user-akses.create');
    Route::post('user-akses/create', 'store')->name('user-akses.store');
    Route::get('user-akses/{user_akses}/edit', 'edit')->name('user-akses.edit');
    Route::put('user-akses/{user_akses}/edit', 'update')->name('user-akses.update');
    Route::delete('user-akses/{user_akses}/destroy', 'destroy')->name('user-akses.destroy');
});

// user
Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index')->name('users.index');
    Route::get('users/create', 'create')->name('users.create');
    Route::post('users/create', 'store')->name('users.store');
    Route::get('users/{menu}/edit', 'edit')->name('users.edit');
    Route::put('users/{menu}/edit', 'update')->name('users.update');
    Route::delete('users/{menu}/destroy', 'destroy')->name('users.destroy');
});

// settitng
Route::controller(SettingController::class)->group(function () {
    Route::get('settings/website', 'index')->name('settings.website');
    Route::get('settings/website/create', 'create')->name('settings.website');
    Route::post('settings/website/create', 'store')->name('settings.website');
    Route::get('settings/website/{menu}/edit', 'edit')->name('settings.website');
    Route::put('settings/website/{menu}/edit', 'update')->name('settings.website');
    Route::delete('settings/website/{menu}/destroy', 'destroy')->name('settings.website');
});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
