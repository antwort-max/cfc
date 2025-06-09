<?php

use Illuminate\Support\Facades\Route;              // ← singular, no “Routes”
use App\Http\Controllers\CustomerAuthController;

/* Visitantes */
Route::middleware('guest:customer')                // ← DOS PUNTOS
    ->prefix('account')
    ->name('customer.')
    ->group(function () {
        Route::get('register',  [CustomerAuthController::class, 'showRegister'])->name('register');
        Route::post('register', [CustomerAuthController::class, 'register']);

        Route::get('login',     [CustomerAuthController::class, 'showLogin'])->name('login');
        Route::post('login',    [CustomerAuthController::class, 'login'])->name('login.store');
    });

/* Clientes autenticados */
Route::middleware('auth:customer')                 // ← DOS PUNTOS
    ->prefix('account')
    ->name('customer.')
    ->group(function () {
        Route::view('dashboard', 'auth.dashboard')->name('dashboard');
        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
    });

use App\Http\Controllers\Ecommerce\CartController;

Route::middleware(['auth:customer'])->group(function () {
    // Ficha de cliente
    Route::get('customer/profile', [App\Http\Controllers\CustomerDashboardController::class, 'profile'])
        ->name('customer.profile');

    // Historial de ventas
    Route::get('customer/sales', [App\Http\Controllers\CustomerDashboardController::class, 'sales'])->name('customer.sales');

    // Carros abandonados
    Route::get('customer/abandoned-carts', [App\Http\Controllers\CustomerDashboardController::class, 'abandoned'])->name('customer.abandonedCarts');
});


Route::middleware(['auth:customer'])->group(function () {
    // ... otras rutas

    // Ruta para restaurar un carro abandonado
    Route::post(
        'customer/abandoned-carts/{cart}/restore',
        [CartController::class, 'restore']
    )->name('cart.restore');
});

use App\Http\Controllers\CustomerDashboardController;

// ...

Route::middleware(['auth:customer'])->group(function () {
    // Ver detalles de un carro abandonado
    Route::get(
        'customer/abandoned-carts/{cart}',
        [CartController::class, 'showAbandoned']
    )->name('cart.showAbandoned');

    // Restaurar un carro abandonado como carrito activo
    Route::post(
        'customer/abandoned-carts/{cart}/restore',
        [CartController::class, 'restore']
    )->name('cart.restore');

    // Perfil y ventas (si aún no las tienes)
    Route::get(
        'customer/profile',
        [CustomerDashboardController::class, 'profile']
    )->name('customer.profile');

    Route::get(
        'customer/sales',
        [CustomerDashboardController::class, 'sales']
    )->name('customer.sales');
});