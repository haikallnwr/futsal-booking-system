<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    //protected $with = ['user']; // Sebaiknya di-load secara eksplisit (eager loading) saat dibutuhkan

    // Konstanta untuk status pesanan/pembayaran
    public const STATUS_WAITING_FOR_PAYMENT = 'Waiting for Payment';
    public const STATUS_PENDING_CONFIRMATION = 'Pending Confirmation';
    public const STATUS_PAYMENT_CONFIRMED = 'Payment Confirmed';
    public const STATUS_BOOKED = 'Booked'; // Bisa digunakan setelah pembayaran dikonfirmasi atau jika ada DP
    public const STATUS_ON_PROGRESS = 'On Progress';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_CANCELLED = 'Cancelled';
    public const STATUS_FAILED = 'Failed'; // Pembayaran ditolak atau gagal

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function gor()
    {
        return $this->belongsTo(Gor::class);
    }

    public function schedule()
    {
        // Jika setiap order PASTI membuat satu schedule, maka hasOne sudah tepat.
        return $this->hasOne(Schedule::class, 'order_id');
    }

    /**
     * Mendapatkan URL lengkap untuk foto struk.
     *
     * @return string|null
     */
    public function getFotoStrukUrlAttribute(): ?string
    {
        if ($this->foto_struk) {
            return asset('storage/' . $this->foto_struk);
        }
        return null;
    }
}