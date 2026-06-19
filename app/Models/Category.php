<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    /**
     * Relasi ke Listing:
     * Satu kategori bisa dimiliki oleh banyak makanan (Listing)
     */
    public function listings()
    {
        return $this->hasMany(Listing::class, 'kategori_id');
    }
}
