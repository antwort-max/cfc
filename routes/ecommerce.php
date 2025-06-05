<?php 

use Illuminate\Support\Facades\Routes;

Route::get('/',  [App\Http\Controllers\Ecommerce\NavigationController::class, 'landingPage'])->name('landingPage');
Route::get('/locations',  [App\Http\Controllers\Ecommerce\NavigationController::class, 'locationPage'])->name('locationPage');
Route::get('/search-products', [\App\Http\Controllers\Ecommerce\ProductSearchController::class, 'index'])->name('products.search');
Route::get('/products/brand/{brand}', [App\Http\Controllers\Ecommerce\BrandProductController::class, 'show'])->name('products.byBrand');
Route::get('/products/family/{family}', [App\Http\Controllers\Ecommerce\FamilyProductController::class, 'show'])->name('products.byFamily');
Route::get('/products/category/{category}', [App\Http\Controllers\Ecommerce\CategoryProductController::class, 'show'])->name('products.byCategory');

Route::get('/product/{product:slug}', [App\Http\Controllers\Ecommerce\ProductShowController::class, 'show'])->name('products.show');

Route::post('/cart-store',  [App\Http\Controllers\Ecommerce\CartController::class, 'store'])->name('cart.store');  
Route::get('/cart-show',   [App\Http\Controllers\Ecommerce\CartController::class, 'show'])->name('cart.show');    
Route::patch('/cart-update/{item}',  [App\Http\Controllers\Ecommerce\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart-destroy/{item}', [App\Http\Controllers\Ecommerce\CartController::class, 'destroy'])->name('cart.destroy');
Route::get('cart/customer', [\App\Http\Controllers\Ecommerce\CartController::class, 'selectCustomer'])->name('cart.customer.select');

Route::get('checkout', [App\Http\Controllers\Ecommerce\CheckoutController::class, 'show'])->name('checkout.show');
Route::post('checkout/process', [App\Http\Controllers\Ecommerce\CheckoutController::class, 'process'])->name('checkout.process');
Route::get('checkout/thanks', [CheckoutController::class, 'thanks'])->name('checkout.thanks');
Route::post('register-from-guest', [App\Http\Controllers\Ecommerce\RegisterController::class, 'fromGuest'])->name('customer.register.fromGuest');

