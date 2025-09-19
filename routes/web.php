<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', static function (): RedirectResponse {
    return redirect()->route('products.list');
});

Route::controller(ProductController::class)
    ->prefix('/products')->name('products.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/create', 'create')->name('create');

        Route::prefix('/{product}')->group(static function (): void {
            Route::get('', 'view')->name('view');
            Route::get('/shops', 'viewShops')->name('view-shops');
            Route::get('/update', 'showUpdateForm')->name('update-form');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');

            
            Route::prefix('/shops')->group(static function (): void {
                Route::get('/add', 'showAddShopsForm')->name('add-shops-form');
                Route::post('/add', 'addShops')->name('add-shops');
                Route::post('/{shop}/remove', 'removeShop')->name('remove-shop');
            });
        });
    });

Route::controller(ShopController::class)
    ->prefix('/shops')
    ->name('shops.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/create', 'create')->name('create');
        Route::prefix('/{shop}')->group(static function (): void {
            Route::get('', 'view')->name('view');
            Route::get('/products', 'viewProducts')->name('view-products'); 
            Route::get('/update', 'showUpdateForm')->name('update-form');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');

             Route::prefix('/products')->group(static function (): void {
                Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                Route::post('/add', 'addProducts')->name('add-products');
                Route::post('/remove', 'removeProduct')->name('remove-product'); 
            });
        });
    });


Route::controller(CategoryController::class)
  ->prefix('/categories')
  ->name('categories.')
  ->group(static function (): void {
      Route::get('', 'list')->name('list');
      Route::get('/create', 'showCreateForm')->name('create-form');
      Route::post('/create', 'create')->name('create');
      Route::prefix('/{category}')->group(static function (): void {
          Route::get('', 'view')->name('view');
          Route::get('/products', 'viewProducts')->name('view-products');
          Route::get('/update', 'showUpdateForm')->name('update-form');   
          Route::post('/update', 'update')->name('update');               
          Route::post('/delete', 'delete')->name('delete');               

         Route::prefix('/products')->group(static function (): void {
                Route::get('/add', 'showAddProductsForm')->name('add-products-form'); 
                Route::post('/add', 'addProducts')->name('add-products');            
            });

        
      });
  });


