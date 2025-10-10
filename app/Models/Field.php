<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['schedule', 'order',];

    public function gor()
    {
        return $this->BelongsTo(Gor::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
