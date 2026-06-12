<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            // UBAH: id_listings menjadi listing_id
            $table->foreignId('listing_id')
                ->constrained('listings')
                ->onDelete('cascade');
                
            $table->integer('jumlah');
            $table->decimal('total_harga', 10, 2);
            $table->string('kode_klaim')->unique();
            $table->enum('status', ['pending', 'diambil', 'batal']) ->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};