<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // Hapus $with = ['order']; untuk menghindari N+1, load secara eksplisit
    // protected $with = ['order'];

    // Konstanta Status untuk Schedule
    public const STATUS_TENTATIVE = 'Tentative'; // Saat order dibuat, menunggu pembayaran
    public const STATUS_BOOKED = 'Booked';       // Order sudah dikonfirmasi/booked
    public const STATUS_ON_PROGRESS = 'On Progress'; // Order sedang berlangsung (opsional)
    public const STATUS_COMPLETED = 'Completed';   // Order sudah selesai
    public const STATUS_CANCELLED = 'Cancelled';   // Order/Schedule dibatalkan
    public const STATUS_BLOCKED = 'Blocked';     // Jika admin memblokir manual jadwal tertentu

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function user() // Pemesan
    {
        return $this->belongsTo(User::class);
    }

    public function order() // Relasi ke Order yang membuat schedule ini
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function gor()
    {
        return $this->belongsTo(Gor::class);
    }
}