<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/ecommerce.php';
require __DIR__.'/customer.php';


Route::middleware(['auth', 'verified'])->get('/sync-products', [App\Http\Controllers\Sync\SyncProductsController::class, 'index'])->name('sync.products');
Route::middleware(['auth', 'verified'])->get('/sync-brands', [App\Http\Controllers\Sync\SyncBrandsController::class, 'index'])->name('sync.brands');
Route::middleware(['auth', 'verified'])->get('/sync-categories', [App\Http\Controllers\Sync\SyncCategoriesController::class, 'index'])->name('sync.categories');
Route::middleware(['auth', 'verified'])->get('/sync-families', [App\Http\Controllers\Sync\SyncFamiliesController::class, 'index'])->name('sync.families');
Route::middleware(['auth', 'verified'])->get('/sync-subfamilies', [App\Http\Controllers\Sync\SyncSubfamiliesController::class, 'index'])->name('sync.subfamilies');
Route::middleware(['auth', 'verified'])->get('/sync-stock', [App\Http\Controllers\Sync\SyncStockController::class, 'index'])->name('sync.stock');

Route::get('sync/historic-products/{initial?}/{final?}', [App\Http\Controllers\Sync\SyncHistoricController::class, 'index']);
Route::get('/bi/seller-sales-report/pdf', [App\Filament\Bi\Pages\SellerSalesAnalysis::class, 'downloadPdf'])->name('seller-sales-report');