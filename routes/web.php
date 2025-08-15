<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\ShopController;

Route::get('/', function () {
    return view('welcome');
});


Route::controller(ProductController::class)
    ->prefix('/products')
    ->name('products.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');
        Route::get('/{product}', 'view')->name('view');
    });

Route::controller(ShopController::class)
    ->prefix('/shops')
    ->name('shops.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');
        Route::get('/{shop}', 'view')->name('view');
    });
