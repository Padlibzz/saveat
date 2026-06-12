<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    use HasFactory;

   protected $fillable = [
        'merchant_id', // <-- PERBAIKAN
        'kategori_id', 
        'nama',
        'foto',
        'harga_normal',
        'harga_diskon',
        'stok_total',
        'stok_sisa',
        'batas_waktu',
        'status',
    ];

    protected $casts = [
        'batas_waktu' => 'datetime',
        'harga_normal' => 'float',
        'harga_diskon' => 'float',
    ];

    public function merchant(): BelongsTo
    {
        // PERBAIKAN: Merujuk ke model Profil, bukan Merchant
        return $this->belongsTo(Profil::class, 'merchant_id');
    }

    public function kategori(): BelongsTo
    {
        // Pastikan nama model kategori Anda adalah Kategori
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}