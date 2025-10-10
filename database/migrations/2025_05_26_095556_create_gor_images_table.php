<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_gor_images_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gor_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gor_id')->constrained('gors')->onDelete('restrict');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gor_images');
    }
};