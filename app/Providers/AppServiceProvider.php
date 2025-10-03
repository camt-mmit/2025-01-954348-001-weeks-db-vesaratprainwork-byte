<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;


use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ShopPolicy;
use App\Policies\CategoryPolicy;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {

        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.default');
        \Illuminate\Pagination\Paginator::defaultSimpleView('vendor.pagination.simple-default');


        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Shop::class, ShopPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
