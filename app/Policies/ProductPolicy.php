<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{

    function list(User $user): bool
    {
        return true;
    }
    function view(User $user, Product $product): bool
    {
        return true;
    }


    function create(User $user): bool
    {
        return $user->isAdministrator();
    }
    function update(User $user, Product $product): bool
    {
        return $user->isAdministrator();
    }
    function delete(User $user, Product $product): bool
    {
        return $user->isAdministrator();
    }


    function addShop(User $user, Product $product): bool
    {
        return $user->isAdministrator();
    }
    function removeShop(User $user, Product $product): bool
    {
        return $user->isAdministrator();
    }
}
