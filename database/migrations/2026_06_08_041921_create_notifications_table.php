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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('id_claims')
                ->nullable()
                ->constrained('claims')
                ->nullOnDelete();
            $table->enum('jenis', [
                'claims_masuk',
                'claims_berhasil',
                'listing_expired',
                'pesanan_selesai',
            ]);
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('is_read')
                ->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
