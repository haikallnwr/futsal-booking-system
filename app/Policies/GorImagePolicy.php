<?php

namespace App\Policies;

use App\Models\GorImage;
use App\Models\User;
use App\Models\Gor; // Import Gor
use Illuminate\Auth\Access\Response;

class GorImagePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role_id == 1) {
            return true;
        }
        return null;
    }

    // Admin GOR bisa membuat (upload) gambar untuk GOR miliknya
    public function create(User $user, Gor $gor): bool
    {
        if ($user->role_id == 2) {
            return $gor->user_id === $user->id;
        }
        return false;
    }

    // Admin GOR bisa menghapus gambar dari GOR miliknya
    public function delete(User $user, GorImage $gorImage): bool
    {
        if ($user->role_id == 2) {
            return $gorImage->gor->user_id === $user->id;
        }
        return false;
    }
}