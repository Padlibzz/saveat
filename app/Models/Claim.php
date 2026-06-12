<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'user_id',
        'listing_id', // UBAH: id_listings menjadi listing_id
        'jumlah',
        'total_harga', 
        'kode_klaim',  
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // UBAH: Nama method menjadi singular 'listing' karena belongsTo merujuk ke 1 item
    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id'); // Sesuaikan foreign key
    }
}