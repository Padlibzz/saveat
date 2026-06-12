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
        Schema::create('profil', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unique(); // Setiap pengguna hanya memiliki satu profil

            $table->enum('tipe_profil', ['konsumen', 'merchant', 'admin']);

            $table->text('alamat')->nullable();

            $table->string('nama_usaha')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('link_map')->nullable();

            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->nullable();
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('diverifikasi_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil');
    }
};
