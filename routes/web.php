<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

/**
 * 1) Auth routes (สาธารณะ) — ต้องอยู่นอก middleware 'auth'
 */
Route::controller(LoginController::class)
    ->prefix('auth')
    ->group(static function (): void {
        Route::get('/login', 'showLoginForm')->name('login');          // name = login (บังคับ)
        Route::post('/login', 'authenticate')->name('authenticate');
        Route::post('/logout', 'logout')->name('logout');
    });

/**
 * 2) Protected routes — ต้องล็อกอิน + no-cache
 *    (ย้าย cache.headers ไปรวมกับ auth และลบ auth routes ที่ซ้ำออก)
 */
Route::middleware([
        'auth',
        'cache.headers:no_store;no_cache;must_revalidate;max_age=0',
    ])
    ->group(static function (): void {

        Route::get('/', static function (): RedirectResponse {
            return redirect()->route('products.list');
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
                    Route::get('/update', 'showUpdateForm')->name('update-form');
                    Route::post('/update', 'update')->name('update');
                    Route::post('/delete', 'delete')->name('delete');
                    Route::prefix('/shops')->group(static function (): void {
                        Route::get('', 'viewShops')->name('view-shops');
                        Route::get('/add', 'showAddShopsForm')->name('add-shops-form');
                        Route::post('/add', 'addShop')->name('add-shop');
                        Route::post('/remove', 'removeShop')->name('remove-shop');
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
                    Route::get('/update', 'showUpdateForm')->name('update-form');
                    Route::post('/update', 'update')->name('update');
                    Route::post('/delete', 'delete')->name('delete');
                    Route::prefix('/products')->group(static function (): void {
                        Route::get('', 'viewProducts')->name('view-products');
                        Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                        Route::post('/add', 'addProduct')->name('add-product');
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
                    Route::get('/update', 'showUpdateForm')->name('update-form');
                    Route::post('/update', 'update')->name('update');
                    Route::post('/delete', 'delete')->name('delete');
                    Route::prefix('/products')->group(static function (): void {
                        Route::get('', 'viewProducts')->name('view-products');
                        Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                        Route::post('/add', 'addProduct')->name('add-product');
                    });
                });
            });

Route::controller(UserController::class)
    ->prefix('/users')
    ->name('users.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');                       // users.list
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/create', 'create')->name('create');
        Route::prefix('/{user}')->group(static function (): void {
            Route::get('', 'view')->name('view');
            Route::get('/update', 'showUpdateForm')->name('update-form');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');
        });
    });
    });
Route::prefix('/users/selves')
    ->name('users.selves.')
    ->controller(UserController::class)
    ->group(static function (): void {
        Route::get('', 'viewSelf')->name('view');
        Route::get('/update', 'showUpdateSelfForm')->name('update-form');
        Route::post('/update', 'updateSelf')->name('update');
    });