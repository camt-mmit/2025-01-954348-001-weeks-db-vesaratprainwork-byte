<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    public function list(User $user): bool
    {
        return true;
    }
    public function view(User $user, Product $product): bool
    {
        return $this->list($user);
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }
    public function update(User $user, Product $product): bool
    {
        return $user->isAdministrator();
    }
    public function delete(User $user, Product $product): bool
    {

        return $user->isAdministrator();
    }
}
