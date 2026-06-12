<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('profils')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('categories')->onDelete('restrict');
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->decimal('harga_normal', 10, 2);
            $table->decimal('harga_diskon', 10, 2);
            $table->integer('stok_total');
            $table->integer('stok_sisa');
            $table->dateTime('batas_waktu');
            $table->enum('status', ['aktif', 'habis', 'diarsipkan', 'ditolak'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};