<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('gor_id');
            $table->foreignId('field_id');
            $table->string('jam_mulai');
            $table->string('jam_selesai');
            $table->string('durasi');
            $table->string('tanggal_main');
            $table->string('subtotal');
            $table->string('foto_struk')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
