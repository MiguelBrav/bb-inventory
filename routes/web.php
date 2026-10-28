<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/dashboard', '/');

    Route::get('products/export/pdf', [ProductController::class, 'exportPdf'])->name('products.export.pdf');
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('categories/export/pdf', [CategoryController::class, 'exportPdf'])->name('categories.export.pdf');
    Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
    Route::resource('categories', CategoryController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('stock-movements/export/pdf', [StockMovementController::class, 'exportPdf'])
        ->name('stock-movements.export.pdf');
    Route::get('stock-movements/export', [StockMovementController::class, 'export'])
        ->name('stock-movements.export');
    Route::resource('stock-movements', StockMovementController::class)
        ->except(['show'])
        ->parameters(['stock-movements' => 'stockMovement']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('can:manage-users')->group(function (): void {
        Route::get('users/export/pdf', [UserController::class, 'exportPdf'])->name('users.export.pdf');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::resource('users', UserController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
