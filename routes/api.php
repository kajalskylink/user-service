<?php

use App\Http\Controllers\BuyerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;

// Route::middleware(['api.key', 'auth:sanctum', 'throttle:api'])->group(function () {     --> need to be added api_key into the env file

Route::middleware(['throttle:api'])->group(function () {

    // Permission Related Route
    Route::controller(PermissionController::class)->name('permissions.')->prefix('permissions')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{permission}/edit', 'edit')->name('edit');
        Route::put('/{permission}', 'update')->name('update');
        Route::patch('/{permission}/status', 'changeStatus')->name('changeStatus');
        Route::delete('/{permission}', 'destroy')->name('destroy');
    });

    //Role Related route
    Route::controller(RoleController::class)->name('roles.')->prefix('roles')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{role}', 'show')->name('show');
        Route::get('/{role}/edit', 'edit')->name('edit');
        Route::put('/{role}', 'update')->name('update');
        Route::delete('/{role}', 'destroy')->name('destroy');
        Route::patch('/{role}/status', 'changeStatus')->name('changeStatus');
    });

    // Buyer Related Rotue
    Route::controller(BuyerController::class)->name('buyers.')->prefix('buyers')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{buyer}', 'show')->name('show');
        Route::get('/{buyer}/edit', 'edit')->name('edit');
        Route::put('/{buyer}', 'update')->name('update');
        Route::delete('/{buyer}', 'destroy')->name('destroy');
        Route::put('/{buyer}', 'changeStatus')->name('changeStatus');
    });

    // Supplier Related Route
    Route::controller(SupplierController::class)->name('suppliers.')->prefix('suppliers')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{supplier}', 'show')->name('show');
        Route::get('/{supplier}/edit', 'edit')->name('edit');
        Route::put('/{supplier}', 'update')->name('update');
        Route::delete('/{supplier}', 'destroy')->name('destroy');
        Route::put('/{supplier}', 'changeStatus')->name('changeStatus');
    });

});

// User Related Route
Route::controller(UserController::class)->name('users.')->prefix('users')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{user}', 'show')->name('show');
    Route::get('/{user}/edit', 'edit')->name('edit');
    Route::put('/{user}', 'update')->name('update');
    Route::delete('/{user}', 'destroy')->name('destroy');

    Route::prefix('{user}')->group(function() {
        Route::patch('update-roles',  'updateRoles')->name('users.updateRoles');
        Route::patch('update-password', 'updatePassword')->name('users.updatePassword');
        Route::patch('change-status','changeStatus')->name('users.changeStatus');
    });
});
