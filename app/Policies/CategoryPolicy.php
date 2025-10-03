<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function list(User $user): bool
    {
        return true;
    }
    public function view(User $user, Category $category): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }


    public function update(User $user, Category $category): bool
    {
        return $user->isAdministrator();
    }


    public function delete(User $user, Category $category): bool
    {
        return $user->isAdministrator();
    }
}
