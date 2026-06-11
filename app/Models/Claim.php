<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = 'claims';

    protected $fillable = [
        'id_pengguna',
        'id_listings',
        'jumlah',
        'total_harga',
        'kode_klaim',
        'status',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'id_listings');
    }
}
