<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // สำหรับเมนู/ลิสต์ทั้งหมด
    public function list(User $user): bool     { return $user->role === 'ADMIN'; }
    public function viewAny(User $user): bool  { return $user->role === 'ADMIN'; }

    // ตัวอย่าง CRUD หลัก
    public function view(User $user, User $target): bool   { return $user->role === 'ADMIN' || $user->id === $target->id; }
    public function create(User $user): bool               { return $user->role === 'ADMIN'; }
    public function update(User $user, User $target): bool { return $user->role === 'ADMIN' || $user->id === $target->id; }
    public function delete(User $user, User $target): bool { return $user->role === 'ADMIN' && $user->id !== $target->id; }
}
