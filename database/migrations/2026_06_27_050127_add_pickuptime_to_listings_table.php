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
        Schema::table('listings', function (Blueprint $table) {
            $table->date('pickup_date')
                  ->nullable()
                  ->after('batas_waktu');

            $table->time('pickup_start')
                  ->nullable()
                  ->after('pickup_date');

            $table->time('pickup_end')
                  ->nullable()
                  ->after('pickup_start');

            $table->integer('pickup_interval')
                  ->default(30)
                  ->after('pickup_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_date',
                'pickup_start',
                'pickup_end',
                'pickup_interval'
            ]);
        });
    }
};
