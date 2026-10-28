<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class)
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::resource('stock-movements', StockMovementController::class)
    ->parameters(['stock-movements' => 'stockMovement']);
