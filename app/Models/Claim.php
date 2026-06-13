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
        'metode_pembayaran', // <-- TAMBAHAN
        'status_pembayaran', // <-- TAMBAHAN
        'waktu_pembayaran',  // <-- TAMBAHAN
        'status',
        'listing_id', 
        'jumlah',
        'total_harga', 
        'kode_klaim',
        'metode_pembayaran', 
        'status_pembayaran', 
        'waktu_pembayaran',  
        'status'
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
