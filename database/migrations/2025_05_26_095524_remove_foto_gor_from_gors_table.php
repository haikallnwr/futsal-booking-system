<?php

// database/migrations/xxxx_xx_xx_xxxxxx_remove_foto_gor_from_gors_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gors', function (Blueprint $table) {
            if (Schema::hasColumn('gors', 'foto_gor')) {
                $table->dropColumn('foto_gor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gors', function (Blueprint $table) {
            $table->string('foto_gor')->nullable();
        });
    }
};