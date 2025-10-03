<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{

    public function list(User $user): bool
    {
        return true;
    }
    public function view(User $user, Shop $shop): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }


    public function update(User $user, Shop $shop): bool
    {
        return $user->isAdministrator();
    }


    public function delete(User $user, Shop $shop): bool
    {
        return $user->isAdministrator();
    }
}
