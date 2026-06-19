<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use app\Models\category;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
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
        return $this->belongsTo(Profil::class, 'merchant_id');
    }

    public function kategori(): BelongsTo
    {
        // PERBAIKAN: Ubah 'categori_id' menjadi 'kategori_id'
        return $this->belongsTo(Category::class, 'kategori_id');
    }
}
