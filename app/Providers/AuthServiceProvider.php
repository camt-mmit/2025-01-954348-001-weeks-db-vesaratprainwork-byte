<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ShopPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class  => ProductPolicy::class,
        Shop::class     => ShopPolicy::class,
        Category::class => CategoryPolicy::class,
        User::class     => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
