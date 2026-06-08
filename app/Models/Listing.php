<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_merchant',
        'id_categori',
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
        return $this->belongsTo(Merchant::class, 'id_merchant');
    }

    public function categori(): BelongsTo
    {
        return $this->belongsTo(Categori::class, 'id_categori');
    }
}
