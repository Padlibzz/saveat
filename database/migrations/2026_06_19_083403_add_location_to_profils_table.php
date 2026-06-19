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
        Schema::table('profils', function (Blueprint $table) {
            // Untuk merchant: koordinat lokasi toko (dipakai hitung jarak ke listing)
            // Untuk konsumen: koordinat lokasi terakhir (dari toggle "Layanan Lokasi")
            $table->decimal('latitude', 10, 7)->nullable()->after('alamat');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->boolean('izin_lokasi')->default(false)->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'izin_lokasi']);
        });
    }
};
