<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Models\Gor;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role_id == 1) {
            return true;
        }
        return null;
    }

    // Admin GOR bisa lihat semua order di GOR miliknya
    public function viewAny(User $user, ?Gor $gor = null): bool
    {
        if ($user->role_id == 2 && $gor) {
            return $gor->user_id === $user->id;
        }
         return false;
    }

    // Admin GOR bisa lihat detail order di GOR miliknya
    public function view(User $user, Order $order): bool
    {
        if ($user->role_id == 2) {
            return $order->gor->user_id === $user->id;
        }
        return false;
    }

    // Order dibuat oleh user, bukan admin/dev
    public function create(User $user): bool
    {
        return false;
    }

    // Admin GOR bisa update status order di GOR miliknya
    public function update(User $user, Order $order): bool
    {
         if ($user->role_id == 2) {
            return $order->gor->user_id === $user->id;
        }
        return false;
    }

    // Sebaiknya order tidak dihapus, tapi statusnya diubah jadi 'Cancelled'
    public function delete(User $user, Order $order): bool
    {
        return false; // Atau hanya Developer yang bisa (tambahkan logic di before())
    }
}