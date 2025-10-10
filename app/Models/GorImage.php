<?php

// app/Models/GorImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GorImage extends Model
{
    use HasFactory;
    protected $fillable = [ 'image_path'];

    public function gor()
    {
        return $this->belongsTo(Gor::class);
    }
}