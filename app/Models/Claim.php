<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    // PERBAIKAN: Tambahkan total_harga dan kode_klaim ke dalam fillable
    protected $fillable = [
        'user_id',
        'id_listings',
        'jumlah',
        'total_harga',
        'kode_klaim',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function listings()
    {
        return $this->belongsTo(Listing::class, 'id_listings');
    }
}