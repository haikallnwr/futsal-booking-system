<?php

namespace App\Policies;

use App\Models\Gor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GorPolicy
{
    /**
     * Perform pre-authorization checks.
     * Developer (role_id 1) bisa melakukan apa saja.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role_id == 1) { // Developer
            return true;
        }
        return null; // Lanjutkan ke metode policy lainnya
    }

    /**
     * Determine whether the user can view any models.
     * (Admin GOR tidak melihat list GOR, hanya GOR miliknya)
     */
    public function viewAny(User $user): bool
    {
        return $user->role_id == 1; // Hanya Developer
    }

    /**
     * Determine whether the user can view the model.
     * (Admin GOR bisa lihat GOR miliknya)
     */
    public function view(User $user, Gor $gor): bool
    {
        if ($user->role_id == 2) { // Admin GOR
            return $gor->user_id === $user->id;
        }
        return false; // User biasa tidak punya akses via policy ini
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role_id == 1; // Hanya Developer bisa buat GOR baru
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Gor $gor): bool
    {
         if ($user->role_id == 2) { // Admin GOR
            return $gor->user_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Gor $gor): bool
    {
        return $user->role_id == 1; // Hanya Developer
    }
}