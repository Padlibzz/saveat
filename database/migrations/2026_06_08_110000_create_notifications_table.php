<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // PERBAIKAN: user_id merujuk ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // PERBAIKAN: claim_id merujuk ke tabel claims
            $table->foreignId('claim_id')->nullable()->constrained('claims')->nullOnDelete();

            $table->enum('jenis', [
                'claims_masuk',
                'claims_berhasil',
                'listing_expired',
                'pesanan_selesai',
            ]);
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
