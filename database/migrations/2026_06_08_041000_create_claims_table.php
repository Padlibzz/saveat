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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')
                ->constrained('users')
                ->onDelete('cascade');
            $table->integer('jumlah_porsi');
            $table->decimal('harga_perporsi', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', [
                'aktif',
                'diproses',
                'sudah_diambil',
                'kadaluarsa',
                'dibatalkan',
            ])->default('aktif');
            $table->string('code_qr')->unique();
            $table->timestamp('diambil_pada')->nullable();
            $table->timestamp('kadaluarsa_pada')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
