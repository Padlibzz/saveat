<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // UBAH: 'profil' menjadi 'profils'
        Schema::create('profils', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unique();

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

    public function down(): void
    {
        // UBAH: 'profil' menjadi 'profils'
        Schema::dropIfExists('profils');
    }
};
