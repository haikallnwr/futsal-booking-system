<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gors', function (Blueprint $table) {
            $table->text('alamat_gor')->change();

            // TAMBAHKAN KOLOM FASILITAS DI SINI
            $table->text('fasilitas')->after('deskripsi')->nullable();
            $table->string('wilayah')->after('fasilitas')->nullable(); 
            $table->string('kecamatan')->after('wilayah')->nullable();
            $table->string('whatsapp')->after('kecamatan')->nullable();
            $table->string('instagram')->after('whatsapp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gors', function (Blueprint $table) {
            $table->string('alamat_gor')->change();
            
            $table->dropColumn(['fasilitas', 'wilayah', 'kecamatan', 'whatsapp', 'instagram']);
        });
    }
};