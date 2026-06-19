<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'user_id',
        'listing_id',
        'jumlah',
        'total_harga',
        'kode_klaim',
        'metode_pembayaran',
        'status_pembayaran',
        'waktu_pembayaran',
        'status',
        // Midtrans columns
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_snap_token',
        'midtrans_redirect_url',
        'midtrans_transaction_status',
        'midtrans_raw_response',
    ];

    protected $casts = [
        'midtrans_raw_response' => 'array',
        'waktu_pembayaran' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }
}
