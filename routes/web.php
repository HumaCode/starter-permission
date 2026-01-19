<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
