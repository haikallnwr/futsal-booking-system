<?php

namespace App\Policies;

use App\Models\Field;
use App\Models\User;
use App\Models\Gor; // Import Gor
use Illuminate\Auth\Access\Response;

class FieldPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role_id == 1) {
            return true;
        }
        return null;
    }

    // Admin GOR bisa melihat semua lapangan di GOR miliknya
    public function viewAny(User $user, ?Gor $gor = null): bool
    {
        if ($user->role_id == 2 && $gor) {
            return $gor->user_id === $user->id;
        }
        return false;
    }

    // Admin GOR bisa melihat detail lapangan di GOR miliknya
    public function view(User $user, Field $field): bool
    {
        if ($user->role_id == 2) {
            return $field->gor->user_id === $user->id;
        }
        return false;
    }

    // Admin GOR bisa membuat lapangan di GOR miliknya
    public function create(User $user, ?Gor $gor = null): bool
    {
         if ($user->role_id == 2 && $gor) { // Gor harus disediakan saat create
            return $gor->user_id === $user->id;
        }
        return false;
    }

    // Admin GOR bisa update lapangan di GOR miliknya
    public function update(User $user, Field $field): bool
    {
        if ($user->role_id == 2) {
            return $field->gor->user_id === $user->id;
        }
        return false;
    }

    // Admin GOR bisa hapus lapangan di GOR miliknya
    public function delete(User $user, Field $field): bool
    {
        if ($user->role_id == 2) {
            return $field->gor->user_id === $user->id;
        }
        return false;
    }
}