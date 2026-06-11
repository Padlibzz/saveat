<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = 'claims';

    protected $fillable = [
        'id_pengguna',
        'id_listing',
        'jumlah_porsi',
        'harga_perporsi',
        'total_harga',
        'status',
        'code_qr',
        'diambil_pada',
        'kadaluarsa_pada',
        'catatan',
    ];

    protected $casts = [
        'diambil_pada' => 'datetime',
        'kadaluarsa_pada' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function listing()
    {
        return $this->belongsTo(
            ListingMakanan::class,
            'id_listing'
        );
    }

    public function notifications()
    {
        return $this->hasMany(
            Notification::class,
            'id_claims'
        );
    }
}
