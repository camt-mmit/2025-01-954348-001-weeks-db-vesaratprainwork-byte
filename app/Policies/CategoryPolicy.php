<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    function list(User $user): bool
    {
        return true;
    }
    function view(User $user, Category $category): bool
    {
        return true;
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }
    function update(User $user, Category $category): bool
    {
        return $user->isAdministrator();
    }
    function delete(User $user, Category $category): bool
    {
        return $user->isAdministrator();
    }


    function addProduct(User $user, Category $category): bool
    {
        return $user->isAdministrator();
    }
}
