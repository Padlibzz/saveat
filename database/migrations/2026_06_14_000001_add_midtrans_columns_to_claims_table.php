<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('kode_klaim');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_payment_type')->nullable()->after('midtrans_transaction_id');
            $table->text('midtrans_snap_token')->nullable()->after('midtrans_payment_type');
            $table->text('midtrans_redirect_url')->nullable()->after('midtrans_snap_token');
            $table->string('midtrans_transaction_status')->nullable()->after('midtrans_redirect_url');
            $table->json('midtrans_raw_response')->nullable()->after('midtrans_transaction_status');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'midtrans_snap_token',
                'midtrans_redirect_url',
                'midtrans_transaction_status',
                'midtrans_raw_response',
            ]);
        });
    }
};
