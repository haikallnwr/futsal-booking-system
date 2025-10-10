<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'username',
        'email',
        'alamat',
        'password',
        'notelp',
        'profile_photo_path', // <-- TAMBAHKAN INI
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
   protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Ini penting jika Anda menggunakan Laravel 9+
    ];

    public function role()
    {
        return $this->BelongsTo(Role::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class);
    }
    public function gorManaged()
{
    return $this->hasOne(Gor::class, 'user_id'); 
}
}
