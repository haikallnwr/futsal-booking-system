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
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom snap_token setelah subtotal, bisa null
            $table->string('snap_token')->nullable()->after('subtotal');

            // Hapus kolom foto_struk jika masih ada dan Anda yakin tidak memerlukannya lagi
            if (Schema::hasColumn('orders', 'foto_struk')) {
                $table->dropColumn('foto_struk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
            // Jika ingin mengembalikan kolom foto_struk saat rollback
            $table->string('foto_struk')->nullable();
        });
    }
};