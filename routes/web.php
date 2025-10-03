<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Models\User as UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', static function (): RedirectResponse {
    return redirect()->route('products.list');
});


Route::controller(LoginController::class)
    ->prefix('auth')
    ->group(static function (): void {
        Route::get('/login', 'showForm')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
        Route::post('/logout', 'logout')->name('logout');
    });


Route::middleware(['auth', 'cache.headers:no_store;no_cache;must_revalidate;max_age=0'])
    ->group(function (): void {

        
        Route::middleware('can:create,' . UserModel::class)->group(function (): void {
            Route::controller(UserController::class)->group(function (): void {
                Route::get('/users', 'list')->name('users.list');
                Route::get('/users/create', 'showCreateForm')->name('users.create-form');
                Route::post('/users/create', 'create')->name('users.create');
                Route::get('/users/{user}', 'view')->name('users.view');
                Route::get('/users/{user}/update', 'showUpdateForm')->name('users.update-form');
                Route::post('/users/{user}/update', 'update')->name('users.update');
                Route::post('/users/{user}/delete', 'delete')->name('users.delete');
            });
        });

       
        Route::controller(UserController::class)->group(function (): void {
            Route::get('/users/self', 'selfView')->name('users.selves.view');
            Route::get('/users/self/update', 'showSelfUpdateForm')->name('users.selves.update-form');
            Route::post('/users/self/update', 'selfUpdate')->name('users.selves.update');
        });

       
        Route::controller(ProductController::class)
            ->prefix('/products')
            ->name('products.')
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
                        Route::post('/add', 'addProducts')->name('add-products');           // ส่ง product/code ใน body
                        Route::post('/remove', 'removeProduct')->name('remove-product');     // หรือใช้ '/{product}/remove'
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

                    Route::get('/update', 'showUpdateForm')->name('update-form');
                    Route::post('/update', 'update')->name('update');
                    Route::post('/delete', 'delete')->name('delete');

                    Route::prefix('/products')->group(static function (): void {
                        Route::get('', 'viewProducts')->name('view-products');
                        Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                        Route::post('/add', 'addProducts')->name('add-products');
                    });
                });
            });
    });


