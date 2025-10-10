<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Gor; // Import Gor
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role_id == 1) {
            return true;
        }
        return null;
    }

    // Admin GOR bisa lihat semua jadwal di GOR miliknya
    public function viewAny(User $user, ?Gor $gor = null): bool
    {
        if ($user->role_id == 2 && $gor) {
            return $gor->user_id === $user->id;
        }
        return false;
    }

    // Admin GOR bisa lihat detail jadwal di GOR miliknya
    public function view(User $user, Schedule $schedule): bool
    {
        if ($user->role_id == 2) {
            return $schedule->gor->user_id === $user->id;
        }
        return false;
    }

    // Jadwal dibuat otomatis dari Order
    public function create(User $user): bool
    {
        return false;
    }

    // Admin GOR bisa membatalkan jadwal di GOR miliknya
    public function update(User $user, Schedule $schedule): bool // Untuk cancelSchedule
    {
        if ($user->role_id == 2) {
            return $schedule->gor->user_id === $user->id;
        }
        return false;
    }

    // Sebaiknya jadwal tidak dihapus langsung, tapi status diubah
    public function delete(User $user, Schedule $schedule): bool
    {
         return false;
    }
}